<?php

namespace App\Services;

use App\Models\Course;
use Carbon\Carbon;

class ReportService
{
    /**
     * Get age statistics for students in a course
     *
     * @param int $courseId
     * @return array
     */
    public function getCourseAgeReport($courseId)
    {
        $course = Course::with(['students' => function ($query) {
            $query->whereNotNull('birth_date');
        }])->findOrFail($courseId);

        if ($course->students->isEmpty()) {
            return [
                'course' => $course,
                'average_age' => null,
                'youngest_student' => null,
                'oldest_student' => null,
                'total_students' => 0,
            ];
        }

        $ages = $course->students->map(function ($student) {
            return [
                'student' => $student,
                'age' => Carbon::parse($student->birth_date)->age,
            ];
        });

        $youngestData = $ages->sortBy('age')->first();
        $oldestData = $ages->sortByDesc('age')->first();
        $averageAge = $ages->avg('age');

        return [
            'course' => $course,
            'average_age' => round($averageAge, 2),
            'youngest_student' => $youngestData['student'],
            'youngest_age' => $youngestData['age'],
            'oldest_student' => $oldestData['student'],
            'oldest_age' => $oldestData['age'],
            'total_students' => $course->students->count(),
        ];
    }

    /**
     * Get age statistics for all courses
     *
     * @param int|null $courseId
     * @return array<int, array<string, mixed>>
     */
    public function getAllCoursesAgeReport($courseId = null)
    {
        $query = Course::with(['students' => function ($query) {
            $query->whereNotNull('birth_date');
        }]);

        if ($courseId) {
            $query->where('id', $courseId);
        }

        $courses = $query->get();

        $reports = [];

        foreach ($courses as $course) {
            if ($course->students->isEmpty()) {
                $reports[] = [
                    'course' => $course,
                    'average_age' => null,
                    'youngest_student' => null,
                    'oldest_student' => null,
                    'total_students' => 0,
                ];
                continue;
            }

            $ages = $course->students->map(function ($student) {
                return [
                    'student' => $student,
                    'age' => Carbon::parse($student->birth_date)->age,
                ];
            });

            $youngestData = $ages->sortBy('age')->first();
            $oldestData = $ages->sortByDesc('age')->first();
            $averageAge = (int) $ages->avg('age');

            $reports[] = [
                'course' => $course,
                'average_age' => round($averageAge, 2),
                'youngest_student' => $youngestData['student'],
                'youngest_age' => $youngestData['age'],
                'oldest_student' => $oldestData['student'],
                'oldest_age' => $oldestData['age'],
                'total_students' => $course->students->count(),
            ];
        }

        return $reports;
    }
}
