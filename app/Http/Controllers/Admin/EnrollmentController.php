<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EnrollmentRequest;
use App\Services\EnrollmentService;
use App\Services\StudentService;
use App\Services\CourseService;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    protected $enrollmentService;
    protected $studentService;
    protected $courseService;

    public function __construct(
        EnrollmentService $enrollmentService,
        StudentService $studentService,
        CourseService $courseService
    ) {
        $this->enrollmentService = $enrollmentService;
        $this->studentService = $studentService;
        $this->courseService = $courseService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'course_id', 'date_from', 'date_to']);
        
        $enrollments = $this->enrollmentService->getPaginatedEnrollments(10, $filters);
        $courses = $this->courseService->getAllCourses();
        
        return view('admin.enrollments.index', compact('enrollments', 'filters', 'courses'));
    }

    public function create()
    {
        $students = $this->studentService->getAllStudents();
        $courses = $this->courseService->getAllCourses();
        return view('admin.enrollments.create', compact('students', 'courses'));
    }

    public function store(EnrollmentRequest $request)
    {
        $validated = $request->validated();

        $this->enrollmentService->enrollStudent(
            $validated['student_id'],
            $validated['course_id'],
            $validated
        );

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Matrícula realizada com sucesso!');
    }

    public function show($id)
    {
        $enrollment = $this->enrollmentService->getEnrollment($id);
        return view('admin.enrollments.show', compact('enrollment'));
    }

    public function edit($id)
    {
        $enrollment = $this->enrollmentService->getEnrollment($id);
        $students = $this->studentService->getAllStudents();
        $courses = $this->courseService->getAllCourses();
        
        return view('admin.enrollments.edit', compact('enrollment', 'students', 'courses'));
    }

    public function update(EnrollmentRequest $request, $id)
    {
        $validated = $request->validated();

        $this->enrollmentService->updateEnrollment($id, $validated);

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Matrícula atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $this->enrollmentService->deleteEnrollment($id);

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Matrícula excluída com sucesso!');
    }
}
