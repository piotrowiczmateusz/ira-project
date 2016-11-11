<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Hotel;

class HotelController extends FOSRestController
{

  /**
  * @Rest\Get("/api/hotels")
  */
 public function getHotels(Request $request)
 {
     $em = $this->getDoctrine()->getManager();

     $query = "SELECT hotel.id, hotel.name, hotel.city, hotel.address, hotel.stars  FROM AppBundle:Hotel hotel";
     $data = $em->createQuery($query)->getResult();

     $view = $this->view($data, Response::HTTP_INTERNAL_SERVER_ERROR);
     return $view;
 }

 /**
 * @Rest\Get("/api/hotels/{id}")
 */
 public function getHotelById(Request $request)
 {
    $id = $request->get('id');

    $em = $this->getDoctrine()->getManager();

    $query =  "SELECT h FROM AppBundle:Hotel h INNER JOIN AppBundle:Room r WITH h.id = r.hotel WHERE h.id = '".$id."'";
    $data = $em->createQuery($query)->getResult();

    if(!$data) {
      $data = "Error 404. Hotel with id: ".$id." not found.";
    }

    $view = $this->view($data, Response::HTTP_INTERNAL_SERVER_ERROR);
    return $view;
  }


   /**
   * @Rest\Post("/api/hotels")
   */
   public function postHotels(Request $request)
   {

      $em = $this->getDoctrine()->getManager();

      $body = $request->getContent();

      if (!empty($body)) { $params = json_decode($body, false); }

      $hotel = new Hotel($params->name, $params->city, $params->address, $params->stars);

      $em->persist($hotel);
      $em->flush();

      $data = "201. Created new hotel.";

      $view = $this->view($data, Response::HTTP_INTERNAL_SERVER_ERROR);
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

        $data = "204. Hotel with id: ".$id." was deleted.";
      } else {
        $data = "Error 404. Hotel with id: ".$id." not found.";
      }

      $view = $this->view($data, Response::HTTP_INTERNAL_SERVER_ERROR);
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

     if (!empty($body)) { $params = json_decode($body, false); }

     $hotel = $em->getRepository('AppBundle:Hotel')->findOneById($id);

     if($hotel) {
       $hotel->setName($params->name);
       $hotel->setCity($params->city);
       $hotel->setAddress($params->address);
       $hotel->setStars($params->stars);

       $em->flush();

       $data = "204. Update hotel with id: ".$id.".";
     } else {
       $data = "Error 404. Hotel with id: ".$id." not found.";
     }

     $view = $this->view($data, Response::HTTP_INTERNAL_SERVER_ERROR);
     return $view;
  }

}
