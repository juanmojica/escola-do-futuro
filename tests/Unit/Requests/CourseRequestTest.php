<?php

namespace Tests\Unit\Requests;

use Tests\TestCase;
use App\Http\Requests\CourseRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class CourseRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa as regras de validação do request
     */
    private function validateData(array $data)
    {
        $request = new CourseRequest();
        
        $validator = Validator::make($data, $request->rules(), $request->messages());
        
        return $validator;
    }

    /** @test */
    public function title_is_required()
    {
        // Arrange
        $data = [
            'description' => 'Descrição do curso',
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
        $data = [
            'title' => 12345, // Número ao invés de string
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
        $data = [
            'title' => str_repeat('a', 256), // 256 caracteres
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
        $data = [
            'title' => str_repeat('a', 255), // Exatamente 255 caracteres
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
        $data = [
            'title' => 'Curso de PHP',
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
        $data = [
            'title' => 'Curso de PHP',
            'description' => 12345, // Número ao invés de string
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('description', $validator->errors()->toArray());
    }

    /** @test */
    public function start_date_is_optional()
    {
        // Arrange
        $data = [
            'title' => 'Curso de PHP',
            // start_date não está presente
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertFalse($validator->fails());
    }

    /** @test */
    public function start_date_must_be_a_valid_date()
    {
        // Arrange
        $data = [
            'title' => 'Curso de PHP',
            'start_date' => 'data-invalida',
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('start_date', $validator->errors()->toArray());
        $this->assertEquals('A data de início deve ser uma data válida.', $validator->errors()->first('start_date'));
    }

    /** @test */
    public function end_date_is_optional()
    {
        // Arrange
        $data = [
            'title' => 'Curso de PHP',
            'start_date' => '2024-01-01',
            // end_date não está presente
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertFalse($validator->fails());
    }

    /** @test */
    public function end_date_must_be_a_valid_date()
    {
        // Arrange
        $data = [
            'title' => 'Curso de PHP',
            'end_date' => 'data-invalida',
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('end_date', $validator->errors()->toArray());
        $this->assertEquals('A data de término deve ser uma data válida.', $validator->errors()->first('end_date'));
    }

    /** @test */
    public function end_date_must_be_after_or_equal_to_start_date()
    {
        // Arrange - End date antes de start date
        $data = [
            'title' => 'Curso de PHP',
            'start_date' => '2024-06-01',
            'end_date' => '2024-05-01', // Antes da data de início
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('end_date', $validator->errors()->toArray());
        $this->assertEquals('A data de término deve ser igual ou posterior à data de início.', $validator->errors()->first('end_date'));
    }

    /** @test */
    public function end_date_can_be_equal_to_start_date()
    {
        // Arrange - Mesma data para início e fim
        $data = [
            'title' => 'Workshop Intensivo',
            'start_date' => '2024-06-01',
            'end_date' => '2024-06-01', // Mesma data
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertFalse($validator->fails());
    }

    /** @test */
    public function end_date_can_be_after_start_date()
    {
        // Arrange
        $data = [
            'title' => 'Curso de PHP',
            'start_date' => '2024-01-01',
            'end_date' => '2024-06-30', // Depois da data de início
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertFalse($validator->fails());
    }

    /** @test */
    public function validation_passes_with_all_valid_data()
    {
        // Arrange
        $data = [
            'title' => 'Curso Completo de Laravel',
            'description' => 'Aprenda Laravel do básico ao avançado',
            'start_date' => '2024-03-01',
            'end_date' => '2024-08-31',
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertFalse($validator->fails());
    }

    /** @test */
    public function validation_passes_with_only_required_fields()
    {
        // Arrange - Apenas o título (campo obrigatório)
        $data = [
            'title' => 'Curso Básico',
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

        $request = new CourseRequest();

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

        $request = new CourseRequest();

        // Act
        $authorized = $request->authorize();

        // Assert
        $this->assertFalse($authorized);
    }

    /** @test */
    public function authorize_returns_false_for_guest_users()
    {
        // Arrange - Nenhum usuário autenticado
        $request = new CourseRequest();

        // Act
        $authorized = $request->authorize();

        // Assert
        $this->assertFalse($authorized);
    }

    /** @test */
    public function custom_error_messages_are_returned()
    {
        // Arrange
        $request = new CourseRequest();
        $messages = $request->messages();

        // Assert
        $this->assertArrayHasKey('title.required', $messages);
        $this->assertArrayHasKey('title.max', $messages);
        $this->assertArrayHasKey('start_date.date', $messages);
        $this->assertArrayHasKey('end_date.date', $messages);
        $this->assertArrayHasKey('end_date.after_or_equal', $messages);
        
        $this->assertEquals('O título é obrigatório.', $messages['title.required']);
        $this->assertEquals('O título não pode ter mais de 255 caracteres.', $messages['title.max']);
    }
}
