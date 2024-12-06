<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DatatableRequest extends FormRequest
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
            'draw' => 'required',
            'columns' => 'required|array',
            'order' => 'required|array',
            'order.*.column' => 'required',
            'order.*.dir' => 'required',
            'order.*.name' => 'nullable',
            'start' => 'required',
            'length' => 'required',
            'search' => 'required|array',
            'search.value' => 'nullable',
            'search.regex' => 'required',
        ];
    }
}
