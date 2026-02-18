<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Services\CourseService;

class CourseController extends Controller
{
    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function index()
    {
        $courses = $this->courseService->getPaginatedCourses();
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(CourseRequest $request)
    {
        $this->courseService->createCourse($request->validated());

        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso criado com sucesso!');
    }

    public function show($id)
    {
        $course = $this->courseService->getCourseWithEnrollments($id);
        return view('admin.courses.show', compact('course'));
    }

    public function edit($id)
    {
        $course = $this->courseService->getCourse($id);
        return view('admin.courses.edit', compact('course'));
    }

    public function update(CourseRequest $request, $id)
    {
        $this->courseService->updateCourse($id, $request->validated());

        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $this->courseService->deleteCourse($id);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso excluído com sucesso!');
    }
}
