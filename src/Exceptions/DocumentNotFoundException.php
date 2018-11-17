<?php

namespace App\Exceptions;

class DocumentNotFoundException extends \Exception
{
    public function __construct($message="Le document recherché est introuvable", $code=0)
    {
        parent::__construct($message, $code);
    }

    public function __toString()
    {
        return $this->message;
    }

}