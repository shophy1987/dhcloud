<?php

namespace DahuaCloud\Exception;

class ArgumentException extends ApiException
{
    public function __construct($message, $arg) {
        parent::__construct("{$arg} $message");
    }
}
