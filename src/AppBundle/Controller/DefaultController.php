<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends FOSRestController
{

    /**
    * @Rest\Get("/api")
    * @Rest\Get("/")
    */
    public function getDefault(Request $request)
    {
      $response = "IRA API";
      $view = $this->view($response);
      return $view;
    }

}
