<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignProjectMembersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('edit_project') ?? false;
    }

    public function rules(): array
    {
        return [
            'members' => ['array'],
            'members.*.user_id' => ['required', 'exists:users,id'],
            'members.*.role' => ['nullable', 'string', 'max:50'],
        ];
    }
}
