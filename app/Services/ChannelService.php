<?php

namespace App\Services;

use App\Models\Push;
use App\Repositories\PushRepository;

use function PHPSTORM_META\map;

class ChannelService
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
        $records = $filteredRecords->slice($request['start'], $request['length'])
            ->values();

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
     * @return Push
     */
    public function create(array $request): Push
    {
        $request['credentials'] = $this->formatCredentials($request['credentials']);
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
     */
    public function update(int $id, array $request)
    {
        if (array_key_exists('credentials', $request)) {
            $request['credentials'] = $this->formatCredentials($request['credentials']);
        }

        return $this->pushRepository->update($id, $request);
    }

    /**
     * format the channel's credentails.
     *
     * @param string $credentails
     * @return string
     */
    private function formatCredentials(string $credentials): string
    {
        $credentialsArr = [];
        $rawCredentials = preg_split('/\r\n|\r|\n/', $credentials);
        $rawCredentials = str_replace(['"', "'"], '', $rawCredentials);

        foreach ($rawCredentials as $string) {
            list($key, $value) = explode('=', $string);
            $credentialsArr[trim($key)] = trim($value);
        }

        return json_encode($credentialsArr);
    }
}
