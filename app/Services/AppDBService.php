<?php

namespace App\Services;

use App\Enums\Service;
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
        $hiddenColumns = ['user_id', 'description', 'scopes', 'token', 'refresh_token'];

        $searchValue = $this->getSearchRequest($request, $columns);

        $order = $this->getOrderRequest($request);

        $totalRecords = $this->appRepository->getAllCount();

        $filteredRecords = $this->appRepository->getAll($searchValue, $order);
        $records = $filteredRecords->makeHidden($hiddenColumns)
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
     * get an app details by id.
     * TODO: refactor $type not to based on the method.
     *
     * @param int $id
     * @param bool $type (false for show and true for edit)
     * @return array
     */
    public function getById(int $id): array
    {
        return $this->appRepository->getById($id)
            ->makeHidden(['user_id', 'scopes'])
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
