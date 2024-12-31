<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class App extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'scopes',
        'name',
        'description',
        'token',
        'refresh_token',
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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'notifications',
        'channels'
    ];

    /**
     * Accessor for scopes attribute.
     *
     * @param string $value
     * @return array
     */
    protected function getScopesAttribute($value): array
    {
        $scopesArray = (array) json_decode($value);
        $scopes = [];

        foreach ($scopesArray as $key => $channelId) {
            $notification = $this->getNotificationType($key);
            // TODO: Implement for email notification.
            $channel = Push::findOrFail($channelId);

            $scopes[$notification] = substr($channel->name, 0, 16) . " - {$channel->provider_name}";
        }

        return $scopes;
    }

    /**
     * Accessor for notifications attribute.
     *
     * @return string
     */
    protected function getNotificationsAttribute(): string
    {
        return implode(', ', array_keys($this->scopes));
    }

    /**
     * Accessor for channels attribute.
     *
     * @return string
     */
    protected function getChannelsAttribute(): string
    {
        return implode(', ', array_values($this->scopes));
    }

    /**
     * Get the notification type.
     *
     * @param string $string
     * @return string
     */
    private function getNotificationType($string): string
    {
        $notificationType = [
            'push' => 'Push',
            'email' => 'Email',
        ];

        list(, $type, ) = explode('.', $string);

        return $notificationType[$type];
    }
}
