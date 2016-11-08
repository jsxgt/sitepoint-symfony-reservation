<?php

namespace ReservationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ReservationBundle\Validator\Constraints as ReservationAssert;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation")
 * @ORM\Entity(repositoryClass="ReservationBundle\Repository\ReservationRepository")
 *
 * @ReservationAssert\SubmitCheck()
 */
class Reservation
{

    public $baggageTypes = [
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

    public $pricePerPassenger = 100;

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
     * @var array
     *
     * @ORM\Column(name="baggages", type="simple_array")
     */
    private $baggages;

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

    /**
     * Set baggages
     *
     * @param array $baggages
     *
     * @return Reservation
     */
    public function setBaggages($baggages)
    {
        $this->baggages = $baggages;

        return $this;
    }

    /**
     * Get baggages
     *
     * @return array
     */
    public function getBaggages()
    {
        return $this->baggages;
    }

    /**
     * Get price
     *
     * @return int
     */
    public function getPrice(){
        $price = count($this->passengers) * $this->pricePerPassenger;

        foreach($this->baggages as $baggage){
            $price = $price + $this->baggageTypes[$baggage]['price'];
        }

        if(count($this->passengers) > 0){
            $passenger = $this->passengers->get(0);
            $user = $passenger->getUser();
            foreach($this->flight->getUserSeats($user->getId()) as $seat){
                $price = $price + $seat['price'];
            }
        }

        return $price;
    }
}
