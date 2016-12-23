<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
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
       $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
       $response = new Response();
       $em = $this->getDoctrine()->getManager();

       $id = $request->get('hotelId');

       $query = "SELECT room.id, room.type, room.price FROM AppBundle:Room room WHERE room.hotel ='".$id."'";
       $content = $em->createQuery($query)->getResult();

       $response->setContent($serializer->serialize($content, 'json'));
       return $response;
   }

   /**
   * @Rest\Get("/api/hotels/{hotelId}/rooms/{id}")
   */
   public function getRoomById(Request $request)
   {
      $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
      $response = new Response();
      $em = $this->getDoctrine()->getManager();

      $hotelId = $request->get('hotelId');
      $id = $request->get('id');

      $query = $em->createQuery("SELECT room FROM AppBundle:Room room WHERE room.hotel = '".$hotelId."' AND room.id = '".$id."'");
      $content = $query->getResult();

      if(!$content) {
        $response->setStatusCode(404);
        $content = new Error("404", "Room with id: ".$id." not found.");
      }

      $response->setContent($serializer->serialize($content, 'json'));
      return $response;
   }


   /**
   * @Rest\Post("/api/rooms")
   */
   public function postRooms(Request $request)
   {
      $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
      $response = new Response();
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

            $content = $room;

        } else {
          $response->setStatusCode(400);
          $content = new Error("400", "Missing parameters for room.");
        }

      } else {
        $response->setStatusCode(400);
        $content = new Error("400", "Invalid JSON syntax.");
      }

      $response->setContent($serializer->serialize($content, 'json'));
      return $response;
   }

   /**
   * @Rest\Delete("/api/rooms/{id}")
   */
  public function deleteRoom(Request $request)
  {
      $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
      $response = new Response();
      $em = $this->getDoctrine()->getManager();

      $id = $request->get('id');

      $query = $em->createQuery("DELETE FROM AppBundle:Room room WHERE room.id = '".$id."'");
      $room = $query->getResult();

      if($room) {
        $em->remove($room);
        $em->flush();

        $response->setStatusCode(204);
        $content = new Error("204", "Room with id: ".$id." was deleted.");
      } else {
        $response->setStatusCode(404);
        $content = new Error("404", "Room with id: ".$id." not found.");
      }

      $response->setContent($serializer->serialize($content, 'json'));
      return $response;
  }

  /**
  * @Rest\Put("/api/rooms/{id}")
  */
  public function updateRoom(Request $request)
  {
     $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
     $response = new Response();
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

         $response->setStatusCode(204);
         $content = new Error("204", "Room with id: ".$id."was updated.");
       } else {
         $response->setStatusCode(404);
         $content = new Error("404", "Room with id: ".$id." not found.");
       }
     } else {
       $response->setStatusCode(400);
       $content = new Error("400", "Invalid JSON syntax.");
     }

     $response->setContent($serializer->serialize($content, 'json'));
     return $response;
  }

}
