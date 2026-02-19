<?php

namespace App\Repositories;

use App\Exceptions\BusinessException;
use App\Models\Student;
use App\Repositories\Contracts\StudentRepositoryInterface;

class StudentRepository extends BaseRepository implements StudentRepositoryInterface
{
    public function __construct(Student $model)
    {
        parent::__construct($model);
    }

    public function findByEmail($email)
    {
        return $this->model->whereHas('user', function($query) use ($email) {
            $query->where('email', $email);
        })->first();
    }

    public function findByUserId($userId)
    {
        return $this->model->with(['user', 'courses', 'enrollments'])
            ->where('user_id', $userId)
            ->first();
    }

    public function searchByNameOrEmail($search)
    {
        return $this->model
            ->whereHas('user', function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->paginate(10);
    }

    public function getStudentWithCourses($id)
    {
        return $this->model->with(['courses', 'enrollments', 'user'])->findOrFail($id);
    }

    public function paginate($perPage = 10)
    {
        return $this->model->with('user')->orderBy('id')->paginate($perPage);
    }

    public function delete($id)
    {
        $student = $this->model->find($id);
        
        if (!$student) {
            throw new BusinessException('O Aluno não foi encontrado.', 404);
        }

        $hasActiveEnrollments = $student->enrollments()
            ->where('status', 'ativa')
            ->exists();

        if ($hasActiveEnrollments) {
            throw new BusinessException('O Aluno não pode ser excluído pois possui matrículas ativas.', 409);
        }
       
        return $student->delete();
    }
}
