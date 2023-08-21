<?php

namespace ticketeradigital\bsale;

use Illuminate\Http\Client\Response;

class BsaleException extends \Exception
{
    public function __construct(Response $response)
    {
        $message = $response->json()['error'];
        parent::__construct($message);
    }

    public function __toString()
    {
        return __CLASS__.": {$this->message}\n";
    }
}
