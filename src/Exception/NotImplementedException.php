<?php

namespace DahuaCloud\Exception;

class NotImplementedException extends ApiException
{
    public function __construct() {
        parent::__construct('not implemented');
    }
}
