<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use App\Services\CourseService;
use Illuminate\Http\Request;
use PDF;

class ReportController extends Controller
{
    protected $reportService;
    protected $courseService;

    public function __construct(ReportService $reportService, CourseService $courseService)
    {
        $this->reportService = $reportService;
        $this->courseService = $courseService;
    }

    public function index(Request $request)
    {
        $courseId = $request->get('course_id');
        
        $reports = $this->reportService->getAllCoursesAgeReport($courseId);
        $courses = $this->courseService->getAllCourses();
        
        return view('admin.reports.index', compact('reports', 'courses', 'courseId'));
    }

    public function show($courseId)
    {
        $report = $this->reportService->getCourseAgeReport($courseId);
        return view('admin.reports.show', compact('report'));
    }

    public function exportPdf(Request $request)
    {
        $courseId = $request->get('course_id');
        
        $reports = $this->reportService->getAllCoursesAgeReport($courseId);
        $courses = $this->courseService->getAllCourses();
        
        $pdf = PDF::loadView('admin.reports.pdf', compact('reports', 'courseId'));
        
        $filename = 'relatorio-idade-cursos-' . date('Y-m-d-His') . '.pdf';
        
        return $pdf->download($filename);
    }
}
