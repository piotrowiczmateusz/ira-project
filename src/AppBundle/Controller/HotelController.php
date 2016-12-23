<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Hotel;
use AppBundle\Entity\Error;

/*
  Sample Data:

  {
    "name": "hotel",
    "city": "cityName",
    "address": "street, City",
    "stars": 5
  }

*/

class HotelController extends FOSRestController
{

  /**
  * @Rest\Get("/api/hotels")
  */
 public function getHotels(Request $request)
 {
     $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
     $response = new Response();
     $em = $this->getDoctrine()->getManager();

     $query = "SELECT hotel.id, hotel.name, hotel.city, hotel.address, hotel.stars  FROM AppBundle:Hotel hotel";
     $content = $em->createQuery($query)->getResult();

     $response->setContent($serializer->serialize($content, 'json'));
     return $response;

 }

 /**
 * @Rest\Get("/api/hotels/{id}")
 */
 public function getHotelById(Request $request)
 {
    $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
    $response = new Response();
    $em = $this->getDoctrine()->getManager();

    $id = $request->get('id');

    $query =  "SELECT h FROM AppBundle:Hotel h WHERE h.id = '".$id."'";
    $content = $em->createQuery($query)->getResult();

    if(!$content) {
      $response->setStatusCode(404);
      $content = new Error("404", "Hotel with id: ".$id." not found.");
    }

    $response->setContent($serializer->serialize($content, 'json'));
    return $response;


  }


   /**
   * @Rest\Post("/api/hotels")
   */
   public function postHotels(Request $request)
   {
      $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
      $response = new Response();
      $em = $this->getDoctrine()->getManager();

      $body = $request->getContent();

      if (!empty($body)) $params = json_decode($body);

      if($params) {

        if(isset($params->name) &&
           isset($params->city) &&
           isset($params->address) &&
           isset($params->stars)) {

             $hotel = new Hotel($params->name, $params->city, $params->address, $params->stars);

             $em->persist($hotel);
             $em->flush();

             $content = $hotel;

        } else {
          $response->setStatusCode(400);
          $content = new Error("400", "Missing parameters for hotel.");
        }
      } else {
        $response->setStatusCode(400);
        $content = new Error("400", "Invalid JSON syntax.");
      }

      $response->setContent($serializer->serialize($content, 'json'));
      return $response;


   }

   /**
   * @Rest\Delete("/api/hotels/{id}")
   */
  public function deleteHotel(Request $request)
  {
      $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
      $response = new Response();
      $em = $this->getDoctrine()->getManager();

      $id = $request->get('id');

      $hotel = $em->getRepository('AppBundle:Hotel')->findOneById($id);

      if($hotel) {
        $em->remove($hotel);
        $em->flush();

        $response->setStatusCode(204);
        $content = new Error("204", "Hotel with id: ".$id." was deleted.");
      } else {
        $response->setStatusCode(404);
        $content = new Error("404", "Hotel with id: ".$id." not found.");
      }

      $response->setContent($serializer->serialize($content, 'json'));
      return $response;

  }

  /**
  * @Rest\Put("/api/hotels/{id}")
  */
  public function updateHotel(Request $request)
  {
     $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
     $response = new Response();
     $em = $this->getDoctrine()->getManager();

     $id = $request->get('id');

     $body = $request->getContent();

     if (!empty($body)) $params = json_decode($body);

     $hotel = $em->getRepository('AppBundle:Hotel')->findOneById($id);

     if($params) {

       if($hotel) {

         if (isset($params->name)) $hotel->setName($params->name);
         if (isset($params->city)) $hotel->setCity($params->city);
         if (isset($params->address)) $hotel->setAddress($params->address);
         if (isset($params->stars)) $hotel->setStars($params->stars);

         $em->flush();

         $response->setStatusCode(204);
         $content = new Error("204", "hotel with id: ".$id."was updated.");

       } else {
         $response->setStatusCode(404);
         $content = new Error("404", "Hotel with id: ".$id." not found.");
       }
     } else {
       $response->setStatusCode(400);
       $content = new Error("400", "Invalid JSON syntax.");
     }

     $response->setContent($serializer->serialize($content, 'json'));
     return $response;

  }

}
