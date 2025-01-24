<?php

namespace Database\Factories;

use App\Enums\EmailProviders;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmailChannel>
 */
class EmailChannelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $provider = fake()->randomElement(EmailProviders::class)?->value;

        return [
            'user_id' => 1,
            'provider' => $provider,
            'name' => EmailProviders::getNameByValue($provider) . ' Channel',
            'credentials' => 'MAIL_MAILER=smtp
            MAIL_HOST=sandbox.smtp.mailtrap.io
            MAIL_PORT=2525
            MAIL_USERNAME=1a601ae54273fa
            MAIL_PASSWORD=ec32bbd0f06979',
        ];
    }
}
