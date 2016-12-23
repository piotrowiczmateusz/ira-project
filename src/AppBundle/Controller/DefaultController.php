<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
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
      $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
      $response = new Response();
      
      $content = "IRA API";
      $response->setContent($serializer->serialize($content, 'json'));
      return $response;
    }

}
