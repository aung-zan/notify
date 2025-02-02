<?php

namespace App\Http\Controllers\Web;

use App\Enums\Service;
use App\Http\Controllers\Controller;
use App\Http\Requests\AppRequest;
use App\Http\Requests\DatatableRequest;
use App\Services\AppDBService;
use App\Services\PushChannelService;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppController extends Controller
{
    private $pushChannelService;
    private $appDBService;
    private $tokenService;

    public function __construct(
        PushChannelService $pushChannelService,
        AppDBService $appDBService,
        TokenService $tokenService
    ) {
        $this->pushChannelService = $pushChannelService;
        $this->appDBService = $appDBService;
        $this->tokenService = $tokenService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        return view('app.index');
    }

    /**
     * Return app's resource.
     *
     * @param DatatableRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(DatatableRequest $request): \Illuminate\Http\JsonResponse
    {
        $result = $this->appDBService->list($request->toArray());

        return response()->json($result, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): \Illuminate\View\View
    {
        $services = array_column(Service::cases(), 'value');
        $channels = [
            //TODO: add email provider
            'push' => $this->pushChannelService->getByGroupProvider(),
        ];

        return view('app.create', [
            'services' => $services,
            'channels' => $channels,
        ]);
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param AppRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AppRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $data = $request->except('_token');

            DB::transaction(function () use ($data) {
                $app = $this->appDBService->create($data);
                $tokens = $this->tokenService->generateToken($app, $data);
                $this->appDBService->update($app['id'], $tokens);
            });

            $this->flashMessage['success']['message'] = 'Successfully created.';
        } catch (\Throwable $th) {
            $this->handleException($th);
            return redirect()->back()
                ->withInput()
                ->with('flashMessage', $this->flashMessage['failed']);
        }

        return redirect()->route('app.index')
            ->with('flashMessage', $this->flashMessage['success']);
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return \Illuminate\View\View
     */
    public function show(string $id): \Illuminate\View\View
    {
        $app = $this->appDBService->getById($id);

        return view('app.show', [
            'app' => $app,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     * @return \Illuminate\View\View
     */
    public function edit(string $id): \Illuminate\View\View
    {
        $services = array_column(Service::cases(), 'value');
        $channels = [
            //TODO: add email provider
            'push' => $this->pushChannelService->getByGroupProvider(),
        ];

        $app = $this->appDBService->getById($id, true);

        return view('app.edit', [
            'services' => $services,
            'channels' => $channels,
            'app' => $app,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AppRequest $request
     * @param string $id
     */
    public function update(AppRequest $request, string $id)
    {
        try {
            $data = $request->except('_token', '_method');
            $this->appDBService->update($id, $data, true);

            $this->flashMessage['success']['message'] = 'Successfully updated.';
        } catch (\Throwable $th) {
            $this->handleException($th);
            return redirect()->back()
                ->withInput()
                ->with('flashMessage', $this->flashMessage['failed']);
        }

        return redirect()->route('app.index')
            ->with('flashMessage', $this->flashMessage['success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        return 'delete';
    }
}
