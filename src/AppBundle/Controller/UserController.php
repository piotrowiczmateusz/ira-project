<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use SensioLabs\Security\Exception\RuntimeException;
use AppBundle\Entity\User;
use AppBundle\Entity\Error;


/*
  Sample Data:

  {
    "name": "user",
    "surname": "resu",
    "email": "user@user.pl",
    "password": "123"
  }

*/

class UserController extends FOSRestController
{

  /**
  * @Rest\Get("/api/users")
  */
  public function getUsers(Request $request)
  {
     $em = $this->getDoctrine()->getManager();

     $query = "SELECT user.id, user.name, user.surname, user.email, user.password FROM AppBundle:User user";
     $response = $em->createQuery($query)->getResult();

     $view = $this->view($response);
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
      $response = $em->createQuery($query)->getResult();

      if(!$response) {
        $response = new Error("404", "User with id: ".$id." not found.");
      }

      $view = $this->view($response);
      return $view;
   }


   /**
   * @Rest\Post("/api/users")
   */
   public function postUsers(Request $request)
   {

      $em = $this->getDoctrine()->getManager();

      $body = $request->getContent();

      if (!empty($body)) $params = json_decode($body);

      if($params) {

        if(isset($params->name) &&
           isset($params->surname) &&
           isset($params->email) &&
           isset($params->password)) {

             $user = new User($params->name, $params->surname, $params->email, $params->password);

             $em->persist($user);
             $em->flush();

             $response = $user;

          } else {
            $response = new Error("400", "Missing parameters for user.");
          }

        } else {
          $response = new Error("400", "Invalid JSON syntax.");
        }

      $view = $this->view($response);
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

        $response = new Error("204", "User with id: ".$id." was deleted.");
      } else {
        $response = new Error("404", "User with id: ".$id." not found.");
      }

      $view = $this->view($response);
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

     if (!empty($body)) $params = json_decode($body);

     $user = $em->getRepository('AppBundle:User')->findOneById($id);

     if($params) {

       if($user) {
         if (isset($params->name)) $user->setName($params->name);
         if (isset($params->surname)) $user->setSurname($params->surname);
         if (isset($params->email)) $user->setEmail($params->email);
         if (isset($params->password)) $user->setPassword($params->password);

         $em->flush();

         $response = new Error("204", "User with id: ".$id."was updated.");
       } else {
         $response = new Error("404", "User with id: ".$id." not found.");
       }
     } else {
       $response = new Error("400", "Invalid JSON syntax.");
     }

     $view = $this->view($response);
     return $view;
  }

}
