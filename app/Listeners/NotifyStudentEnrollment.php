<?php

namespace App\Listeners;

use App\Events\StudentEnrolled;
use App\Models\Notification;
use App\Jobs\SendEnrollmentNotification;

class NotifyStudentEnrollment
{
    /**
     * Handle the event.
     *
     * @param  StudentEnrolled  $event
     * @return void
     */
    public function handle(StudentEnrolled $event)
    {
        $enrollment = $event->enrollment;
        $student = $enrollment->student;
        $course = $enrollment->course;

        Notification::create([
            'user_id' => $student->user_id,
            'type' => 'enrollment',
            'message' => "Você foi matriculado(a) no curso: {$course->title}",
            'data' => [
                'enrollment_id' => $enrollment->id,
                'course_id' => $course->id,
                'course_title' => $course->title,
                'enrollment_date' => $enrollment->enrollment_date->format('d/m/Y'),
                'status' => $enrollment->status,
            ],
        ]);

        SendEnrollmentNotification::dispatch($enrollment);
    }
}
