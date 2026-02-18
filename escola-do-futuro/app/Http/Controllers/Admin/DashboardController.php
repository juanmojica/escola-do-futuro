<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use App\Services\CourseService;

class DashboardController extends Controller
{
    protected $reportService;
    protected $courseService;

    public function __construct(ReportService $reportService, CourseService $courseService)
    {
        $this->reportService = $reportService;
        $this->courseService = $courseService;
    }

    public function index()
    {
        // Dados para gráfico de alunos por curso
        $courses = $this->courseService->getAllCourses();
        $studentsPerCourse = [];
        
        foreach ($courses as $course) {
            $studentsPerCourse[] = [
                'course' => $course->title,
                'count' => $course->students()->count()
            ];
        }

        // Dados para gráfico de idade por curso
        $ageReports = $this->reportService->getAllCoursesAgeReport();
        $ageData = [];
        
        foreach ($ageReports as $report) {
            if ($report['total_students'] > 0) {
                $ageData[] = [
                    'course' => $report['course']->title,
                    'average' => $report['average_age'],
                    'youngest' => $report['youngest_age'],
                    'oldest' => $report['oldest_age']
                ];
            }
        }

        return view('admin.dashboard', compact('studentsPerCourse', 'ageData'));
    }
}
