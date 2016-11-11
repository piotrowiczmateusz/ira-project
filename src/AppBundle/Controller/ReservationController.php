<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Reservation;

class ReservationController extends FOSRestController
{

   /**
   * @Rest\Get("/api/reservations")
   */
   public function getReservations(Request $request)
   {
       $em = $this->getDoctrine()->getManager();

       $query = $em->createQuery("SELECT reservation FROM AppBundle:Reservation reservation");
       $reservations = $query->getResult();

       $view = $this->view($reservations, Response::HTTP_INTERNAL_SERVER_ERROR);
       return $view;
   }

    /**
    * @Rest\Get("/api/reservations/{id}")
    */
   public function getReservationById(Request $request)
   {
       $id = $request->get('id');

       $em = $this->getDoctrine()->getManager();

       $query = $em->createQuery("SELECT reservation FROM AppBundle:Reservation reservation WHERE reservation.id = '".$id."'");
       $data = $query->getResult();

       if(!$data) {
         $data = "Error 404. Reservation with id: ".$id." not found.";
       }

       $view = $this->view($data, Response::HTTP_INTERNAL_SERVER_ERROR);
       return $view;
   }

   /**
   * @Rest\Post("/api/reservations")
   */
   public function postReservations(Request $request)
   {

      $em = $this->getDoctrine()->getManager();

      $body = $request->getContent();

      if (!empty($body)) { $params = json_decode($body, false); }

      $reservation = new Reservation($params->user, $params->room, $params->arrivalDate, $params->departureDate);

      $em->persist($reservation);
      $em->flush();

      $data = "201. Created new reservation.";

      $view = $this->view($data, Response::HTTP_INTERNAL_SERVER_ERROR);
      return $view;
   }

  /**
  * @Rest\Delete("/api/reservations/{id}")
  */
  public function deleteReservation(Request $request)
  {
      $em = $this->getDoctrine()->getManager();

      $id = $request->get('id');

      $reservation = $em->getRepository('AppBundle:Reservation')->findOneById($id);

      if($reservation) {
        $em->remove($Reservation);
        $em->flush();

        $data = "204. Reservation with id: ".$id." was deleted.";
      } else {
        $data = "Error 404. Reservation with id: ".$id." not found";
      }

      $view = $this->view($data, Response::HTTP_INTERNAL_SERVER_ERROR);
      return $view;
  }

  /**
  * @Rest\Put("/api/reservations/{id}")
  */
  public function updateReservation(Request $request)
  {

     $em = $this->getDoctrine()->getManager();

     $id = $request->get('id');

     $body = $request->getContent();

     if (!empty($body)) { $params = json_decode($body, false); }

     $reservation = $em->getRepository('AppBundle:Reservation')->findOneById($id);

     if($reservation) {
       $reservation->setUser($params->user);
       $reservation->setRoom($params->room);
       $reservation->setArrivalDate($params->arrivalDate);
       $reservation->setDepartureDate($params->departureDate);

       $em->flush();

       $data = "204. Update reservation with id: ".$id.".";
     } else {
       $data = "Error 404. Reservation with id: ".$id." not found";
     }

     $view = $this->view($data, Response::HTTP_INTERNAL_SERVER_ERROR);
     return $view;
  }

}
