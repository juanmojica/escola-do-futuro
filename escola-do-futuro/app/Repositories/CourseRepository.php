<?php

namespace App\Repositories;

use App\Exceptions\BusinessException;
use App\Models\Course;
use App\Repositories\Contracts\CourseRepositoryInterface;

class CourseRepository extends BaseRepository implements CourseRepositoryInterface
{
    public function __construct(Course $model)
    {
        parent::__construct($model);
    }

    public function delete($id)
    {
        $course = $this->model->findOrFail($id);
        
        if ($course->enrollments()->where('status', 'ativa')->exists()) {
            throw new BusinessException('Não é possível excluir um curso com matrículas ativas.', 409);
        }
        
        return $course->delete();
    }

    public function getCoursesWithSubjects()
    {
        return $this->model->with('subjects')->get();
    }

    public function getCourseWithEnrollments($id)
    {
        return $this->model->with(['enrollments.student', 'subjects'])->findOrFail($id);
    }
}
