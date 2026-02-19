<?php

namespace Tests\Unit\Requests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Requests\EnrollmentRequest;
use App\Models\Student;
use App\Models\Course;
use App\Models\Enrollment;
use App\User;
use Illuminate\Support\Facades\Validator;

class EnrollmentRequestTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = factory(User::class)->create(['is_admin' => true]);
        $this->actingAs($this->user);
    }

    /** @test */
    public function student_id_is_required_on_post()
    {
        $request = new EnrollmentRequest();
        $request->setMethod('POST');

        $validator = Validator::make([], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('student_id', $validator->errors()->toArray());
    }

    /** @test */
    public function student_id_is_not_required_on_put()
    {
        $request = new EnrollmentRequest();
        $request->setMethod('PUT');

        $validator = Validator::make([], $request->rules());

        $this->assertFalse($validator->errors()->has('student_id'));
    }

    /** @test */
    public function course_id_is_required_on_post()
    {
        $request = new EnrollmentRequest();
        $request->setMethod('POST');

        $validator = Validator::make([], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('course_id', $validator->errors()->toArray());
    }

    /** @test */
    public function course_id_is_not_required_on_put()
    {
        $request = new EnrollmentRequest();
        $request->setMethod('PUT');

        $validator = Validator::make([], $request->rules());

        $this->assertFalse($validator->errors()->has('course_id'));
    }

    /** @test */
    public function student_id_must_exist_in_students_table()
    {
        $request = new EnrollmentRequest();
        $request->setMethod('POST');

        $validator = Validator::make([
            'student_id' => 999,
            'course_id' => 1,
        ], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('student_id', $validator->errors()->toArray());
    }

    /** @test */
    public function student_id_passes_when_exists()
    {
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create();

        $request = new EnrollmentRequest();
        $request->setMethod('POST');

        $validator = Validator::make([
            'student_id' => $student->id,
            'course_id' => $course->id,
        ], $request->rules());

        $this->assertFalse($validator->errors()->has('student_id'));
    }

    /** @test */
    public function course_id_must_exist_in_courses_table()
    {
        $student = factory(Student::class)->create();

        $request = new EnrollmentRequest();
        $request->setMethod('POST');

        $validator = Validator::make([
            'student_id' => $student->id,
            'course_id' => 999,
        ], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('course_id', $validator->errors()->toArray());
    }

    /** @test */
    public function course_id_passes_when_exists()
    {
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create();

        $request = new EnrollmentRequest();
        $request->setMethod('POST');

        $validator = Validator::make([
            'student_id' => $student->id,
            'course_id' => $course->id,
        ], $request->rules());

        $this->assertFalse($validator->errors()->has('course_id'));
    }

    /** @test */
    public function enrollment_date_is_optional()
    {
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create();

        $request = new EnrollmentRequest();
        $request->setMethod('POST');

        $validator = Validator::make([
            'student_id' => $student->id,
            'course_id' => $course->id,
        ], $request->rules());

        $this->assertFalse($validator->errors()->has('enrollment_date'));
    }

    /** @test */
    public function enrollment_date_must_be_a_valid_date()
    {
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create();

        $request = new EnrollmentRequest();
        $request->setMethod('POST');

        $validator = Validator::make([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrollment_date' => 'not-a-date',
        ], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('enrollment_date', $validator->errors()->toArray());
    }

    /** @test */
    public function enrollment_date_must_be_before_or_equal_today()
    {
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create();

        $request = new EnrollmentRequest();
        $request->setMethod('POST');

        $tomorrow = now()->addDay()->format('Y-m-d');

        $validator = Validator::make([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrollment_date' => $tomorrow,
        ], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('enrollment_date', $validator->errors()->toArray());
    }

    /** @test */
    public function enrollment_date_can_be_today()
    {
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create();

        $request = new EnrollmentRequest();
        $request->setMethod('POST');

        $today = now()->format('Y-m-d');

        $validator = Validator::make([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrollment_date' => $today,
        ], $request->rules());

        $this->assertFalse($validator->errors()->has('enrollment_date'));
    }

    /** @test */
    public function enrollment_date_can_be_in_the_past()
    {
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create();

        $request = new EnrollmentRequest();
        $request->setMethod('POST');

        $yesterday = now()->subDay()->format('Y-m-d');

        $validator = Validator::make([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrollment_date' => $yesterday,
        ], $request->rules());

        $this->assertFalse($validator->errors()->has('enrollment_date'));
    }

    /** @test */
    public function status_is_optional()
    {
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create();

        $request = new EnrollmentRequest();
        $request->setMethod('POST');

        $validator = Validator::make([
            'student_id' => $student->id,
            'course_id' => $course->id,
        ], $request->rules());

        $this->assertFalse($validator->errors()->has('status'));
    }

    /** @test */
    public function status_must_be_ativa()
    {
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create();

        $request = new EnrollmentRequest();
        $request->setMethod('POST');

        $validator = Validator::make([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'ativa',
        ], $request->rules());

        $this->assertFalse($validator->errors()->has('status'));
    }

    /** @test */
    public function status_must_be_concluida()
    {
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create();

        $request = new EnrollmentRequest();
        $request->setMethod('POST');

        $validator = Validator::make([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'concluida',
        ], $request->rules());

        $this->assertFalse($validator->errors()->has('status'));
    }

    /** @test */
    public function status_must_be_cancelada()
    {
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create();

        $request = new EnrollmentRequest();
        $request->setMethod('POST');

        $validator = Validator::make([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'cancelada',
        ], $request->rules());

        $this->assertFalse($validator->errors()->has('status'));
    }

    /** @test */
    public function status_cannot_be_invalid_value()
    {
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create();

        $request = new EnrollmentRequest();
        $request->setMethod('POST');

        $validator = Validator::make([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'invalido',
        ], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('status', $validator->errors()->toArray());
    }

    /** @test */
    public function admin_can_authorize()
    {
        $adminUser = factory(User::class)->create(['is_admin' => true]);
        $this->actingAs($adminUser);

        $request = new EnrollmentRequest();
        
        $this->assertTrue($request->authorize());
    }

    /** @test */
    public function non_admin_cannot_authorize()
    {
        $nonAdminUser = factory(User::class)->create(['is_admin' => false]);
        $this->actingAs($nonAdminUser);

        $request = new EnrollmentRequest();
        
        $this->assertFalse($request->authorize());
    }

    /** @test */
    public function it_has_custom_messages()
    {
        $request = new EnrollmentRequest();
        $messages = $request->messages();

        $this->assertArrayHasKey('student_id.required', $messages);
        $this->assertArrayHasKey('course_id.required', $messages);
        $this->assertArrayHasKey('enrollment_date.date', $messages);
        $this->assertArrayHasKey('status.in', $messages);
    }

    /** @test */
    public function it_has_custom_attributes()
    {
        $request = new EnrollmentRequest();
        $attributes = $request->attributes();

        $this->assertArrayHasKey('student_id', $attributes);
        $this->assertArrayHasKey('course_id', $attributes);
        $this->assertArrayHasKey('enrollment_date', $attributes);
        $this->assertArrayHasKey('status', $attributes);
    }

    /** @test */
    public function all_required_fields_pass_validation_on_post()
    {
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create();

        $request = new EnrollmentRequest();
        $request->setMethod('POST');

        $validator = Validator::make([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrollment_date' => now()->format('Y-m-d'),
            'status' => 'ativa',
        ], $request->rules());

        $this->assertFalse($validator->fails());
    }

    /** @test */
    public function update_with_only_status_passes_validation()
    {
        $request = new EnrollmentRequest();
        $request->setMethod('PUT');

        $validator = Validator::make([
            'status' => 'concluida',
        ], $request->rules());

        $this->assertFalse($validator->fails());
    }
}
