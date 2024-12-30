<?php

namespace App\Http\Controllers\Web;

use App\Enums\Service;
use App\Http\Controllers\Controller;
use App\Http\Requests\AppRequest;
use App\Http\Requests\DatatableRequest;
use App\Services\AppDBService;
use App\Services\ChannelDBService;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppController extends Controller
{
    private $channelDBService;
    private $appDBService;
    private $tokenService;
    protected $flashMessage;

    public function __construct(
        ChannelDBService $channelDBService,
        AppDBService $appDBService,
        TokenService $tokenService
    ) {
        $this->channelDBService = $channelDBService;
        $this->appDBService = $appDBService;
        $this->tokenService = $tokenService;

        $this->flashMessage = [
            'success' => [
                'color' => 'bg-success',
            ],
            'failed' => [
                'color' => 'bg-danger',
            ],
        ];
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
        $draw = $request['draw'];

        $data = [
            [
                'id' => 1,
                'name' => 'John Doe',
                'notification' => 'Email, Push',
                'channel' => 'SMTP, Pusher',
                'created_at' => '2021-01-01 00:00',
                'updated_at' => '2021-01-01 00:00',
            ],
            [
                'id' => 2,
                'name' => 'Jane Doe',
                'notification' => 'Push',
                'channel' => 'Push',
                'created_at' => '2021-01-01 00:00',
                'updated_at' => '2021-01-01 00:00',
            ]
        ];

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => 2,
            'recordsFiltered' => 2,
            'data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): \Illuminate\View\View
    {
        $services = Service::getAll();
        $providers = [
            // 'email' => EmailProvider::getAll(),
            'push' => $this->channelDBService->getByGroupProvider(),
        ];

        return view('app.create', [
            'services' => $services,
            'providers' => $providers,
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
        return view('app.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     * @return \Illuminate\View\View
     */
    public function edit(string $id): \Illuminate\View\View
    {
        return view('app.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return 'update';
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        return 'delete';
    }
}
