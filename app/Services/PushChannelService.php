<?php

namespace App\Services;

use App\Models\PushChannel;
use App\Repositories\PushChannelRepository;

class PushChannelService extends DBService
{
    private $pushRepository;

    public function __construct(PushChannelRepository $pushRepository)
    {
        $this->pushRepository = $pushRepository;
    }

    /**
     * get the channel list by the datatable request.
     *
     * @param array $request
     * @return array
     */
    public function list(array $request): array
    {
        $draw = $request['draw'];
        $columns = ['name', 'provider'];
        $hiddenColumns = ['credentials'];

        $searchValue = $this->getSearchRequest($request, $columns);

        $order = $this->getOrderRequest($request);

        $totalRecords = $this->pushRepository->getAllCount();

        $filteredRecords = $this->pushRepository->getAll($searchValue, $order);
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
     * create a channel.
     *
     * @param array $request
     * @return PushChannel
     */
    public function create(array $request): PushChannel
    {
        $request['user_id'] = 1;

        return $this->pushRepository->create($request);
    }

    /**
     * get the channel details by id.
     *
     * @param int $id
     * @param bool $type (false for show and true for edit)
     * @return array
     */
    public function getById(int $id): array
    {
        return $this->pushRepository->getById($id)
            ->setAppends(['provider_name', 'credentials_string'])
            ->toArray();
    }

    /**
     * update a channel's information.
     *
     * @param int $id
     * @param array $request
     * @return void
     */
    public function update(int $id, array $request): void
    {
        $this->pushRepository->update($id, $request);
    }

    /**
     * get the channel's info by id for the testing.
     *
     * @param int $id
     * @return array
     */
    public function getByIdForTest(int $id): array
    {
        $channel = $this->getById($id);

        return [
            'id' => $channel['id'],
            'provider_name' => $channel['provider_name'],
            'name' => $channel['name'],
            'key' => $channel['credentials']['key'],
            'cluster' => $channel['credentials']['cluster'],
        ];
    }

    /**
     * get all the channels and group by the provider.
     *
     * @return array
     */
    public function getByGroupProvider(): array
    {
        return $this->pushRepository->getAll([], [])
            ->select(['id', 'name', 'provider_name'])
            ->groupBy('provider_name')
            ->toArray();
    }

    /**
     * check the channel by id is valid or not.
     *
     * @param int $id
     * @return bool
     */
    public function checkChannel(int $id): bool
    {
        return $this->pushRepository->getById($id, true) ? true : false;
    }
}
