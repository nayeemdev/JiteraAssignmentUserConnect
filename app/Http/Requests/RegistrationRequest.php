<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'username' => 'required|string|min:3|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|max:255|confirmed',
            'phone' => 'nullable|string',
            'website' => 'nullable|string|url',
        ];
    }
}
