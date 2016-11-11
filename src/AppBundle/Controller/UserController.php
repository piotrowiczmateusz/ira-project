<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\User;

class UserController extends FOSRestController
{

  /**
  * @Rest\Get("/api/users")
  */
  public function getUsers(Request $request)
  {
     $em = $this->getDoctrine()->getManager();

     $query = "SELECT user.id, user.name, user.surname, user.email, user.password FROM AppBundle:User user";
     $data = $em->createQuery($query)->getResult();

     $view = $this->view($data, Response::HTTP_INTERNAL_SERVER_ERROR);
     return $view;
   }

   /**
   * @Rest\Get("/api/users/{id}")
   */
   public function getUserById(Request $request)
   {
      $id = $request->get('id');

      $em = $this->getDoctrine()->getManager();

      $query = "SELECT user FROM AppBundle:User user WHERE user.id = '".$id."'";
      $data = $em->createQuery($query)->getResult();

      if(!$data) {
        $data = "Error 404. User with id: ".$id." not found.";
      }

      $view = $this->view($data, Response::HTTP_INTERNAL_SERVER_ERROR);
      return $view;
   }


   /**
   * @Rest\Post("/api/users")
   */
   public function postUsers(Request $request)
   {

      $em = $this->getDoctrine()->getManager();

      $body = $request->getContent();

      if (!empty($body)) { $params = json_decode($body, false); }

      $user = new User($params->name, $params->surname, $params->email, $params->password);

      $em->persist($user);
      $em->flush();

      $data = "201 Created new user.";

      $view = $this->view($data, Response::HTTP_INTERNAL_SERVER_ERROR);
      return $view;
   }

   /**
   * @Rest\Delete("/api/users/{id}")
   */
  public function deleteUser(Request $request)
  {
      $em = $this->getDoctrine()->getManager();

      $id = $request->get('id');

      $user = $em->getRepository('AppBundle:User')->findOneById($id);

      if($user) {
        $em->remove($user);
        $em->flush();

        $data = "204. User with id: ".$id." was deleted.";
      } else {
        $data = "Error 404. User with id: ".$id." not found.";
      }

      $view = $this->view($data, Response::HTTP_INTERNAL_SERVER_ERROR);
      return $view;
  }

  /**
  * @Rest\Put("/api/users/{id}")
  */
  public function updateUser(Request $request)
  {

     $em = $this->getDoctrine()->getManager();

     $id = $request->get('id');

     $body = $request->getContent();

     if (!empty($body)) { $params = json_decode($body, false); }

     $user = $em->getRepository('AppBundle:User')->findOneById($id);

     if($user) {
       $user->setName($params->name);
       $user->setSurname($params->surname);
       $user->setEmail($params->email);
       $user->setPassword($params->password);

       $em->flush();

       $data = "204. Update user with id: ".$id.".";
     } else {
       $data = "Error 404. User with id: ".$id." not found.";
     }

     $view = $this->view($data, Response::HTTP_INTERNAL_SERVER_ERROR);
     return $view;
  }

}
