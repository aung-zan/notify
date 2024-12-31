<?php

namespace App\Services;

use App\Enums\Service;
use App\Models\App;
use App\Repositories\AppRepository;

class AppDBService extends DBService
{
    private $appRepository;

    public function __construct(AppRepository $appRepository)
    {
        $this->appRepository = $appRepository;
    }

    /**
     * get the app list by the datatable request.
     *
     * @param array $request
     * @return array
     */
    public function list(array $request): array
    {
        $draw = $request['draw'];
        $columns = ['name'];

        $searchValue = $this->getSearchRequest($request, $columns);

        $order = $this->getOrderRequest($request);

        $totalRecords = $this->appRepository->getAllCount();

        $filteredRecords = $this->appRepository->getAll($searchValue, $order);
        $records = $filteredRecords->makeHidden(['user_id', 'description', 'scopes', 'token', 'refresh_token'])
            ->slice($request['start'], $request['length'])
            ->values()
            ->toArray();

        return [
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords->count(),
            'data' => $records,
        ];
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

    /**
     * update an app's information by id.
     *
     * @param int $id
     * @param array $request
     * @return void
     */
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
    private function formatScopes(array $scopes): array
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
