<?php

namespace Tests\Unit\Requests;

use Tests\TestCase;
use App\Http\Requests\StudentRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Models\Student;

class StudentRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa as regras de validação do request
     */
    private function validateData(array $data)
    {
        $request = new StudentRequest();
        
        $validator = Validator::make($data, $request->rules(), $request->messages());
        
        return $validator;
    }

    /** @test */
    public function name_is_required()
    {
        // Arrange
        $data = [
            'email' => 'aluno@example.com',
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
        $this->assertEquals('O nome é obrigatório.', $validator->errors()->first('name'));
    }

    /** @test */
    public function name_must_be_a_string()
    {
        // Arrange
        $data = [
            'name' => 12345,
            'email' => 'aluno@example.com',
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    /** @test */
    public function name_cannot_exceed_255_characters()
    {
        // Arrange
        $data = [
            'name' => str_repeat('a', 256),
            'email' => 'aluno@example.com',
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
        $this->assertEquals('O nome não pode ter mais de 255 caracteres.', $validator->errors()->first('name'));
    }

    /** @test */
    public function name_with_255_characters_is_valid()
    {
        // Arrange
        $data = [
            'name' => str_repeat('a', 255),
            'email' => 'aluno@example.com',
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertFalse($validator->fails());
    }

    /** @test */
    public function email_is_required()
    {
        // Arrange
        $data = [
            'name' => 'João Silva',
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
        $this->assertEquals('O email é obrigatório.', $validator->errors()->first('email'));
    }

    /** @test */
    public function email_must_be_valid_format()
    {
        // Arrange
        $data = [
            'name' => 'João Silva',
            'email' => 'email-invalido',
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
        $this->assertEquals('O email deve ser um endereço válido.', $validator->errors()->first('email'));
    }

    /** @test */
    public function email_must_be_unique()
    {
        // Arrange
        $existingUser = factory(User::class)->create([
            'email' => 'joao@example.com',
        ]);

        $data = [
            'name' => 'Outro Aluno',
            'email' => 'joao@example.com', // Email já existe
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
        $this->assertEquals('Este email já está cadastrado.', $validator->errors()->first('email'));
    }

    /** @test */
    public function birth_date_is_optional()
    {
        // Arrange
        $data = [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            // birth_date não está presente
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertFalse($validator->fails());
    }

    /** @test */
    public function birth_date_must_be_a_valid_date()
    {
        // Arrange
        $data = [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'birth_date' => 'data-invalida',
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('birth_date', $validator->errors()->toArray());
        $this->assertEquals('A data de nascimento deve ser uma data válida.', $validator->errors()->first('birth_date'));
    }

    /** @test */
    public function birth_date_must_be_before_today()
    {
        // Arrange
        $tomorrow = now()->addDay()->format('Y-m-d');
        
        $data = [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'birth_date' => $tomorrow,
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('birth_date', $validator->errors()->toArray());
        $this->assertEquals('A data de nascimento deve ser anterior a hoje.', $validator->errors()->first('birth_date'));
    }

    /** @test */
    public function birth_date_today_is_invalid()
    {
        // Arrange
        $today = now()->format('Y-m-d');
        
        $data = [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'birth_date' => $today,
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('birth_date', $validator->errors()->toArray());
    }

    /** @test */
    public function birth_date_yesterday_is_valid()
    {
        // Arrange
        $yesterday = now()->subDay()->format('Y-m-d');
        
        $data = [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'birth_date' => $yesterday,
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertFalse($validator->fails());
    }

    /** @test */
    public function password_is_optional()
    {
        // Arrange
        $data = [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            // password não está presente
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertFalse($validator->fails());
    }

    /** @test */
    public function password_must_have_minimum_6_characters()
    {
        // Arrange
        $data = [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'password' => '12345', // Apenas 5 caracteres
            'password_confirmation' => '12345',
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
        $this->assertEquals('A senha deve ter no mínimo 6 caracteres.', $validator->errors()->first('password'));
    }

    /** @test */
    public function password_with_6_characters_is_valid()
    {
        // Arrange
        $data = [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'password' => '123456', // Exatamente 6 caracteres
            'password_confirmation' => '123456',
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertFalse($validator->fails());
    }

    /** @test */
    public function password_must_be_confirmed()
    {
        // Arrange
        $data = [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different_password',
        ];

        // Act
        $validator = $this->validateData($data);

        // Assert
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
        $this->assertEquals('A confirmação da senha não confere.', $validator->errors()->first('password'));
    }

    /** @test */
    public function password_confirmation_must_match()
    {
        // Arrange
        $data = [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
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
            'name' => 'João da Silva Santos',
            'email' => 'joao.silva@example.com',
            'birth_date' => '2000-05-15',
            'password' => 'senha123',
            'password_confirmation' => 'senha123',
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
        $data = [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
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

        $request = new StudentRequest();

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

        $request = new StudentRequest();

        // Act
        $authorized = $request->authorize();

        // Assert
        $this->assertFalse($authorized);
    }

    /** @test */
    public function authorize_returns_false_for_guest_users()
    {
        // Arrange - Nenhum usuário autenticado
        $request = new StudentRequest();

        // Act
        $authorized = $request->authorize();

        // Assert
        $this->assertFalse($authorized);
    }

    /** @test */
    public function custom_error_messages_are_returned()
    {
        // Arrange
        $request = new StudentRequest();
        $messages = $request->messages();

        // Assert
        $this->assertArrayHasKey('name.required', $messages);
        $this->assertArrayHasKey('name.max', $messages);
        $this->assertArrayHasKey('email.required', $messages);
        $this->assertArrayHasKey('email.email', $messages);
        $this->assertArrayHasKey('email.unique', $messages);
        $this->assertArrayHasKey('birth_date.date', $messages);
        $this->assertArrayHasKey('birth_date.before', $messages);
        $this->assertArrayHasKey('password.min', $messages);
        $this->assertArrayHasKey('password.confirmed', $messages);
        
        $this->assertEquals('O nome é obrigatório.', $messages['name.required']);
        $this->assertEquals('A senha deve ter no mínimo 6 caracteres.', $messages['password.min']);
    }

    /** @test */
    public function custom_attributes_are_returned()
    {
        // Arrange
        $request = new StudentRequest();
        $attributes = $request->attributes();

        // Assert
        $this->assertArrayHasKey('name', $attributes);
        $this->assertArrayHasKey('email', $attributes);
        $this->assertArrayHasKey('birth_date', $attributes);
        $this->assertArrayHasKey('password', $attributes);
        
        $this->assertEquals('nome', $attributes['name']);
        $this->assertEquals('email', $attributes['email']);
        $this->assertEquals('data de nascimento', $attributes['birth_date']);
        $this->assertEquals('senha', $attributes['password']);
    }
}
