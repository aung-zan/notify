<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        //
    }

    public function list()
    {
        return response()->json([
            'message' => 'list'
        ], 200);
    }

    public function send(Request $request)
    {
        return response()->json([
            'message' => 'sent successfully.'
        ], 200);
    }
}
