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
        $request['scopes'] = $this->createScopes($request);

        return $this->appRepository->create($request)
            ->toArray();
    }

    /**
     * get an app details by id.
     *
     * @param int $id
     * @param bool $hideAppends
     * @return array
     */
    public function getById(int $id, bool $hideAppends = false): array
    {
        $hideColumns = ['scopes'];

        if ($hideAppends) {
            $hideColumns = ['scopes', 'service_display', 'channel_display', 'token', 'refresh_token'];
        }

        return $this->appRepository->getById($id)
            ->makeHidden($hideColumns)
            ->toArray();
    }

    /**
     * update an app's information by id.
     *
     * @param int $id
     * @param array $request
     * @param bool $createScopes
     * @return void
     */
    public function update(int $id, array $request, bool $createScopes = false): void
    {
        if ($createScopes) {
            $request['scopes'] = $this->createScopes($request);
        }

        $this->appRepository->update($id, $request);
    }

    /**
     * create and format scopes.
     * TODO: Think about new format for permissions.
     *
     * @param array $request
     * @return string
     */
    private function createScopes(array &$request): string
    {
        $scopes = [];
        $channels = $request['channels'];

        foreach ($channels as $key => $channel) {
            $service = 'notifications.' . $key;
            $scopes[$service] = $channel;
        }

        unset($request['services']);
        unset($request['channels']);

        return json_encode($scopes);
    }
}
