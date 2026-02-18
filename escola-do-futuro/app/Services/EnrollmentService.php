<?php

namespace App\Services;

use App\Exceptions\BusinessException;
use App\Repositories\Contracts\EnrollmentRepositoryInterface;
use App\Repositories\Contracts\StudentRepositoryInterface;
use Illuminate\Support\Facades\DB;

class EnrollmentService
{
    protected $enrollmentRepository;
    protected $studentRepository;

    public function __construct(
        EnrollmentRepositoryInterface $enrollmentRepository,
        StudentRepositoryInterface $studentRepository
    ) {
        $this->enrollmentRepository = $enrollmentRepository;
        $this->studentRepository = $studentRepository;
    }

    public function getAllEnrollments()
    {
        return $this->enrollmentRepository->all();
    }

    public function getPaginatedEnrollments($perPage = 10, array $filters = [])
    {
        if (!empty($filters)) {
            return $this->enrollmentRepository->paginateWithFilters($filters, $perPage);
        }
        
        return $this->enrollmentRepository->paginate($perPage);
    }

    public function getEnrollment($id)
    {
        return $this->enrollmentRepository->find($id);
    }

    public function enrollStudent($studentId, $courseId, array $data = [])
    {
        if ($this->enrollmentRepository->checkEnrollmentExists($studentId, $courseId)) {
            throw new BusinessException('O estudante já está matriculado neste curso.');
        }

        return $this->enrollmentRepository->enrollStudent($studentId, $courseId, $data);
    }

    public function updateEnrollment($id, array $data)
    {
        return $this->enrollmentRepository->update($id, $data);
    }

    public function deleteEnrollment($id)
    {
        return $this->enrollmentRepository->delete($id);
    }

    public function getEnrollmentsByStudent($studentId)
    {
        return $this->enrollmentRepository->getEnrollmentsByStudent($studentId);
    }

    public function getEnrollmentsByCourse($courseId)
    {
        return $this->enrollmentRepository->getEnrollmentsByCourse($courseId);
    }
}
