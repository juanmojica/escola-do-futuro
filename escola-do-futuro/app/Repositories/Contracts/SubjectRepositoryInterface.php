<?php

namespace App\Repositories\Contracts;

interface SubjectRepositoryInterface extends BaseRepositoryInterface
{
    public function getSubjectsByCourse($courseId);
    public function getSubjectsByTeacher($teacherId);
}
