<?php

namespace Tests\Unit\Requests;

use Tests\TestCase;
use App\Http\Requests\SubjectRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Models\Course;
use App\Models\Teacher;

class SubjectRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa as regras de validação do request
     */
    private function validateData(array $data)
    {
        $request = new SubjectRequest();
        
        $validator = Validator::make($data, $request->rules(), $request->messages());
        
        return $validator;
    }

    /** @test */
    public function title_is_required()
    {
        // Arrange
        $data = [
            'description' => 'Descrição da disciplina',
            'course_id' => 1,
            'teacher_id' => 1,
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('title', $validator->errors()->toArray());
        $this->assertEquals('O título é obrigatório.', $validator->errors()->first('title'));
    }

    /** @test */
    public function title_must_be_a_string()
    {
        // Arrange
        $course = factory(Course::class)->create();
        $teacher = factory(Teacher::class)->create();

        $data = [
            'title' => 12345, // Número ao invés de string
            'course_id' => $course->id,
            'teacher_id' => $teacher->id,
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('title', $validator->errors()->toArray());
    }

    /** @test */
    public function title_cannot_exceed_255_characters()
    {
        // Arrange
        $course = factory(Course::class)->create();
        $teacher = factory(Teacher::class)->create();

        $data = [
            'title' => str_repeat('a', 256), // 256 caracteres
            'course_id' => $course->id,
            'teacher_id' => $teacher->id,
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('title', $validator->errors()->toArray());
        $this->assertEquals('O título não pode ter mais de 255 caracteres.', $validator->errors()->first('title'));
    }

    /** @test */
    public function title_with_255_characters_is_valid()
    {
        // Arrange
        $course = factory(Course::class)->create();
        $teacher = factory(Teacher::class)->create();

        $data = [
            'title' => str_repeat('a', 255), // Exatamente 255 caracteres
            'course_id' => $course->id,
            'teacher_id' => $teacher->id,
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertFalse($validator->fails());
    }

    /** @test */
    public function description_is_optional()
    {
        // Arrange
        $course = factory(Course::class)->create();
        $teacher = factory(Teacher::class)->create();

        $data = [
            'title' => 'Introdução ao PHP',
            'course_id' => $course->id,
            'teacher_id' => $teacher->id,
            // description não está presente
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertFalse($validator->fails());
    }

    /** @test */
    public function description_must_be_a_string_when_provided()
    {
        // Arrange
        $course = factory(Course::class)->create();
        $teacher = factory(Teacher::class)->create();

        $data = [
            'title' => 'Introdução ao PHP',
            'description' => 12345, // Número ao invés de string
            'course_id' => $course->id,
            'teacher_id' => $teacher->id,
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('description', $validator->errors()->toArray());
    }

    /** @test */
    public function course_id_is_required()
    {
        // Arrange
        $teacher = factory(Teacher::class)->create();

        $data = [
            'title' => 'Introdução ao PHP',
            'teacher_id' => $teacher->id,
            // course_id não está presente
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('course_id', $validator->errors()->toArray());
        $this->assertEquals('O curso é obrigatório.', $validator->errors()->first('course_id'));
    }

    /** @test */
    public function course_id_must_exist_in_courses_table()
    {
        // Arrange
        $teacher = factory(Teacher::class)->create();

        $data = [
            'title' => 'Introdução ao PHP',
            'course_id' => 99999, // ID que não existe
            'teacher_id' => $teacher->id,
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('course_id', $validator->errors()->toArray());
        $this->assertEquals('O curso selecionado não existe.', $validator->errors()->first('course_id'));
    }

    /** @test */
    public function teacher_id_is_required()
    {
        // Arrange
        $course = factory(Course::class)->create();

        $data = [
            'title' => 'Introdução ao PHP',
            'course_id' => $course->id,
            // teacher_id não está presente
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('teacher_id', $validator->errors()->toArray());
        $this->assertEquals('O professor é obrigatório.', $validator->errors()->first('teacher_id'));
    }

    /** @test */
    public function teacher_id_must_exist_in_teachers_table()
    {
        // Arrange
        $course = factory(Course::class)->create();

        $data = [
            'title' => 'Introdução ao PHP',
            'course_id' => $course->id,
            'teacher_id' => 99999, // ID que não existe
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('teacher_id', $validator->errors()->toArray());
        $this->assertEquals('O professor selecionado não existe.', $validator->errors()->first('teacher_id'));
    }

    /** @test */
    public function validation_passes_with_all_valid_data()
    {
        // Arrange
        $course = factory(Course::class)->create();
        $teacher = factory(Teacher::class)->create();

        $data = [
            'title' => 'Programação Orientada a Objetos',
            'description' => 'Conceitos avançados de POO em PHP',
            'course_id' => $course->id,
            'teacher_id' => $teacher->id,
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertFalse($validator->fails());
    }

    /** @test */
    public function validation_passes_with_only_required_fields()
    {
        // Arrange
        $course = factory(Course::class)->create();
        $teacher = factory(Teacher::class)->create();

        $data = [
            'title' => 'PHP Básico',
            'course_id' => $course->id,
            'teacher_id' => $teacher->id,
            // description é opcional
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertFalse($validator->fails());
    }

    /** @test */
    public function authorize_returns_true_for_admin_users()
    {
        // Arrange
        $adminUser = factory(User::class)->create([
            'is_admin' => true,
        ]);

        $this->actingAs($adminUser);

        $request = new SubjectRequest();

        // Act
        $authorized = $request->authorize();

        // Assert
        $this->assertTrue($authorized);
    }

    /** @test */
    public function authorize_returns_false_for_non_admin_users()
    {
        // Arrange
        $regularUser = factory(User::class)->create([
            'is_admin' => false,
        ]);

        $this->actingAs($regularUser);

        $request = new SubjectRequest();

        // Act
        $authorized = $request->authorize();

        // Assert
        $this->assertFalse($authorized);
    }

    /** @test */
    public function authorize_returns_false_for_guest_users()
    {
        // Arrange - Nenhum usuário autenticado
        $request = new SubjectRequest();

        // Act
        $authorized = $request->authorize();

        // Assert
        $this->assertFalse($authorized);
    }

    /** @test */
    public function custom_error_messages_are_returned()
    {
        // Arrange
        $request = new SubjectRequest();
        $messages = $request->messages();

        // Assert
        $this->assertArrayHasKey('title.required', $messages);
        $this->assertArrayHasKey('title.max', $messages);
        $this->assertArrayHasKey('course_id.required', $messages);
        $this->assertArrayHasKey('course_id.exists', $messages);
        $this->assertArrayHasKey('teacher_id.required', $messages);
        $this->assertArrayHasKey('teacher_id.exists', $messages);
        
        $this->assertEquals('O título é obrigatório.', $messages['title.required']);
        $this->assertEquals('O curso é obrigatório.', $messages['course_id.required']);
        $this->assertEquals('O professor é obrigatório.', $messages['teacher_id.required']);
    }

    /** @test */
    public function custom_attributes_are_returned()
    {
        // Arrange
        $request = new SubjectRequest();
        $attributes = $request->attributes();

        // Assert
        $this->assertArrayHasKey('title', $attributes);
        $this->assertArrayHasKey('description', $attributes);
        $this->assertArrayHasKey('course_id', $attributes);
        $this->assertArrayHasKey('teacher_id', $attributes);
        
        $this->assertEquals('título', $attributes['title']);
        $this->assertEquals('curso', $attributes['course_id']);
        $this->assertEquals('professor', $attributes['teacher_id']);
    }
}
