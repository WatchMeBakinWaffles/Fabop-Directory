<?php

namespace App\Exception;

class DocumentNotFoundException extends \Exception
{
  // Redéfinissez l'exception ainsi le message n'est pas facultatif
  public function __construct($message = "MongoDB document not found. Filter could have given no result or the document searched might not exist.", $code = 0) {

    parent::__construct($message, $code);

  }

}