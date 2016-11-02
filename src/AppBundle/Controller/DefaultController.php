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
    */
    public function getDefault(Request $request)
    {
      $data = "IRA API";
      $view = $this->view($data, Response::HTTP_INTERNAL_SERVER_ERROR);
      return $view;
    }

}
