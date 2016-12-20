<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
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
     $em = $this->getDoctrine()->getManager();

     $query = "SELECT hotel.id, hotel.name, hotel.city, hotel.address, hotel.stars  FROM AppBundle:Hotel hotel";
     $response = $em->createQuery($query)->getResult();

     $view = $this->view($response);
     return $view;
 }

 /**
 * @Rest\Get("/api/hotels/{id}")
 */
 public function getHotelById(Request $request)
 {
    $id = $request->get('id');

    $em = $this->getDoctrine()->getManager();

    $query =  "SELECT h FROM AppBundle:Hotel h WHERE h.id = '".$id."'";
    $response = $em->createQuery($query)->getResult();

    if(!$response) {
      $response = new Error("404", "Hotel with id: ".$id." not found.");
    }

    $view = $this->view($response);
    return $view;
  }


   /**
   * @Rest\Post("/api/hotels")
   */
   public function postHotels(Request $request)
   {

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

             $response = $hotel;

        } else {
          $response = new Error("400", "Missing parameters for hotel.");
        }
      } else {
        $response = new Error("400", "Invalid JSON syntax.");
      }

      $view = $this->view($response);
      return $view;
   }

   /**
   * @Rest\Delete("/api/hotels/{id}")
   */
  public function deleteHotel(Request $request)
  {

      $em = $this->getDoctrine()->getManager();

      $id = $request->get('id');

      $hotel = $em->getRepository('AppBundle:Hotel')->findOneById($id);

      if($hotel) {
        $em->remove($hotel);
        $em->flush();

        $response = new Error("204", "Hotel with id: ".$id." was deleted.");
      } else {
        $response = new Error("404", "Hotel with id: ".$id." not found.");
      }

      $view = $this->view($response);
      return $view;
  }

  /**
  * @Rest\Put("/api/hotels/{id}")
  */
  public function updateHotel(Request $request)
  {

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

         $response = new Error("204", "hotel with id: ".$id."was updated.");

       } else {
         $response = new Error("404", "Hotel with id: ".$id." not found.");
       }
     } else {
       $response = new Error("400", "Invalid JSON syntax.");
     }

     $view = $this->view($response);
     return $view;
  }

}
