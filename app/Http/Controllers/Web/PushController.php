<?php

namespace App\Http\Controllers\Web;

use App\Enums\Push;
use App\Enums\PushProvider;
use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use App\Http\Requests\PushRequest;
use App\Services\ChannelDBService;
use App\Services\ChannelService;
use Illuminate\Http\Request;

class PushController extends Controller
{
    private $channelDBService;
    private $channelService;
    private $flashMessage;

    public function __construct(ChannelDBService $channelDBService, ChannelService $channelService)
    {
        $this->channelDBService = $channelDBService;
        $this->channelService = $channelService;

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
    public function index()
    {
        return view('push.index');
    }

    /**
     * Return push notification channel's resource.
     *
     * @param DatatableRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(DatatableRequest $request)
    {
        $records = $this->channelDBService->list($request->toArray());

        return response()->json($records, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $pushProvider = PushProvider::getAll();

        return view('push.create', [
            'providers' => $pushProvider
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PushRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PushRequest $request)
    {
        try {
            $data = $request->except('_token');
            $this->channelDBService->create($data);

            $this->flashMessage['success']['message'] = 'Successfully created.';
            $message = $this->flashMessage['success'];
        } catch (\Throwable $th) {
            \Log::info($th->getMessage() . ' in ' . $th->getFile() . ' at ' . $th->getLine());

            $this->flashMessage['failed']['message'] = 'Something went wrong.';

            return redirect()->back()
                ->with('flashMessage', $this->flashMessage['failed']);
        }

        return redirect()->route('push.index')
            ->with('flashMessage', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return \Illuminate\View\View
     */
    public function show(string $id)
    {
        $channel = $this->channelDBService->getById($id);

        return view('push.show', [
            'channel' => $channel
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     * @return \Illuminate\View\View
     */
    public function edit(string $id)
    {
        $channel = $this->channelDBService->getById($id, true);

        return view('push.edit', [
            'channel' => $channel
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PushRequest $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PushRequest $request, string $id)
    {
        try {
            $data = $request->only(['name', 'credentials']);
            $this->channelDBService->update($id, $data);

            $this->flashMessage['success']['message'] = 'Successfully updated.';
            $message = $this->flashMessage['success'];
        } catch (\Throwable $th) {
            \Log::info($th->getMessage() . ' in ' . $th->getFile() . ' at ' . $th->getLine());

            $this->flashMessage['failed']['message'] = 'Something went wrong.';

            return redirect()->back()
                ->with('flashMessage', $this->flashMessage['failed']);
        }

        return redirect()->route('push.index')
            ->with('flashMessage', $message);
    }

    /**
     * The push notification page to test.
     *
     * @param string $id
     * @return \Illuminate\View\View
     */
    public function testPage(string $id)
    {
        $channel = $this->channelDBService->getByIdForTest($id);

        return view('push.providers.' . $channel['provider_name'], [
            'channel' => $channel
        ]);
    }

    /**
     * Send push notification to test.
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function test(Request $request, string $id)
    {
        if (! $request->has('message')) {
            return response()->json([
                'message' => 'No data provided.'
            ], 400);
        }

        try {
            $request->merge([
                'channelName' => 'pushNotificationTest',
                'eventName' => 'pushNotificationTest',
            ]);

            $channel = $this->channelDBService->getById($id);
            $this->channelService->sendPushNotification($request->toArray(), $channel);

            return response()->json([
                'message' => 'Push notification sent successfully.'
            ], 200);
        } catch (\Throwable $th) {
            \Log::info($th->getMessage() . ' in ' . $th->getFile() . ' at ' . $th->getLine());

            return response()->json([
                'message' => 'Something went wrong.'
            ], 500);
        }
    }
}
