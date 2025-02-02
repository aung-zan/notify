<?php

namespace App\Services;

use App\Models\PushChannel;
use App\Repositories\PushRepository;

class PushChannelService
{
    private $pushRepository;

    public function __construct(PushRepository $pushRepository)
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
        $searchValue = [];
        $order = [];

        if (! is_null($request['search']['value'])) {
            $searchValue = [
                'name' => $request['search']['value'],
                'provider' => $request['search']['value'],
            ];
        }

        if (array_key_exists('order', $request)) {
            $column = $request['order'][0]['column'];
            $order = [
                'column' => $request['columns'][$column]['data'],
                'dir' => $request['order'][0]['dir'],
            ];
        }

        $totalRecords = $this->pushRepository->getAllCount();

        $filteredRecords = $this->pushRepository->getAll($searchValue, $order);
        $records = $filteredRecords->makeHidden('credentials')
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
     * TODO: refactor $type not to based on the method.
     *
     * @param int $id
     * @param bool $type (false for show and true for edit)
     * @return array
     */
    public function getById(int $id, bool $type = false): array
    {
        $channel = $this->pushRepository->getById($id)
            ->toArray();

        if ($type) {
            $credentialString = '';
            foreach ($channel['credentials'] as $key => $value) {
                $credentialString .= $key . " = '" . $value . "'\n";
            }

            $channel['credentials'] = $credentialString;
        }

        return $channel;
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
        $this->pushRepository->save($id, $request);
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
        $channels = $this->pushRepository->getAll([], [])
            ->select(['id', 'name', 'provider_name'])
            ->groupBy('provider_name')
            ->toArray();

        return $channels;
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
