<?php

namespace App\Services;

use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentService
{
    protected $studentRepository;

    public function __construct(StudentRepositoryInterface $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function getAllStudents()
    {
        return $this->studentRepository->all();
    }

    public function getPaginatedStudents($perPage = 10)
    {
        return $this->studentRepository->paginate($perPage);
    }

    public function getStudent($id)
    {
        $student = $this->studentRepository->find($id);
        
        return $student;
    }

    public function createStudent(array $data)
    {
        return DB::transaction(function () use ($data) {

            $user = \App\User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password'] ?? 'password'),
                'is_admin' => false,
                'role' => 'student',
            ]);

            $student = $this->studentRepository->create([
                'user_id' => $user->id,
                'birth_date' => $data['birth_date'] ?? null,
            ]);
            
            return $student;
        });
    }

    public function updateStudent($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            $student = $this->studentRepository->find($id);
            
            if (!$student) {
                throw new BusinessException('Aluno não encontrado.', 404);
            }
            
            if ($student->user) {
                $userData = [
                    'name' => $data['name'],
                    'email' => $data['email'],
                ];
                
                if (isset($data['password']) && !empty($data['password'])) {
                    $userData['password'] = Hash::make($data['password']);
                }
                
                $student->user->update($userData);
            }

            $this->studentRepository->update($id, [
                'birth_date' => $data['birth_date'] ?? null,
            ]);
            
            return $student->fresh();
        });
    }

    public function deleteStudent($id)
    {
        $deleted = $this->studentRepository->delete($id);
        
        return $deleted;
    }

    public function searchStudents($search)
    {
        return $this->studentRepository->searchByNameOrEmail($search);
    }

    public function getStudentWithCourses($id)
    {
        $student = $this->studentRepository->getStudentWithCourses($id);
        
        if (!$student) {
            throw new BusinessException('Aluno não encontrado.', 404);
        }
        
        return $student;
    }

    public function getStudentByUserId($userId)
    {
        $student = $this->studentRepository->findByUserId($userId);
        
        if (!$student) {
            throw new BusinessException('Você não possui um perfil de aluno associado.', 403);
        }
        
        return $student;
    }
}
