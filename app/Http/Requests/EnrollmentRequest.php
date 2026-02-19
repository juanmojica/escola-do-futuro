<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnrollmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'enrollment_date' => 'nullable|date|before_or_equal:today',
            'status' => 'nullable|in:ativa,concluida,cancelada',
        ];

        if ($this->isMethod('post')) {
            $rules['student_id'] = 'required|exists:students,id';
            $rules['course_id'] = 'required|exists:courses,id';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'student_id.required' => 'O aluno é obrigatório.',
            'student_id.exists' => 'O aluno selecionado não existe.',
            'course_id.required' => 'O curso é obrigatório.',
            'course_id.exists' => 'O curso selecionado não existe.',
            'enrollment_date.date' => 'A data de matrícula deve ser uma data válida.',
            'enrollment_date.before_or_equal' => 'A data de matrícula não pode ser futura.',
            'status.in' => 'O status deve ser: ativa, concluída ou cancelada.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'student_id' => 'aluno',
            'course_id' => 'curso',
            'enrollment_date' => 'data de matrícula',
            'status' => 'status',
        ];
    }
}
