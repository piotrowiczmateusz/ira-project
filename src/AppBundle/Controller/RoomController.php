<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Room;

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
       $data = $em->createQuery($query)->getResult();

       $view = $this->view($data, Response::HTTP_INTERNAL_SERVER_ERROR);
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
      $room = $query->getResult();

      $view = $this->view($room, Response::HTTP_INTERNAL_SERVER_ERROR);
      return $view;
   }


   /**
   * @Rest\Post("/api/rooms")
   */
   public function postRooms(Request $request)
   {

      $em = $this->getDoctrine()->getManager();

      $body = $request->getContent();

      if (!empty($body)) { $params = json_decode($body, false); }

      $hotelID = $params->hotelID;
      $type = $params->type;
      $price = $params->price;

      $room = new Room($hotelID, $type, $price);

      $em = $this->getDoctrine()->getManager();
      $em->persist($room);
      $em->flush();

      $params = json_decode($body, true);

      $view = $this->view($params, Response::HTTP_INTERNAL_SERVER_ERROR);
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
      $rooms = $query->getResult();

      $view = $this->view($rooms, Response::HTTP_INTERNAL_SERVER_ERROR);
      return $view;
  }

  /**
  * @Rest\Put("/api/rooms/{id}")
  */
  public function updateUser(Request $request)
  {

     $em = $this->getDoctrine()->getManager();

     $id = $request->get('id');

     $body = $request->getContent();

     if (!empty($body)) { $params = json_decode($body, false); }

     $hotelID = $params->hotelID;
     $type = $params->type;
     $price = $params->price;

     $query = $em->createQuery("UPDATE AppBundle:Room room SET room.hotelID='".$hotelID."', room.type='".$type."', room.price='".$price."' WHERE room.id = '".$id."'");
     $rooms = $query->getResult();

     $view = $this->view($rooms, Response::HTTP_INTERNAL_SERVER_ERROR);
     return $view;
  }

}
