<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\StudentService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function index()
    {
        $student = $this->studentService->getStudentByUserId(Auth::id());

        return view('student.dashboard', compact('student'));
    }
}
