<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function edit()
    {
        $student = $this->studentService->getStudentByUserId(Auth::id());

        return view('student.profile.edit', compact('student'));
    }

    public function update(Request $request)
    {
        $student = $this->studentService->getStudentByUserId(Auth::id());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->user->id,
            'birth_date' => 'nullable|date',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $this->studentService->updateStudent($student->id, $validated);

        return redirect()->route('student.profile.edit')
            ->with('success', 'Perfil atualizado com sucesso!');
    }
}
