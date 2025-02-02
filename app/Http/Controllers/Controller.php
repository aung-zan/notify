<?php

namespace App\Http\Controllers;

use App\Exceptions\IsUsedException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        if ($th instanceof ModelNotFoundException) {
            $this->flashMessage['failed']['message'] = 'The requested resource does not exit.';
        } elseif ($th instanceof IsUsedException) {
            $this->flashMessage['failed']['message'] = $th->getMessage();
        } else {
            Log::info($th);
            $this->flashMessage['failed']['message'] = 'Something went wrong.';
        }
    }
}
