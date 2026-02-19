<?php

namespace App\Repositories\Contracts;

interface EnrollmentRepositoryInterface extends BaseRepositoryInterface
{
    public function enrollStudent($studentId, $courseId, array $data = []);
    public function getEnrollmentsByStudent($studentId);
    public function getEnrollmentsByCourse($courseId);
    public function checkEnrollmentExists($studentId, $courseId);
}
