<?php

namespace App\Services;

use App\Repositories\Contracts\TeacherRepositoryInterface;
use App\Exceptions\BusinessException;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeacherService
{
    protected $teacherRepository;

    public function __construct(TeacherRepositoryInterface $teacherRepository)
    {
        $this->teacherRepository = $teacherRepository;
    }

    public function getAllTeachers()
    {
        return $this->teacherRepository->all();
    }

    public function getPaginatedTeachers($perPage = 10)
    {
        return $this->teacherRepository->paginate($perPage);
    }

    public function getTeacher($id)
    {
        $teacher = $this->teacherRepository->find($id);
        
        return $teacher;
    }

    public function createTeacher(array $data)
    {
        return DB::transaction(function () use ($data) {

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password'] ?? 'password'),
                'is_admin' => false,
                'role' => 'teacher',
            ]);

            $teacher = $this->teacherRepository->create([
                'user_id' => $user->id,
            ]);
            
            return $teacher;
        });
    }

    public function updateTeacher($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            $teacher = $this->teacherRepository->find($id);
            
            if (!$teacher) {
                throw new BusinessException('Professor não encontrado.', 404);
            }
            
            if ($teacher->user) {
                $userData = [
                    'name' => $data['name'],
                    'email' => $data['email'],
                ];
                
                if (isset($data['password']) && !empty($data['password'])) {
                    $userData['password'] = Hash::make($data['password']);
                }
                
                $teacher->user->update($userData);
            }
            
            return $teacher->fresh();
        });
    }

    public function deleteTeacher($id)
    {
        return $this->teacherRepository->delete($id);        
    }
}
