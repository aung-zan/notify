<?php

namespace App\Services;

use App\Enums\Service;
use App\Exceptions\IsUsedException;
use App\Repositories\AppRepository;
use Illuminate\Support\Facades\DB;

class AppDBService extends DBService
{
    public function __construct(
        AppRepository $table,
        private PushChannelService $pushChannelService,
        private EmailChannelService $emailChannelService,
        private TokenService $tokenService
    ) {
        $this->table = $table;
    }

    /**
     * return the app list.
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
     * return the requried data for app create page.
     *
     * @return array
     */
    public function create(): array
    {
        $services = array_column(Service::cases(), 'value');
        $channels = [
            'push' => $this->pushChannelService->getGroupByProvider(),
            'email' => $this->emailChannelService->getGroupByProvider()
        ];

        return [$services, $channels];
    }

    /**
     * store an app.
     *
     * @param array $request
     * @return void
     */
    public function store(array $request): void
    {
        DB::transaction(function () use ($request) {
            $data = $request;
            $data['user_id'] = $this->userId;
            $data['scopes'] = $this->createScopes($data);

            $app = $this->table->create($data)
                ->toArray();

            $tokens = $this->tokenService->generateToken($app, $request);

            $this->update($app['id'], $tokens);
        });
    }

    /**
     * return an app details by id for show page.
     *
     * @param int $id
     * @return array
     */
    public function show(int $id): array
    {
        $hideColumns = ['scopes'];

        return $this->table->getById($id, $this->userId)
            ->makeHidden($hideColumns)
            ->toArray();
    }

    /**
     * return an app details by id for edit page.
     *
     * @param int $id
     * @return array
     */
    public function edit(int $id): array
    {
        $services = array_column(Service::cases(), 'value');
        $channels = [
            'push' => $this->pushChannelService->getGroupByProvider(),
            'email' => $this->emailChannelService->getGroupByProvider()
        ];

        $hideColumns = ['scopes', 'service_display', 'channel_display', 'token', 'refresh_token'];

        $app = $this->table->getById($id, $this->userId)
            ->makeHidden($hideColumns)
            ->toArray();

        return [$services, $channels, $app];
    }

    /**
     * update an app.
     *
     * @param int $id
     * @param array $request
     * @return void
     */
    public function update(int $id, array $request): void
    {
        $app = $this->table->getById($id, $this->userId);

        if ($app->notifications()->exists()) {
            throw new IsUsedException('This app is used and cannot be updated.');
        }

        if (array_key_exists('channels', $request)) {
            $request['scopes'] = $this->createScopes($request);
        }

        $this->table->update($id, $request);
    }

    /**
     * create and format scopes.
     * For Feature: Think about new format for permissions.
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
