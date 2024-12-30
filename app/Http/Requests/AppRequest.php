<?php

namespace App\Http\Requests;

use App\Enums\Service;
use App\Services\ChannelDBService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

class AppRequest extends FormRequest
{
    private $channelDBService;

    public function __construct(ChannelDBService $channelDBService)
    {
        $this->channelDBService = $channelDBService;
    }

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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'scopes' => 'required|array',
            'scopes.*.service' => ['required', new Enum(Service::class)],
            'scopes.*.channel' => 'required',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'scopes.*.service' => 'service',
            'scopes.*.channel' => 'channel',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'scopes.required' => 'The service and channel fields are required.',
        ];
    }

    /**
     * Get the "after" validation callables for the request.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isEmpty()) {
                    $requestData = $validator->validated();
                    $scopes = $requestData['scopes'];

                    foreach ($scopes as $key => $scope) {
                        // TODO: Add validation for email service.
                        if ((int)$scope['service'] === Service::Push->value) {
                            if (! $this->channelDBService->checkChannel($scope['channel'])) {
                                $validator->errors()->add("scopes.{$key}.channel", 'The selected channel is invalid.');
                            }
                        }
                    }
                }
            },
        ];
    }
}
