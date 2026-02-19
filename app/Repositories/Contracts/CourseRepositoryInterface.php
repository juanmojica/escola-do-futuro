<?php

namespace App\Repositories\Contracts;

interface CourseRepositoryInterface extends BaseRepositoryInterface
{
    public function getCoursesWithSubjects();
    public function getCourseWithEnrollments($id);
}
