<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Room;
use AppBundle\Entity\Error;

/*
  Sample Data:

  {
    "hotelID": "hotelID",
    "type": "single",
    "price": 100
  }

*/

class RoomController extends FOSRestController
{

   /**
   * @Rest\Get("/api/hotels/{hotelId}/rooms")
   */
   public function getRooms(Request $request)
   {
       $id = $request->get('hotelId');
       $em = $this->getDoctrine()->getManager();

       $query = "SELECT room.id, room.type, room.price FROM AppBundle:Room room WHERE room.hotel ='".$id."'";
       $response = $em->createQuery($query)->getResult();

       $view = $this->view($response);
       return $view;
   }

   /**
   * @Rest\Get("/api/hotels/{hotelId}/rooms/{id}")
   */
   public function getRoomById(Request $request)
   {
      $hotelId = $request->get('hotelId');
      $id = $request->get('id');

      $em = $this->getDoctrine()->getManager();

      $query = $em->createQuery("SELECT room FROM AppBundle:Room room WHERE room.hotel = '".$hotelId."' AND room.id = '".$id."'");
      $response = $query->getResult();

      if(!$response) {
        $response = new Error("404", "Room with id: ".$id." not found.");
      }

      $view = $this->view($response);
      return $view;
   }


   /**
   * @Rest\Post("/api/rooms")
   */
   public function postRooms(Request $request)
   {

      $em = $this->getDoctrine()->getManager();

      $body = $request->getContent();

      if (!empty($body)) $params = json_decode($body);

      if($params) {

        if(isset($params->hotelID) &&
           isset($params->type) &&
           isset($params->price)) {

            $room = new Room($params->hotelID, $params->type, $params->price);

            $em = $this->getDoctrine()->getManager();
            $em->persist($room);
            $em->flush();

            $response = $room;

        } else {
          $response = new Error("400", "Missing parameters for room.");
        }

      } else {
        $response = new Error("400", "Invalid JSON syntax.");
      }

      $view = $this->view($response);
      return $view;
   }

   /**
   * @Rest\Delete("/api/rooms/{id}")
   */
  public function deleteRoom(Request $request)
  {

      $em = $this->getDoctrine()->getManager();

      $id = $request->get('id');

      $query = $em->createQuery("DELETE FROM AppBundle:Room room WHERE room.id = '".$id."'");
      $room = $query->getResult();

      if($room) {
        $em->remove($room);
        $em->flush();

        $response = new Error("204", "Room with id: ".$id." was deleted.");
      } else {
        $response = new Error("404", "Room with id: ".$id." not found.");
      }

      $view = $this->view($response);
      return $view;
  }

  /**
  * @Rest\Put("/api/rooms/{id}")
  */
  public function updateRoom(Request $request)
  {

     $em = $this->getDoctrine()->getManager();

     $id = $request->get('id');

     $body = $request->getContent();

     if (!empty($body)) $params = json_decode($body);

     $room = $em->getRepository('AppBundle:Room')->findOneById($id);

     if($params) {

       if($room) {
         if (isset($params->hotelID)) $room->setHotelID($params->hotelID);
         if (isset($params->type)) $room->setType($params->type);
         if (isset($params->price)) $room->setPrice($params->price);

         $em->flush();

         $response = new Error("204", "Room with id: ".$id."was updated.");
       } else {
         $response = new Error("404", "Room with id: ".$id." not found.");
       }
     } else {
       $response = new Error("400", "Invalid JSON syntax.");
     }

     $view = $this->view($response);
     return $view;
  }

}
