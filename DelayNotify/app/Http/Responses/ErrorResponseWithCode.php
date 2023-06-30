<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

/**
 * @OA\Schema()
 */
class ErrorResponseWithCode implements Responsable
{
    public function __construct($message = '', $code = -1, $status = 400)
    {
        $this->status = $status;
        $this->code = $code;
        $this->message = $message;
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

    public function toResponse($request)
    {
        return response()->json(
            [
                'errorMessage' => $this->message,
                'errorCode' => $this->code,
            ],
            $this->status
        );
    }
}
