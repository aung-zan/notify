<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

abstract class Controller
{
    protected $flashMessage = [
        'success' => [
            'color' => 'bg-success',
        ],
        'failed' => [
            'color' => 'bg-danger',
        ],
    ];

    /**
     * Handle the exception throws <Logged the message and update the flash message>.
     *
     * @param \Throwable $th
     * @return void
     */
    protected function handleException(\Throwable $th): void
    {
        Log::info($th->getMessage() . ' in ' . $th->getFile() . ' at ' . $th->getLine());

        $this->flashMessage['failed']['message'] = 'Something went wrong.';
    }
}
