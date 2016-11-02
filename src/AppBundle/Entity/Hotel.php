<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Room;

/**
 * @ORM\Entity
 * @ORM\Table(name="hotel")
 */
class Hotel
{
    /**
    * @ORM\Column(type="guid")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="UUID")
    */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $address;

    /**
     * @ORM\Column(type="integer")
     */
    private $stars;

    /**
     * @ORM\OneToMany(targetEntity="Room", mappedBy="hotel", cascade={"ALL"}, indexBy="hotel")
     */
    private $rooms;


    public function __construct($name, $city, $address, $stars) {
      $this->setName($name);
      $this->setCity($city);
      $this->setAddress($address);
      $this->setStars($stars);
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
     * Set name
     *
     * @param string $name
     *
     * @return Hotel
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Hotel
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Hotel
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set stars
     *
     * @param integer $stars
     *
     * @return Hotel
     */
    public function setStars($stars)
    {
        $this->stars = $stars;

        return $this;
    }

    /**
     * Get stars
     *
     * @return integer
     */
    public function getStars()
    {
        return $this->stars;
    }

    /**
     * Add room
     *
     * @param \AppBundle\Entity\Room $room
     *
     * @return Hotel
     */
    public function addRoom(\AppBundle\Entity\Room $room)
    {
        $this->rooms[] = $room;

        return $this;
    }

    /**
     * Remove room
     *
     * @param \AppBundle\Entity\Room $room
     */
    public function removeRoom(\AppBundle\Entity\Room $room)
    {
        $this->rooms->removeElement($room);
    }

    /**
     * Get rooms
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRooms()
    {
        return $this->rooms;
    }
}
