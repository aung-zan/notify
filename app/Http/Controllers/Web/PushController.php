<?php

namespace App\Http\Controllers\Web;

use App\Enums\Push;
use App\Http\Controllers\Controller;
use App\Models\Channel;
use Illuminate\Http\Request;

class PushController extends Controller
{
    public function __construct()
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('push.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pushServiceProviders = Push::getAll();

        return view('push.create', [
            'providers' => $pushServiceProviders
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $credentails = preg_split('/\r\n|\r|\n/', $request->get('credentials'));

        $records = Channel::all();

        \Log::info($records);

        return 'store';
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return 'show';
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return 'edit';
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return 'update';
    }
}
