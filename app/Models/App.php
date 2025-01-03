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
        'services',
        'channels'
    ];

    /**
     * cache the scopes column's values.
     * this is used to prevent multiple query executions because of the appends attributes.
     * there is still going to be multiple query executions if the app's record is more than one.
     *
     * TODO: Implement caching mechanism <redis> and make changes in a query.
     */
    private $cachedScopes;

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
            $services[] = $this->getService($serviceScope);
        }

        return $services;
    }

    /**
     * Accessor for channels attribute.
     *
     * @return array
     */
    protected function getChannelsAttribute(): array
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
     * @param string $serviceScope
     * @return array
     */
    private function getService(string $serviceScope): string
    {
        $servicesType = [
            'push' => 'Push',
            'email' => 'Email',
        ];

        list(, $type, ) = explode('.', $serviceScope);

        return $servicesType[$type];
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
