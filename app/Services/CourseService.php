<?php

namespace App\Services;

use App\Repositories\Contracts\CourseRepositoryInterface;

class CourseService
{
    protected $courseRepository;

    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function getAllCourses()
    {
        return $this->courseRepository->all();
    }

    public function getPaginatedCourses($perPage = 10)
    {
        return $this->courseRepository->paginate($perPage);
    }

    public function getCourse($id)
    {
        return $this->courseRepository->find($id);
    }

    public function createCourse(array $data)
    {
        return $this->courseRepository->create($data);
    }

    public function updateCourse($id, array $data)
    {
        return $this->courseRepository->update($id, $data);
    }

    public function deleteCourse($id)
    {
        return $this->courseRepository->delete($id);
    }

    public function getCourseWithEnrollments($id)
    {
        return $this->courseRepository->getCourseWithEnrollments($id);
    }
}
