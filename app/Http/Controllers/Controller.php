<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

abstract class Controller
{
    protected function handleException(\Throwable $th): void
    {
        Log::info($th->getMessage() . ' in ' . $th->getFile() . ' at ' . $th->getLine());

        $this->flashMessage['failed']['message'] = 'Something went wrong.';
    }
}
