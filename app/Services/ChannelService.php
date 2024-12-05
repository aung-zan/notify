<?php

namespace App\Services;

use App\Models\Push;
use App\Repositories\PushRepository;

class ChannelService
{
    private $pushRepository;

    public function __construct(PushRepository $pushRepository)
    {
        $this->pushRepository = $pushRepository;
    }

    public function list()
    {
        //
    }

    public function create(array $request): Push
    {
        $credentials = [];
        $rawCredentials = preg_split('/\r\n|\r|\n/', $request['credentials']);

        foreach ($rawCredentials as $value) {
            list($key, $value) = explode('=', $value);
            $key = trim(str_replace("'", "", $key));
            $value = trim(str_replace('"', "", $value));
            $credentials[$key] = $value;
        }

        $request['credentials'] = json_encode($credentials);
        $request['user_id'] = 1;

        return $this->pushRepository->create($request);
    }
}
