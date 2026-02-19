<?php

namespace App\Repositories;

use App\Exceptions\BusinessException;
use App\Models\Teacher;
use App\Repositories\Contracts\TeacherRepositoryInterface;

class TeacherRepository extends BaseRepository implements TeacherRepositoryInterface
{
    public function __construct(Teacher $model)
    {
        parent::__construct($model);
    }

    public function findByEmail($email)
    {
        return $this->model->whereHas('user', function($query) use ($email) {
            $query->where('email', $email);
        })->first();
    }

    public function paginate($perPage = 10)
    {
        return $this->model->with('user')->orderBy('id')->paginate($perPage);
    }

    public function delete($id)
    {
        $teacher = $this->model->find($id);
        
        if (!$teacher) {
            throw new BusinessException('O Professor não foi encontrado.', 404);
        }

        $hasActiveEnrollments = $teacher->subjects()
            ->whereHas('course', function ($query) {
                $query->whereHas('enrollments', function ($enrollmentQuery) {
                    $enrollmentQuery->where('status', 'ativa');
                });
            })
            ->exists();

        if ($hasActiveEnrollments) {
            throw new BusinessException('O Professor não pode ser excluído pois está vinculado a disciplinas com matrículas ativas.', 409);
        }
    
        return $teacher->delete();
    }
}
