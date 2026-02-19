<?php

namespace App\Repositories;

use App\Models\Subject;
use App\Repositories\Contracts\SubjectRepositoryInterface;

class SubjectRepository extends BaseRepository implements SubjectRepositoryInterface
{
    public function __construct(Subject $model)
    {
        parent::__construct($model);
    }

    public function getSubjectsByCourse($courseId)
    {
        return $this->model->where('course_id', $courseId)
            ->with(['teacher', 'course'])
            ->get();
    }

    public function getSubjectsByTeacher($teacherId)
    {
        return $this->model->where('teacher_id', $teacherId)
            ->with(['course'])
            ->get();
    }

    public function paginate($perPage = 10)
    {
        return $this->model->with(['course', 'teacher'])->paginate($perPage);
    }
}
