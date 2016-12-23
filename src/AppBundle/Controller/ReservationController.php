<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Reservation;
use AppBundle\Entity\Error;

/*
  Sample Data:

  {
    "user": "userID",
    "room": "roomID",
    "arrivalDate": "26-04-2017",
    "departureDate": "30-04-2017"
  }

*/

class ReservationController extends FOSRestController
{

   /**
   * @Rest\Get("/api/reservations")
   */
   public function getReservations(Request $request)
   {
       $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
       $response = new Response();
       $em = $this->getDoctrine()->getManager();

       $query = $em->createQuery("SELECT reservation FROM AppBundle:Reservation reservation");
       $content = $query->getResult();

       $response->setContent($serializer->serialize($content, 'json'));
       return $response;

   }

    /**
    * @Rest\Get("/api/reservations/{id}")
    */
   public function getReservationById(Request $request)
   {
       $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
       $response = new Response();
       $em = $this->getDoctrine()->getManager();

       $id = $request->get('id');

       $query = $em->createQuery("SELECT reservation FROM AppBundle:Reservation reservation WHERE reservation.id = '".$id."'");
       $content = $query->getResult();

       if(!$content) {
         $response->setStatusCode(404);
         $content = new Error("404", "Reservation with id: ".$id." not found.");
       }

       $response->setContent($serializer->serialize($content, 'json'));
       return $response;

   }

   /**
   * @Rest\Post("/api/reservations")
   */
   public function postReservations(Request $request)
   {

      $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
      $response = new Response();
      $em = $this->getDoctrine()->getManager();

      $body = $request->getContent();

      if (!empty($body)) $params = json_decode($body);

      if($params) {

        if(isset($params->user) &&
           isset($params->room) &&
           isset($params->arrivalDate) &&
           isset($params->departureDate)) {

            $reservation = new Reservation($params->user, $params->room, $params->arrivalDate, $params->departureDate);

            $em->persist($reservation);
            $em->flush();

            $content = $reservation;
          } else {
            $response->setStatusCode(400);
            $content = new Error("400", "Missing parameters for reservation.");
          }
        } else {
          $response->setStatusCode(400);
          $content = new Error("400", "Invalid JSON syntax.");
        }

        $response->setContent($serializer->serialize($content, 'json'));
        return $response;

   }

  /**
  * @Rest\Delete("/api/reservations/{id}")
  */
  public function deleteReservation(Request $request)
  {

      $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
      $response = new Response();
      $em = $this->getDoctrine()->getManager();

      $id = $request->get('id');

      $reservation = $em->getRepository('AppBundle:Reservation')->findOneById($id);

      if($reservation) {
        $em->remove($Reservation);
        $em->flush();

        $response->setStatusCode(204);
        $content = new Error("204", "Reservation with id: ".$id." was deleted.");
      } else {
        $response->setStatusCode(404);
        $content = new Error("404", "Reservation with id: ".$id." not found");
      }

      $response->setContent($serializer->serialize($content, 'json'));
      return $response;

  }

  /**
  * @Rest\Put("/api/reservations/{id}")
  */
  public function updateReservation(Request $request)
  {

     $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
     $response = new Response();
     $em = $this->getDoctrine()->getManager();

     $id = $request->get('id');

     $body = $request->getContent();

     if (!empty($body)) $params = json_decode($body);

     $reservation = $em->getRepository('AppBundle:Reservation')->findOneById($id);

     if($params) {

       if($reservation) {
         if (isset($params->user)) $reservation->setUser($params->user);
         if (isset($params->room)) $reservation->setRoom($params->room);
         if (isset($params->arrivalDate)) $reservation->setArrivalDate($params->arrivalDate);
         if (isset($params->departureDate)) $reservation->setDepartureDate($params->departureDate);

         $em->flush();

         $response->setStatusCode(204);
         $content = new Error("204", "Reservation with id: ".$id."was updated.");
       } else {
         $response->setStatusCode(404);
         $content = new Error("404", "Reservation with id: ".$id." not found");
       }
     } else {
       $response->setStatusCode(400);
       $content = new Error("400", "Invalid JSON syntax.");
     }

     $response->setContent($serializer->serialize($content, 'json'));
     return $response;

  }

}
