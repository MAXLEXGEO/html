<?php

/**
* Custom Exception to send response status
*/

class ExceptionApi extends Exception
{

    public $status;
    
    function __construct($status, $message, $code = 400)
    {
        $this->status  = $status;
        $this->message = $message;
        $this->code    = $code;
    }
}