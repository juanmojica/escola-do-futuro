<?php

namespace App\Repositories\Contracts;

interface StudentRepositoryInterface extends BaseRepositoryInterface
{
    public function findByEmail($email);
    public function searchByNameOrEmail($search);
    public function getStudentWithCourses($id);
}
