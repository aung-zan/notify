<?php

namespace Database\Factories;

use App\Enums\PushProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Push>
 */
class PushFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $provider = fake()->randomElement(PushProvider::class)?->value;

        return [
            'user_id' => 1,
            'provider' => $provider,
            'name' => PushProvider::getNameByValue($provider) . ' Channel',
            'credentials' => json_encode([
                'app_id' => '1885',
                'key' => '26c0723',
                'secret' => '80e7f5',
                'cluster' => 'ad1',
            ]),
        ];
    }
}
