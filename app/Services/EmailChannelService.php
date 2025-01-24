<?php

namespace App\Services;

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
}
