<?php

namespace AppBundle\Helper;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Error;

class CustomBasicAuthenticationEntryPoint implements AuthenticationEntryPointInterface {

    private $realmName;

    public function __construct($realmName) {
        $this->realmName = $realmName;
    }

    public function start(Request $request, AuthenticationException $authException = null) {
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $content = new Error("401", "Unauthorized access.");

        $response = new Response();
        $response->setStatusCode(401);
        $response->setContent($serializer->serialize($content, 'json'));

        return $response;
    }
}
