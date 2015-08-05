<?php

namespace App\Http\Controllers;

use Auth;
use Request;
use Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Response as HttpResponse;

abstract class Controller extends BaseController
{
    public function __construct()
    {
    }

    public function currentUserId()
    {
        return Auth::user()->getAuthIdentifier();
    }

    protected function response($data = null, $code = HttpResponse::HTTP_OK)
    {
        return Response::make($data, $code);
    }

    protected function formatMessages($messages)
    {
        if ($messages === null) {
            return $messages;
        }
        return ['messages' => is_array($messages) ? $messages : [$messages]];
    }

    protected function noContent()
    {
        return $this->response(null, HttpResponse::HTTP_NO_CONTENT);
    }

    protected function notFound($message = null)
    {
        return $this->response($this->formatMessages($message), HttpResponse::HTTP_NOT_FOUND);
    }

    protected function forbidden($message = null)
    {
        return $this->response($this->formatMessages($message), HttpResponse::HTTP_FORBIDDEN);
    }

    protected function unprocessableEntity($message = null)
    {
        return $this->response($this->formatMessages($message), HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
    }
}
