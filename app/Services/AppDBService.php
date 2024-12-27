<?php

namespace App\Services;

use App\Models\App;
use App\Repositories\AppRepository;

class AppDBService
{
    private $appRepository;

    public function __construct(AppRepository $appRepository)
    {
        $this->appRepository = $appRepository;
    }

    /**
     * create an app.
     *
     * @param array $request
     * @return App
     */
    public function create(array $request): App
    {
        $request['user_id'] = 1;

        return $this->appRepository->create($request);
    }
}
