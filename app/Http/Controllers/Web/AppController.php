<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppRequest;
use App\Http\Requests\DatatableRequest;
use App\Services\AppDBService;

class AppController extends Controller
{
    public function __construct(
        private AppDBService $appDBService
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
        list($services, $channels) = $this->appDBService->create();

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
            $this->appDBService->store($data);

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
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show(int $id): \Illuminate\View\View
    {
        $app = $this->appDBService->show($id);

        return view('app.show', [
            'app' => $app,
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
        list($services, $channels, $app) = $this->appDBService->edit($id);

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
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AppRequest $request, int $id): \Illuminate\Http\RedirectResponse
    {
        try {
            $data = $request->except('_token', '_method');
            $this->appDBService->update($id, $data);

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
    public function delete(int $id)
    {
        return 'delete';
    }
}
