<?php

namespace App\Services;

use App\Enums\Service;
use App\Models\App;
use App\Repositories\AppRepository;

class AppDBService
{
    private $appRepository;

    public function __construct(AppRepository $appRepository)
    {
        $this->appRepository = $appRepository;
    }

    /**
     * create an app.
     *
     * @param array $request
     * @return array
     */
    public function create(array $request): array
    {
        $request['user_id'] = 1;
        $request['scopes'] = json_encode($this->formatScopes($request['scopes']));

        return $this->appRepository->create($request)
            ->toArray();
    }

    public function update(int $id, array $request): void
    {
        $this->appRepository->update($id, $request);
    }

    /**
     * Format scopes.
     *
     * @param array $scopes
     * @return array
     */
    public function formatScopes(array $scopes): array
    {
        $formatScopes = [];

        foreach ($scopes as $scope) {
            $service = 'notifications.' . strtolower(Service::getNameByValue($scope['service'])) . '.send';
            $formatScopes = [
                $service => $scope['channel']
            ];
        }

        return $formatScopes;
    }
}
