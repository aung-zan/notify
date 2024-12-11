<?php

namespace App\Models;

use App\Enums\PushProvider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Push extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'provider',
        'name',
        'credentials',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:Y-m-d H:i',
            'updated_at' => 'datetime:Y-m-d H:i',
        ];
    }

    /**
     * Accessor for provider attribute.
     *
     * @return string
     */
    protected function getProviderAttribute(): string
    {
        return PushProvider::getNameByValue($this->attributes['provider']);
    }

    /**
     * Accessor for credentials attribute.
     *
     * @return string
     */
    protected function getCredentialsAttribute(): array
    {
        $credentials = [];
        $decodedCredentials = json_decode($this->attributes['credentials']);

        foreach ($decodedCredentials as $key => $value) {
            $credentials[$key] = $value;
        }

        return $credentials;
    }

    /**
     * Mutator for user_id attribute.
     *
     * @param int $value
     * @return void
     */
    protected function setUserIdAttribute(int $value): void
    {
        $this->attributes['user_id'] = $value ?? 1;
    }

    /**
     * Mutator for credentials attribute.
     *
     * @param string $value
     * @return void
     */
    protected function setCredentialsAttribute(string $value): void
    {
        $credentialsArr = [];
        $rawCredentials = preg_split('/\r\n|\r|\n/', $value);
        $rawCredentials = str_replace(['"', "'"], '', $rawCredentials);

        foreach ($rawCredentials as $string) {
            list($key, $value) = explode('=', $string);
            $credentialsArr[trim($key)] = trim($value);
        }

        $this->attributes['credentials'] = json_encode($credentialsArr);
    }
}
