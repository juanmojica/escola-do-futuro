<?php

namespace App\Repositories;

use App\Models\Enrollment;
use App\Repositories\Contracts\EnrollmentRepositoryInterface;

class EnrollmentRepository extends BaseRepository implements EnrollmentRepositoryInterface
{
    public function __construct(Enrollment $model)
    {
        parent::__construct($model);
    }

    public function enrollStudent($studentId, $courseId, array $data = [])
    {
        $enrollmentData = array_merge([
            'student_id' => $studentId,
            'course_id' => $courseId,
            'enrollment_date' => now(),
            'status' => 'active',
        ], $data);

        return $this->create($enrollmentData);
    }

    public function getEnrollmentsByStudent($studentId)
    {
        return $this->model->where('student_id', $studentId)
            ->with(['course'])
            ->get();
    }

    public function getEnrollmentsByCourse($courseId)
    {
        return $this->model->where('course_id', $courseId)
            ->with(['student'])
            ->get();
    }

    public function checkEnrollmentExists($studentId, $courseId)
    {
        return $this->model
            ->where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->exists();
    }

    public function paginate($perPage = 10)
    {
        return $this->model->with(['student', 'course'])
            ->orderBy('enrollment_date', 'desc')
            ->paginate($perPage);
    }

    public function paginateWithFilters(array $filters = [], $perPage = 10)
    {
        $query = $this->model->with(['student', 'course']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['course_id'])) {
            $query->where('course_id', $filters['course_id']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('enrollment_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('enrollment_date', '<=', $filters['date_to']);
        }

        return $query->orderBy('enrollment_date', 'desc')->paginate($perPage);
    }
}
