<?php

namespace App\Http\Controllers\Web;

use App\Enums\Push;
use App\Enums\PushProvider;
use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use App\Http\Requests\PushRequest;
use App\Models\Channel;
use App\Services\ChannelService;
use Illuminate\Http\Request;

class PushController extends Controller
{
    private $channelService;
    private $flashMessage;

    public function __construct(ChannelService $channelService)
    {
        $this->channelService = $channelService;
        $this->flashMessage = [
            'success' => [
                'color' => 'success',
            ],
            'failed' => [
                'color' => 'danger',
            ],
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('push.index');
    }

    /**
     * Return push notification channel's resource.
     */
    public function getData(DatatableRequest $request)
    {
        $records = $this->channelService->list($request->toArray());

        return response()->json($records, 200);
    }

    /**
     * Show the form for creating a new resource.
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
     */
    public function store(PushRequest $request)
    {
        try {
            $data = $request->except('_token');
            $this->channelService->create($data);

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
     */
    public function show(string $id)
    {
        $channel = $this->channelService->getById($id);

        return view('push.show', [
            'channel' => $channel
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $channel = $this->channelService->getById($id, true);

        return view('push.edit', [
            'channel' => $channel
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PushRequest $request, string $id)
    {
        try {
            $data = $request->except(['_token', '_method']);
            $this->channelService->update($id, $data);

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

    public function testPage(string $id)
    {
        return 'test page';
    }

    public function test(Request $request, string $id)
    {
        return 'test';
    }
}
