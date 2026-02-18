<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\EnrollmentService;
use App\Services\StudentService;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    protected $enrollmentService;
    protected $studentService;

    public function __construct(EnrollmentService $enrollmentService, StudentService $studentService)
    {
        $this->enrollmentService = $enrollmentService;
        $this->studentService = $studentService;
    }

    public function index()
    {
        $student = $this->studentService->getStudentByUserId(Auth::id());

        $enrollments = $this->enrollmentService->getEnrollmentsByStudent($student->id);

        return view('student.enrollments.index', compact('enrollments'));
    }
}
