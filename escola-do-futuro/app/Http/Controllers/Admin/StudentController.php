<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Services\StudentService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        
        if ($search) {
            $students = $this->studentService->searchStudents($search);
        } else {
            $students = $this->studentService->getPaginatedStudents();
        }

        return view('admin.students.index', compact('students', 'search'));
    }

    public function create()
    {
        return view('admin.students.create');
    }

    public function store(StudentRequest $request)
    {
        $this->studentService->createStudent($request->validated());

        return redirect()->route('admin.students.index')
            ->with('success', 'Aluno criado com sucesso!');
    }

    public function show($id)
    {
        $student = $this->studentService->getStudentWithCourses($id);
        return view('admin.students.show', compact('student'));
    }

    public function edit($id)
    {
        $student = $this->studentService->getStudent($id);
        return view('admin.students.edit', compact('student'));
    }

    public function update(StudentRequest $request, $id)
    {
        $this->studentService->updateStudent($id, $request->validated());

        return redirect()->route('admin.students.index')
            ->with('success', 'Aluno atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $this->studentService->deleteStudent($id);

        return redirect()->route('admin.students.index')
            ->with('success', 'Aluno excluído com sucesso!');
    }
}
