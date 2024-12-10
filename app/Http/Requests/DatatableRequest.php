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
            'order' => 'sometimes|array|size:1',
            'order.*.column' => 'present_with:order',
            'order.*.dir' => 'present_with:order',
            'order.*.name' => 'present_with:order|nullable',
            'start' => 'required',
            'length' => 'required',
            'search' => 'required|array',
            'search.value' => 'nullable',
            'search.regex' => 'required',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'order.*.column' => "order's column",
            'order.*.dir' => "order's dir",
            'order.*.name' => "order's name",
        ];
    }
}
