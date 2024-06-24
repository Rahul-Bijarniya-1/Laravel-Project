<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
                'user_id' => 'required_without_all:resource_type,resource_id|integer|exists:users2,id',
                'resource_type' => 'required_with:resource_id|string|in:customer,transporter',
                'resource_id' => 'required_with:resource_type|integer',
        ];
    }
}
