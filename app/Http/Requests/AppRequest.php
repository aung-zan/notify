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
            'services' => 'required|array',
            'channels' => 'required|array',
            'services.*' => ['required', new Enum(Service::class)],
            'channels.*' => 'required',
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
            'services.*' => 'service',
            'channels.*' => 'channel',
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
                    $services = $requestData['services'];
                    $channels = $requestData['channels'];

                    foreach ($channels as $key => $channel) {
                        if ($services[$key] === Service::Push->value) {
                            if (! $this->channelDBService->checkChannel($channel)) {
                                $validator->errors()->add("channels.{$key}", 'The selected channel is invalid.');
                            }
                        } elseif ($services[$key] === Service::Email->value) {
                            # TODO: Implement channel validation for email.
                        }
                    }
                }
            },
        ];
    }
}
