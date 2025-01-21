<?php

namespace Database\Factories;

use App\Enums\PushProviders;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PushChannels>
 */
class PushChannelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $provider = fake()->randomElement(PushProviders::class)?->value;

        return [
            'user_id' => 1,
            'provider' => $provider,
            'name' => PushProviders::getNameByValue($provider) . ' Channel',
            'credentials' => 'app_id = "1885"
            key = "26c0723"
            secret = "80e7f5"
            cluster = "ad1"',
        ];
    }
}
