<?php

namespace App\Jobs;

use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendEnrollmentNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $enrollment;

    public $tries = 3;

    public $timeout = 60;

    public $backoff = 30;

    /**
     * Create a new job instance.
     *
     * @param Enrollment $enrollment
     * @return void
     */
    public function __construct(Enrollment $enrollment)
    {
        $this->enrollment = $enrollment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $student = $this->enrollment->student;
        $course = $this->enrollment->course;
        $studentUser = $student->user;

        $data = [
            'student_name' => $studentUser->name,
            'course_title' => $course->title,
            'course_description' => $course->description,
            'start_date' => $course->start_date->format('d/m/Y'),
            'end_date' => $course->end_date->format('d/m/Y'),
            'enrollment_date' => $this->enrollment->enrollment_date->format('d/m/Y'),
        ];

        Mail::send('emails.student-enrollment', $data, function ($message) use ($studentUser, $course) {
            $message->to($studentUser->email, $studentUser->name)
                    ->subject('Matrícula Confirmada - ' . $course->title);
        });

        Log::info("Email de matrícula enviado para: {$studentUser->email} - Curso: {$course->title}");
    }

    /**
     * Método executado quando o job falha definitivamente
     *
     * @param \Exception $exception
     * @return void
     */
    public function failed(\Exception $exception)
    {
        Log::error('Job de envio de email falhou após todas as tentativas', [
            'enrollment_id' => $this->enrollment->id,
            'student_id' => $this->enrollment->student_id,
            'course_id' => $this->enrollment->course_id,
            'error' => $exception->getMessage(),
        ]);
    }
}
