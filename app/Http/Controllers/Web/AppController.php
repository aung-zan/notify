<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppController extends Controller
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
        return 'index';
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

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        return 'delete';
    }
}
