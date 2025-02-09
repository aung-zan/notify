<?php

namespace App\Services;

use App\Enums\EmailProviders;

class EmailChannelService extends DBService
{
    /**
     * get the email channel list.
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

        $totalRecords = $this->table->getAllCount($this->userId);

        $filteredRecords = $this->table->getAll([
            $this->userId, $searchValue, $order
        ]);
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
     * return the requried data for email create page.
     *
     * @return array
     */
    public function create(): array
    {
        return EmailProviders::getAll();
    }

     /**
     * create an email channel.
     *
     * @param array $request
     * @return void
     */
    public function store(array $request): void
    {
        $request['user_id'] = $this->userId;

        $this->table->create($request);
    }

    /**
     * return an email channel details by id for show page.
     *
     * @param int $id
     * @return array
     */
    public function show(int $id): array
    {
        return $this->table->getById($id, $this->userId)
            ->setAppends(['provider_name', 'credentials_string'])
            ->toArray();
    }

    /**
     * return an email channel details by id for edit page.
     *
     * @param int $id
     * @return array
     */
    public function edit(int $id): array
    {
        return $this->show($id);
    }

    /**
     * update an email channel.
     *
     * @param int $id
     * @param array $request
     * @return void
     */
    public function update(int $id, array $request): void
    {
        $request['user_id'] = $this->userId;

        $this->table->getById($id, $this->userId);

        $this->table->update($id, $request);
    }

    /**
     * get all email channels and group by the provider.
     * used by AppDBService.
     *
     * @return array
     */
    public function getGroupByProvider(): array
    {
        return $this->table->getAll([$this->userId, [], []])
            ->select(['id', 'name', 'provider_name'])
            ->groupBy('provider_name')
            ->toArray();
    }

    /**
     * check an email channel.
     * used by AppRequest.
     *
     * @param int $id
     * @return bool
     */
    public function checkChannel(int $id): bool
    {
        return $this->table->checkById($id, $this->userId) ? true : false;
    }
}
