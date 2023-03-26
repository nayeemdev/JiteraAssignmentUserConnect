<?php

namespace App\Http\Requests;

use App\Rules\CheckNotFollowing;

class UnFollowRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'user' => [
                'required',
                'exists:users,id',
                new CheckNotFollowing,
            ],
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array<string, string>
     */
    public function all($keys = null): array
    {
        $data = parent::all($keys);
        $data['user'] = $this->route('user');
        return $data;
    }


    public function messages(): array
    {
        return [
            'user.exists' => 'User does not exist, please try with a valid user',
        ];
    }
}
