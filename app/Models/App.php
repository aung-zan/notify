<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'services',
        'channels',
        'service_display',
        'channel_display',
    ];

    /**
     * Get the notifications for the app.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Accessor for scopes attribute.
     *
     * @param string $value
     * @return array
     */
    protected function getScopesAttribute($value): array
    {
        $scopesArray = (array) json_decode($value);

        return $scopesArray;
    }

    /**
     * Accessor for services attribute.
     *
     * @return array
     */
    protected function getServicesAttribute(): array
    {
        $services = [];
        $serviceScopes = array_keys($this->scopes);

        foreach ($serviceScopes as $serviceScope) {
            list(, $service, ) = explode('.', $serviceScope);
            $services[] = $service;
        }

        return $services;
    }

    /**
     * Accessor for service_display attribute.
     *
     * @return array
     */
    protected function getServiceDisplayAttribute(): array
    {
        $serviceDisplay = [];
        $services = $this->services;

        foreach ($services as $service) {
            $serviceDisplay[] = $this->getServiceDisplay($service);
        }

        return $serviceDisplay;
    }

    /**
     * Accessor for channels attribute.
     *
     * @return array
     */
    protected function getChannelsAttribute(): array
    {
        return array_values($this->scopes);
    }

    /**
     * Accessor for channel_display attribute.
     *
     * @return array
     */
    protected function getChannelDisplayAttribute(): array
    {
        $channels = [];

        foreach ($this->scopes as $serviceScope => $channelId) {
            $channel = $this->getChannel($serviceScope, $channelId);
            $channels[] = substr($channel->name, 0, 16) . " - {$channel->provider_name}";
        }

        return $channels;
    }

    /**
     * Get the notification type.
     *
     * @param string $service
     * @return array
     */
    private function getServiceDisplay(string $service): string
    {
        $serviceDisplay = [
            'push' => 'Push',
            'email' => 'Email',
        ][$service];

        return $serviceDisplay;
    }

    /**
     * Get the channel by serviceScope and id.
     *
     * @param string $serviceScope
     * @param int $id
     * @return Model
     */
    private function getChannel(string $serviceScope, int $id): Model
    {
        list(, $type, ) = explode('.', $serviceScope);

        $table = [
            'push' => new Push(),
            'email' => new Email(),
        ][$type];

        return $table->select('name', 'provider')
            ->findOrFail($id);
    }
}
