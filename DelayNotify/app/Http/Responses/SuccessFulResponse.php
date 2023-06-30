<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;


class SuccessFulResponse implements Responsable
{
    public function __construct($message = 'موفق بود', $code = 200)
    {
        $this->message = $message;
        $this->code = $code;
    }

    public function status()
    {
        switch (strtolower($this->name)) {
            case 'teapot':
                return 418;
            default:
                return 200;
        }
    }

    public function toResponse($request, $code = 200)
    {
        return response()->json(['message' => $this->message], $this->code);
    }
}
