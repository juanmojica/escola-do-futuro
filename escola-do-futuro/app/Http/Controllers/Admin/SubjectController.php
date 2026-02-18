<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubjectRequest;
use App\Services\SubjectService;
use App\Services\CourseService;
use App\Services\TeacherService;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    protected $subjectService;
    protected $courseService;
    protected $teacherService;

    public function __construct(
        SubjectService $subjectService,
        CourseService $courseService,
        TeacherService $teacherService
    ) {
        $this->subjectService = $subjectService;
        $this->courseService = $courseService;
        $this->teacherService = $teacherService;
    }

    public function index()
    {
        $subjects = $this->subjectService->getPaginatedSubjects();
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $courses = $this->courseService->getAllCourses();
        $teachers = $this->teacherService->getAllTeachers();
        return view('admin.subjects.create', compact('courses', 'teachers'));
    }

    public function store(SubjectRequest $request)
    {
        $this->subjectService->createSubject($request->validated());

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Disciplina criada com sucesso!');
    }

    public function show($id)
    {
        $subject = $this->subjectService->getSubject($id);
        return view('admin.subjects.show', compact('subject'));
    }

    public function edit($id)
    {
        $subject = $this->subjectService->getSubject($id);
        $courses = $this->courseService->getAllCourses();
        $teachers = $this->teacherService->getAllTeachers();
        return view('admin.subjects.edit', compact('subject', 'courses', 'teachers'));
    }

    public function update(SubjectRequest $request, $id)
    {
        $this->subjectService->updateSubject($id, $request->validated());

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Disciplina atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $this->subjectService->deleteSubject($id);

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Disciplina excluída com sucesso!');
    }
}
