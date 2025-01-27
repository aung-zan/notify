<?php

namespace App\Http\Controllers\Web;

use App\Enums\EmailProviders;
use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use App\Http\Requests\EmailRequest;
use App\Services\EmailChannelService;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    private $emailChannelService;

    public function __construct(EmailChannelService $emailChannelService)
    {
        $this->emailChannelService = $emailChannelService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        return view('email.index');
    }

    /**
     * Return push notification channel's resource.
     *
     * @param DatatableRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(DatatableRequest $request): \Illuminate\Http\JsonResponse
    {
        $records = $this->emailChannelService->list($request->toArray());

        return response()->json($records, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): \Illuminate\View\View
    {
        $emailProviders = EmailProviders::getAll();

        return view('email.create', [
            'providers' => $emailProviders
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EmailRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(EmailRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $data = $request->except('_token');
            $this->emailChannelService->create($data);

            $this->flashMessage['success']['message'] = 'Successfully created.';
        } catch (\Throwable $th) {
            $this->handleException($th);

            return redirect()->back()
                ->with('flashMessage', $this->flashMessage['failed']);
        }

        return redirect()->route('email.index')
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
        $emailChannel = $this->emailChannelService->getById($id);

        return view('email.show', [
            'channel' => $emailChannel
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
        $emailChannel = $this->emailChannelService->getById($id);

        return view('email.edit', [
            'channel' => $emailChannel
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EmailRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(EmailRequest $request, int $id): \Illuminate\Http\RedirectResponse
    {
        try {
            $data = $request->only(['name', 'credentials']);
            $this->emailChannelService->update($id, $data);

            $this->flashMessage['success']['message'] = 'Successfully updated.';
        } catch (\Throwable $th) {
            $this->handleException($th);

            return redirect()->back()
                ->with('flashMessage', $this->flashMessage['failed']);
        }

        return redirect()->route('email.index')
            ->with('flashMessage', $this->flashMessage['success']);
    }
}
