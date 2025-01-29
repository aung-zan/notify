<?php

namespace App\Models;

use App\Enums\PushProviders;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushChannel extends Model
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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['provider_name'];

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
     * Accessor for provider_name attribute.
     *
     * @return string
     */
    protected function getProviderNameAttribute(): string
    {
        return PushProviders::getNameByValue($this->attributes['provider']);
    }

    /**
     * Accessor for credentials attribute.
     *
     * @return array
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
     * Accessor for credentials_string attribute.
     *
     * @return string
     */
    protected function getCredentialsStringAttribute(): string
    {
        $credentials = $this->getCredentialsAttribute();
        $credentialsString = '';

        foreach ($credentials as $key => $value) {
            $credentialsString .= $key . "=" . $value . "\n";
        }

        return $credentialsString;
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
