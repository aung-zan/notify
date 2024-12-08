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

    protected function getProviderAttribute(): string
    {
        return PushProvider::getNameByValue($this->attributes['provider']);
    }

    protected function getCredentialsAttribute(): array
    {
        $credentials = [];
        $decodedCredentials = json_decode($this->attributes['credentials']);

        foreach ($decodedCredentials as $key => $value) {
            $credentials[$key] = $value;
        }

        return $credentials;
    }
}
