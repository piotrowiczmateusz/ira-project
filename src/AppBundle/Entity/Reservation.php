<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;
use AppBundle\Entity\Room;

/**
 * @ORM\Entity
 * @ORM\Table(name="reservation")
 */
class Reservation
{
    /**
    * @ORM\Column(type="guid")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="UUID")
    */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="reservations")
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="Room")
     */
    private $room;

    /**
     * @ORM\Column(type="date")
     */
    private $reservationDate;

    /**
     * @ORM\Column(type="date")
     */
    private $arrivalDate;

    /**
     * @ORM\Column(type="date")
     */
    private $departureDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;


    public function __construct($user, $room, $arrivalDate, $departureDate, $price) {
      $this->setUser($user);
      $this->setRoom($room);
      $time = new \DateTime();
      $this->setReservationDate($time->format('H:i:s \O\n Y-m-d'));
      $this->setArrivalDate($arrivalDate);
      $this->setDepartureDate($departureDate);
      $this->setPrice($price);
    }

    /**
     * Get id
     *
     * @return guid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set reservationDate
     *
     * @param \DateTime $reservationDate
     *
     * @return Reservation
     */
    public function setReservationDate($reservationDate)
    {
        $this->reservationDate = $reservationDate;

        return $this;
    }

    /**
     * Get reservationDate
     *
     * @return \DateTime
     */
    public function getReservationDate()
    {
        return $this->reservationDate;
    }

    /**
     * Set arrivalDate
     *
     * @param \DateTime $arrivalDate
     *
     * @return Reservation
     */
    public function setArrivalDate($arrivalDate)
    {
        $this->arrivalDate = $arrivalDate;

        return $this;
    }

    /**
     * Get arrivalDate
     *
     * @return \DateTime
     */
    public function getArrivalDate()
    {
        return $this->arrivalDate;
    }

    /**
     * Set departureDate
     *
     * @param \DateTime $departureDate
     *
     * @return Reservation
     */
    public function setDepartureDate($departureDate)
    {
        $this->departureDate = $departureDate;

        return $this;
    }

    /**
     * Get departureDate
     *
     * @return \DateTime
     */
    public function getDepartureDate()
    {
        return $this->departureDate;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Reservation
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Reservation
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set room
     *
     * @param \AppBundle\Entity\Room $room
     *
     * @return Reservation
     */
    public function setRoom(\AppBundle\Entity\Room $room = null)
    {
        $this->room = $room;

        return $this;
    }

    /**
     * Get room
     *
     * @return \AppBundle\Entity\Room
     */
    public function getRoom()
    {
        return $this->room;
    }
}
