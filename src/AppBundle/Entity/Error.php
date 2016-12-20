<?php

namespace AppBundle\Entity;

class Error
{
    private $code;

    private $message;

    public function setCode($code) {
      $this->code = $code;
      return $this;
    }

    public function getCode() {
      return $this->code;
    }

    public function setMessage($message) {
      $this->message = $message;
      return $this;
    }

    public function getMessage() {
      return $this->message;
    }

    public function __construct($code, $message) {
      $this->setCode($code);
      $this->setMessage($message);
    }
}
