<?php

namespace App\Services;

use App\Repositories\Contracts\SubjectRepositoryInterface;

class SubjectService
{
    protected $subjectRepository;

    public function __construct(SubjectRepositoryInterface $subjectRepository)
    {
        $this->subjectRepository = $subjectRepository;
    }

    public function getAllSubjects()
    {
        return $this->subjectRepository->all();
    }

    public function getPaginatedSubjects($perPage = 10)
    {
        return $this->subjectRepository->paginate($perPage);
    }

    public function getSubject($id)
    {
        return $this->subjectRepository->find($id);
    }

    public function createSubject(array $data)
    {
        return $this->subjectRepository->create($data);
    }

    public function updateSubject($id, array $data)
    {
        return $this->subjectRepository->update($id, $data);
    }

    public function deleteSubject($id)
    {
        return $this->subjectRepository->delete($id);
    }

    public function getSubjectsByCourse($courseId)
    {
        return $this->subjectRepository->getSubjectsByCourse($courseId);
    }

    public function getSubjectsByTeacher($teacherId)
    {
        return $this->subjectRepository->getSubjectsByTeacher($teacherId);
    }
}
