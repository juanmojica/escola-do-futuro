<?php

namespace App\Repositories\Contracts;

interface TeacherRepositoryInterface extends BaseRepositoryInterface
{
    public function findByEmail($email);
}
