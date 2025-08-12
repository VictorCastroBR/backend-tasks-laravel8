<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $target = $this->route('user');

        Gate::forUser($this->user())->authorize('update', $target);
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $userId = $this->route('user')->id ?? null;

        return [
            'name' => ['required','string','max:120'],
            'email' => ['required','email','max:150','unique:users,email,'.$userId],
            'is_admin' => ['nullable','boolean'],
        ];
    }
}
