<?php

namespace App\Http\Requests;

use App\Enums\EmailProviders;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class EmailRequest extends FormRequest
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
        if ($this->isMethod('post')) {
            return [
                'provider' => ['required', new Enum(EmailProviders::class)],
                'name' => 'required|max:100',
                'credentials' => 'required|min:10',
            ];
        }

        return [
            'name' => 'required|max:100',
            'credentials' => 'sometimes|min:10',
        ];
    }
}
