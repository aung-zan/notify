<?php

namespace App\Services;

use App\Models\EmailChannel;
use App\Models\PushChannel;
use App\Repositories\EmailChannelRepository;

class EmailChannelService extends DBService
{
    private $emailChannelRepository;

    public function __construct(EmailChannelRepository $emailChannelRepository)
    {
        $this->emailChannelRepository = $emailChannelRepository;
    }

    /**
     * get the email_channels list by the datatable request.
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

        $totalRecords = $this->emailChannelRepository->getAllCount();

        $filteredRecords = $this->emailChannelRepository->getAll($searchValue, $order);
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
     * @return EmailChannel
     */
    public function create(array $request): EmailChannel
    {
        $request['user_id'] = 1;

        return $this->emailChannelRepository->create($request);
    }

    /**
     * get an email_channel details by id.
     *
     * @param int $id
     * @return array
     */
    public function getById(int $id): array
    {
        return $this->emailChannelRepository->getById($id)
            ->setAppends(['provider_name', 'credentials_string'])
            ->toArray();
    }

    public function update(int $id, array $request): void
    {
        $request['user_id'] = 1;

        $this->emailChannelRepository->update($id, $request);
    }
}
