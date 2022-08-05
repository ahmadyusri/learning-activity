<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public static function saveLogErrors(\Illuminate\Http\Request $request, $path, $errorMessage)
    {
        $headers    = $request->headers;
        $body       = $request->all();

        if (isset($body['password'])) {
            $password = $body['password'];

            $password = str_repeat("*", strlen((string) $password));

            $body['password'] = $password;
        }

        if (isset($body['_token'])) {
            unset($body['_token']);
        }

        $request = [
            'url'       => @$request->url(),
            'headers'   => $headers,
            'body'      => $body
        ];

        Log::channel('daily_error_500')->error($path, [
            'title'     => $errorMessage,
            'request'   => $request
        ]);
    }
}
