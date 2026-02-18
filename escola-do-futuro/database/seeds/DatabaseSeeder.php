<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@escola.com',
            'password' => Hash::make('123123123'),
            'is_admin' => true,
            'role' => 'admin',
        ]);

        // Create Student Users
        $studentUser1 = User::create([
            'name' => 'Emanuel Silva',
            'email' => 'emanuel@aluno.com',
            'password' => Hash::make('123123123'),
            'is_admin' => false,
            'role' => 'student',
        ]);

        $studentUser2 = User::create([
            'name' => 'Maria Santos',
            'email' => 'maria@aluno.com',
            'password' => Hash::make('123123123'),
            'is_admin' => false,
            'role' => 'student',
        ]);

        $studentUser3 = User::create([
            'name' => 'João Oliveira',
            'email' => 'joao@aluno.com',
            'password' => Hash::make('123123123'),
            'is_admin' => false,
            'role' => 'student',
        ]);

        $studentUser4 = User::create([
            'name' => 'Ana Costa',
            'email' => 'ana.costa@aluno.com',
            'password' => Hash::make('123123123'),
            'is_admin' => false,
            'role' => 'student',
        ]);

        $studentUser5 = User::create([
            'name' => 'Pedro Almeida',
            'email' => 'pedro@aluno.com',
            'password' => Hash::make('123123123'),
            'is_admin' => false,
            'role' => 'student',
        ]);

        $studentUser6 = User::create([
            'name' => 'Julia Fernandes',
            'email' => 'julia@aluno.com',
            'password' => Hash::make('123123123'),
            'is_admin' => false,
            'role' => 'student',
        ]);

        $studentUser7 = User::create([
            'name' => 'Lucas Pereira',
            'email' => 'lucas@aluno.com',
            'password' => Hash::make('123123123'),
            'is_admin' => false,
            'role' => 'student',
        ]);

        $studentUser8 = User::create([
            'name' => 'Beatriz Lima',
            'email' => 'beatriz@aluno.com',
            'password' => Hash::make('123123123'),
            'is_admin' => false,
            'role' => 'student',
        ]);

        // Create Teacher Users
        $teacherUser1 = User::create([
            'name' => 'Prof. Jubilut',
            'email' => 'jubilut@escola.com',
            'password' => Hash::make('123123123'),
            'is_admin' => false,
            'role' => 'teacher',
        ]);

        $teacherUser2 = User::create([
            'name' => 'Prof. Carlos',
            'email' => 'carlos@escola.com',
            'password' => Hash::make('123123123'),
            'is_admin' => false,
            'role' => 'teacher',
        ]);

        $teacherUser3 = User::create([
            'name' => 'Prof. Ana',
            'email' => 'ana@escola.com',
            'password' => Hash::make('123123123'),
            'is_admin' => false,
            'role' => 'teacher',
        ]);

        $teacherUser4 = User::create([
            'name' => 'Prof. Fernanda Lima',
            'email' => 'fernanda@escola.com',
            'password' => Hash::make('123123123'),
            'is_admin' => false,
            'role' => 'teacher',
        ]);

        $teacherUser5 = User::create([
            'name' => 'Prof. Roberto Souza',
            'email' => 'roberto@escola.com',
            'password' => Hash::make('123123123'),
            'is_admin' => false,
            'role' => 'teacher',
        ]);

        // Create Courses
        $biologia = Course::create([
            'title' => 'Biologia',
            'description' => 'Curso completo de Biologia',
            'start_date' => '2024-02-01',
            'end_date' => '2024-12-15',
        ]);

        $quimica = Course::create([
            'title' => 'Química',
            'description' => 'Curso completo de Química',
            'start_date' => '2024-02-01',
            'end_date' => '2024-12-15',
        ]);

        $fisica = Course::create([
            'title' => 'Física',
            'description' => 'Curso completo de Física',
            'start_date' => '2024-02-01',
            'end_date' => '2024-12-15',
        ]);

        $matematica = Course::create([
            'title' => 'Matemática',
            'description' => 'Curso completo de Matemática',
            'start_date' => '2024-01-15',
            'end_date' => '2024-11-30',
        ]);

        $programacao = Course::create([
            'title' => 'Programação Web',
            'description' => 'Desenvolvimento de aplicações web modernas',
            'start_date' => '2023-08-01',
            'end_date' => '2023-12-20',
        ]);

        $ingles = Course::create([
            'title' => 'Inglês Avançado',
            'description' => 'Curso de inglês nível avançado',
            'start_date' => '2023-03-01',
            'end_date' => '2023-11-30',
        ]);

        // Create Teachers
        $jubilut = Teacher::create([
            'user_id' => $teacherUser1->id,
        ]);

        $teacherQuimica = Teacher::create([
            'user_id' => $teacherUser2->id,
        ]);

        $teacherFisica = Teacher::create([
            'user_id' => $teacherUser3->id,
        ]);

        $teacherMatematica = Teacher::create([
            'user_id' => $teacherUser4->id,
        ]);

        $teacherProgramacao = Teacher::create([
            'user_id' => $teacherUser5->id,
        ]);

        // Create Subjects
        Subject::create([
            'title' => 'Biologia Celular',
            'description' => 'Estudo das células',
            'course_id' => $biologia->id,
            'teacher_id' => $jubilut->id,
        ]);

        Subject::create([
            'title' => 'Genética',
            'description' => 'Estudo da genética',
            'course_id' => $biologia->id,
            'teacher_id' => $jubilut->id,
        ]);

        Subject::create([
            'title' => 'Química Orgânica',
            'description' => 'Estudo de compostos orgânicos',
            'course_id' => $quimica->id,
            'teacher_id' => $teacherQuimica->id,
        ]);

        Subject::create([
            'title' => 'Mecânica',
            'description' => 'Estudo de forças e movimento',
            'course_id' => $fisica->id,
            'teacher_id' => $teacherFisica->id,
        ]);

        Subject::create([
            'title' => 'Eletromagnetismo',
            'description' => 'Estudo de eletricidade e magnetismo',
            'course_id' => $fisica->id,
            'teacher_id' => $teacherFisica->id,
        ]);

        Subject::create([
            'title' => 'Álgebra Linear',
            'description' => 'Estudo de vetores e matrizes',
            'course_id' => $matematica->id,
            'teacher_id' => $teacherMatematica->id,
        ]);

        Subject::create([
            'title' => 'Cálculo Diferencial',
            'description' => 'Introdução ao cálculo',
            'course_id' => $matematica->id,
            'teacher_id' => $teacherMatematica->id,
        ]);

        Subject::create([
            'title' => 'HTML e CSS',
            'description' => 'Fundamentos de desenvolvimento web',
            'course_id' => $programacao->id,
            'teacher_id' => $teacherProgramacao->id,
        ]);

        Subject::create([
            'title' => 'JavaScript Avançado',
            'description' => 'Programação JavaScript moderna',
            'course_id' => $programacao->id,
            'teacher_id' => $teacherProgramacao->id,
        ]);

        Subject::create([
            'title' => 'Laravel Framework',
            'description' => 'Desenvolvimento com Laravel',
            'course_id' => $programacao->id,
            'teacher_id' => $teacherProgramacao->id,
        ]);

        // Create Students
        $emanuel = Student::create([
            'user_id' => $studentUser1->id,
            'birth_date' => '2000-05-15',
        ]);

        $maria = Student::create([
            'user_id' => $studentUser2->id,
            'birth_date' => '1998-03-20',
        ]);

        $joao = Student::create([
            'user_id' => $studentUser3->id,
            'birth_date' => '2002-08-10',
        ]);

        $ana = Student::create([
            'user_id' => $studentUser4->id,
            'birth_date' => '1999-11-25',
        ]);

        $pedro = Student::create([
            'user_id' => $studentUser5->id,
            'birth_date' => '2001-07-18',
        ]);

        $julia = Student::create([
            'user_id' => $studentUser6->id,
            'birth_date' => '2000-12-05',
        ]);

        $lucas = Student::create([
            'user_id' => $studentUser7->id,
            'birth_date' => '1997-04-22',
        ]);

        $beatriz = Student::create([
            'user_id' => $studentUser8->id,
            'birth_date' => '2002-01-30',
        ]);

        // Create Enrollments

        // Matrículas ATIVAS
        Enrollment::create([
            'student_id' => $emanuel->id,
            'course_id' => $biologia->id,
            'enrollment_date' => '2024-02-01',
            'status' => 'ativa',
        ]);

        Enrollment::create([
            'student_id' => $emanuel->id,
            'course_id' => $quimica->id,
            'enrollment_date' => '2024-02-01',
            'status' => 'ativa',
        ]);

        Enrollment::create([
            'student_id' => $maria->id,
            'course_id' => $biologia->id,
            'enrollment_date' => '2024-02-01',
            'status' => 'ativa',
        ]);

        Enrollment::create([
            'student_id' => $joao->id,
            'course_id' => $fisica->id,
            'enrollment_date' => '2024-02-05',
            'status' => 'ativa',
        ]);

        Enrollment::create([
            'student_id' => $ana->id,
            'course_id' => $biologia->id,
            'enrollment_date' => '2024-02-03',
            'status' => 'ativa',
        ]);

        Enrollment::create([
            'student_id' => $ana->id,
            'course_id' => $quimica->id,
            'enrollment_date' => '2024-02-03',
            'status' => 'ativa',
        ]);

        Enrollment::create([
            'student_id' => $pedro->id,
            'course_id' => $matematica->id,
            'enrollment_date' => '2024-01-20',
            'status' => 'ativa',
        ]);

        Enrollment::create([
            'student_id' => $julia->id,
            'course_id' => $fisica->id,
            'enrollment_date' => '2024-02-10',
            'status' => 'ativa',
        ]);

        Enrollment::create([
            'student_id' => $beatriz->id,
            'course_id' => $matematica->id,
            'enrollment_date' => '2024-01-25',
            'status' => 'ativa',
        ]);

        // Matrículas CONCLUÍDAS
        Enrollment::create([
            'student_id' => $lucas->id,
            'course_id' => $programacao->id,
            'enrollment_date' => '2023-08-05',
            'status' => 'concluída',
        ]);

        Enrollment::create([
            'student_id' => $emanuel->id,
            'course_id' => $ingles->id,
            'enrollment_date' => '2023-03-10',
            'status' => 'concluída',
        ]);

        Enrollment::create([
            'student_id' => $maria->id,
            'course_id' => $programacao->id,
            'enrollment_date' => '2023-08-01',
            'status' => 'concluída',
        ]);

        Enrollment::create([
            'student_id' => $pedro->id,
            'course_id' => $ingles->id,
            'enrollment_date' => '2023-03-15',
            'status' => 'concluída',
        ]);

        Enrollment::create([
            'student_id' => $julia->id,
            'course_id' => $programacao->id,
            'enrollment_date' => '2023-08-10',
            'status' => 'concluída',
        ]);

        // Matrículas CANCELADAS
        Enrollment::create([
            'student_id' => $joao->id,
            'course_id' => $quimica->id,
            'enrollment_date' => '2024-02-01',
            'status' => 'cancelada',
        ]);

        Enrollment::create([
            'student_id' => $lucas->id,
            'course_id' => $fisica->id,
            'enrollment_date' => '2024-02-05',
            'status' => 'cancelada',
        ]);

        Enrollment::create([
            'student_id' => $beatriz->id,
            'course_id' => $biologia->id,
            'enrollment_date' => '2024-02-08',
            'status' => 'cancelada',
        ]);

        Enrollment::create([
            'student_id' => $ana->id,
            'course_id' => $ingles->id,
            'enrollment_date' => '2023-03-20',
            'status' => 'cancelada',
        ]);

        Enrollment::create([
            'student_id' => $pedro->id,
            'course_id' => $programacao->id,
            'enrollment_date' => '2023-08-15',
            'status' => 'cancelada',
        ]);
    }
}
