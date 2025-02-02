<?php

namespace App\Http\Controllers\Web;

use App\Enums\PushProviders;
use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use App\Http\Requests\PushRequest;
use App\Services\PushChannelService;
use App\Services\PushService;
use Illuminate\Http\Request;

class PushController extends Controller
{
    private $pushChannelService;
    private $pushService;

    public function __construct(PushChannelService $pushChannelService, PushService $pushService)
    {
        $this->pushChannelService = $pushChannelService;
        $this->pushService = $pushService;
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
        $records = $this->pushChannelService->list($request->toArray());

        return response()->json($records, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $pushProviders = PushProviders::getAll();

        return view('push.create', [
            'providers' => $pushProviders
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
            $this->pushChannelService->create($data);

            $this->flashMessage['success']['message'] = 'Successfully created.';
            $message = $this->flashMessage['success'];
        } catch (\Throwable $th) {
            $this->handleException($th);

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
        $channel = $this->pushChannelService->getById($id);

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
        $channel = $this->pushChannelService->getById($id, true);

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
            $this->pushChannelService->update($id, $data);

            $this->flashMessage['success']['message'] = 'Successfully updated.';
            $message = $this->flashMessage['success'];
        } catch (\Throwable $th) {
            $this->handleException($th);

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
        $channel = $this->pushChannelService->getByIdForTest($id);

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

            $channel = $this->pushChannelService->getById($id);
            $this->pushService->sendPushNotification($request->toArray(), $channel);

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
