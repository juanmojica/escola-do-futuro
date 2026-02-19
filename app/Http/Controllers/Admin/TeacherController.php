<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TeacherService;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    protected $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    public function index()
    {
        $teachers = $this->teacherService->getPaginatedTeachers();
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $this->teacherService->createTeacher($validated);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Professor criado com sucesso!');
    }

    public function show($id)
    {
        $teacher = $this->teacherService->getTeacher($id);
        return view('admin.teachers.show', compact('teacher'));
    }

    public function edit($id)
    {
        $teacher = $this->teacherService->getTeacher($id);
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, $id)
    {
        $teacher = $this->teacherService->getTeacher($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($teacher->user ? $teacher->user->id : ''),
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $this->teacherService->updateTeacher($id, $validated);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Professor atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $this->teacherService->deleteTeacher($id);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Professor excluído com sucesso!');
    }
}
