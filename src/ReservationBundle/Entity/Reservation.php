<?php

namespace ReservationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation")
 * @ORM\Entity(repositoryClass="ReservationBundle\Repository\ReservationRepository")
 */
class Reservation
{

    public static $baggageTypes = [
        'small' => [
            'name' => 'Small cabin baggage',
            'price' => 0
        ],
        'medium' => [
            'name' => 'Medium cabin baggage',
            'price' => 15
        ],
        'large' => [
            'name' => 'Large baggage',
            'price' => 25
        ]
    ];

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Flight")
     *
     * @ORM\JoinColumn(name="flight_id", referencedColumnName="id")
     */
    private $flight;

    /**
     * @ORM\ManyToMany(targetEntity="Passenger")
     *
     * @ORM\JoinTable(name="reservations_passengers")
     */
    private $passengers;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->passengers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set flight
     *
     * @param \ReservationBundle\Entity\Flight $flight
     *
     * @return Reservation
     */
    public function setFlight(\ReservationBundle\Entity\Flight $flight = null)
    {
        $this->flight = $flight;

        return $this;
    }

    /**
     * Get flight
     *
     * @return \ReservationBundle\Entity\Flight
     */
    public function getFlight()
    {
        return $this->flight;
    }

    /**
     * Add passenger
     *
     * @param \ReservationBundle\Entity\Passenger $passenger
     *
     * @return Reservation
     */
    public function addPassenger(\ReservationBundle\Entity\Passenger $passenger)
    {
        $this->passengers[] = $passenger;

        return $this;
    }

    /**
     * Remove passenger
     *
     * @param \ReservationBundle\Entity\Passenger $passenger
     */
    public function removePassenger(\ReservationBundle\Entity\Passenger $passenger)
    {
        $this->passengers->removeElement($passenger);
    }

    /**
     * Get passengers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPassengers()
    {
        return $this->passengers;
    }
}
