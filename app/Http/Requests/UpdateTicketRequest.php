<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTicketRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'string|required|max:255',
            'message' => 'string|required|max:255',
            'priority' => 'integer|required',
        ];
    }

    public function getUser()
    {
        $payload = request()->attributes->get('jwt_payload');

        return $payload['sub'] ?? null;
    }

    public function messages()
    {
        return [
            'title.string' => 'The title must be a string.',
            'title.required' => 'The title is required.',
            'message.string' => 'The message must be a string.',
            'message.required' => 'The message is required.',
            'priority.integer' => 'The priority must be an integer.',
            'priority.required' => 'The priority is required.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
