<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function __construct()
    {
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
        return view('app.create');
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        return redirect()->route('app.index');
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
