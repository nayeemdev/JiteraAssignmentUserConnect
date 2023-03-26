<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class FollowRequest extends BaseFormRequest
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
                Rule::notIn([auth()->id()]),
                Rule::unique('followers', 'user_id')->where(function ($query) {
                    return $query->where('follower_id', auth()->id())
                        ->where('user_id', $this->user);
                }),
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
        $data['user'] = $data['user'] ?? $this->route('user');
        return $data;
    }

    public function messages(): array
    {
        return [
            'user.exists' => 'User does not exist, please try with a valid user',
            'user.not_in' => 'You cannot follow yourself',
            'user.unique' => 'You are already following this user',
        ];
    }
}
