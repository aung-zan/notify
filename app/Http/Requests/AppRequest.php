<?php

namespace App\Http\Requests;

use App\Enums\Service;
use App\Services\EmailChannelService;
use App\Services\PushChannelService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

class AppRequest extends FormRequest
{
    private $pushChannelService;
    private $emailChannelService;

    public function __construct(PushChannelService $pushChannelService, EmailChannelService $emailChannelService)
    {
        $this->pushChannelService = $pushChannelService;
        $this->emailChannelService = $emailChannelService;
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
                    $channels = $requestData['channels'];
                    $channelRepo = [
                        $this->pushChannelService,
                        $this->emailChannelService,
                    ];

                    foreach ($channels as $key => $channel) {
                        if (
                            ! array_key_exists($key, $channelRepo) ||
                            ! $channelRepo[$key]->checkChannel($channel)
                        ) {
                            $validator->errors()->add("channels", 'The selected channel is invalid.');
                        }
                    }
                }
            },
        ];
    }
}
