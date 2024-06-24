<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TripRequestStoreRequest extends FormRequest
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
    public function rules()
    {
        return [
            'customer_id' => 'nullable|exists:customers,id',
            'transporter_id' => 'nullable|exists:transporters,id',
            'source' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,completed,cancelled',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $customerId = $this->input('customer_id');
            $transporterId = $this->input('transporter_id');

            if (!$customerId && !$transporterId) {
                $validator->errors()->add('customer_id', 'Either customer_id or transporter_id must be provided.');
                $validator->errors()->add('transporter_id', 'Either customer_id or transporter_id must be provided.');
            }

            if ($customerId && $transporterId) {
                $validator->errors()->add('customer_id', 'Only one of customer_id or transporter_id can be provided.');
                $validator->errors()->add('transporter_id', 'Only one of customer_id or transporter_id can be provided.');
            }
        });
    }
}
