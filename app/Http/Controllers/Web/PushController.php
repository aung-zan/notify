<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use App\Http\Requests\PushNotiRequest;
use App\Http\Requests\PushRequest;
use App\Services\PushChannelService;
use App\Services\PushService;

class PushController extends Controller
{
    public function __construct(
        private PushChannelService $pushChannelService,
        private PushService $pushService
    ) {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        return view('push.index');
    }

    /**
     * Return push notification channel's resource.
     *
     * @param DatatableRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(DatatableRequest $request): \Illuminate\Http\JsonResponse
    {
        $records = $this->pushChannelService->list($request->toArray());

        return response()->json($records, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): \Illuminate\View\View
    {
        $pushProviders = $this->pushChannelService->create();

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
    public function store(PushRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $data = $request->except('_token');
            $this->pushChannelService->store($data);

            $this->flashMessage['success']['message'] = 'Successfully created.';
        } catch (\Throwable $th) {
            $this->handleException($th);

            return redirect()->back()
                ->with('flashMessage', $this->flashMessage['failed']);
        }

        return redirect()->route('push.index')
            ->with('flashMessage', $this->flashMessage['success']);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show(int $id): \Illuminate\View\View
    {
        $channel = $this->pushChannelService->show($id);

        return view('push.show', [
            'channel' => $channel
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit(int $id): \Illuminate\View\View
    {
        $channel = $this->pushChannelService->edit($id);

        return view('push.edit', [
            'channel' => $channel
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PushRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PushRequest $request, int $id): \Illuminate\Http\RedirectResponse
    {
        try {
            $data = $request->only(['name', 'credentials']);
            $this->pushChannelService->update($id, $data);

            $this->flashMessage['success']['message'] = 'Successfully updated.';
        } catch (\Throwable $th) {
            $this->handleException($th);

            return redirect()->back()
                ->with('flashMessage', $this->flashMessage['failed']);
        }

        return redirect()->route('push.index')
            ->with('flashMessage', $this->flashMessage['success']);
    }

    /**
     * The push notification page to test.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function testPage(int $id): \Illuminate\View\View
    {
        $channel = $this->pushChannelService->testPage($id);

        return view('push.providers.' . $channel['provider_name'], [
            'channel' => $channel
        ]);
    }

    /**
     * Send push notification to test.
     *
     * @param PushNotiRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function test(PushNotiRequest $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $channel = $this->pushChannelService->testPush($id);
            $this->pushService->sendPushNotification($request->toArray(), $channel);
        } catch (\Throwable $th) {
            $this->handleException($th);

            return response()->json([
                'message' => 'Something went wrong.'
            ], 500);
        }

        return response()->json([
            'message' => 'Push notification sent successfully.'
        ], 200);
    }
}
