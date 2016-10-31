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
}

