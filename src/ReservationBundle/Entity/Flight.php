<?php

namespace ReservationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Flight
 *
 * @ORM\Table(name="flight")
 * @ORM\Entity(repositoryClass="ReservationBundle\Repository\FlightRepository")
 */
class Flight
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="number", type="string", length=10, unique=true)
     */
    private $number;

    /**
     * @var array
     *
     * @ORM\Column(name="seats", type="json_array")
     */
    private $seats;

    /**
     * @var string
     *
     * @ORM\Column(name="takeoff", type="string", length=255)
     */
    private $takeoff;

    /**
     * @var string
     *
     * @ORM\Column(name="landing", type="string", length=255)
     */
    private $landing;

    /**
     * Flight constructor.
     */
    public function __construct()
    {
        if(is_null($this->seats)){
            $this->generateSeats();
        }
    }

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
     * Set number
     *
     * @param string $number
     *
     * @return Flight
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set seats
     *
     * @param array $seats
     *
     * @return Flight
     */
    public function setSeats($seats)
    {
        $this->seats = $seats;

        return $this;
    }

    /**
     * Get seats
     *
     * @return array
     */
    public function getSeats()
    {
        return $this->seats;
    }

    /**
     * Set takeoff
     *
     * @param string $takeoff
     *
     * @return Flight
     */
    public function setTakeoff($takeoff)
    {
        $this->takeoff = $takeoff;

        return $this;
    }

    /**
     * Get takeoff
     *
     * @return string
     */
    public function getTakeoff()
    {
        return $this->takeoff;
    }

    /**
     * Set landing
     *
     * @param string $landing
     *
     * @return Flight
     */
    public function setLanding($landing)
    {
        $this->landing = $landing;

        return $this;
    }

    /**
     * Get landing
     *
     * @return string
     */
    public function getLanding()
    {
        return $this->landing;
    }

    private function generateSeats($options = null){
        if(is_null($options)){
            $options = [
                'rows' => 30,
                'seats_per_row' => 6,
                'legroom_rows' => [12, 13],
                'legroom_price' => 15,
                'window_price' => 10,
                'front_price' => 25
            ];
        }

        $seats = array();

        for($i = 1; $i <= $options['rows']; $i++){

            for($j = 0; $j < $options['seats_per_row']; $j++){
                $seat_number = $i . chr(ord('A') + $j);
                $price = 0;

                if($j == 0 || $j == ($options['seats_per_row'] - 1)){
                    $price = $price + $options['window_price'];
                }

                if($i == 1){
                    $price = $price + $options['front_price'];
                }

                if(in_array($i, $options['legroom_rows'])){
                    $price = $price + $options['legroom_price'];
                }

                $seat = array(
                    'price' => $price,
                );

                $seats[$seat_number] = $seat;
            }
        }

        $this->seats = $seats;
    }

    /**
     * Seat a seat as reserved
     *
     * @return mixed
     */
    public function reserveSeat($seat, $userId){
        if(isset($this->seats[$seat])){
            if(!isset($this->seats[$seat]['reserved'])){
                $this->seats[$seat]['reserved'] = $userId;
                return true;
            }else{
                return false;
            }
        }else{
            return null;
        }
    }


    /**
     * Get seats reserved by user
     *
     * @param $user_id
     * @return array
     */
    public function getUserSeats($user_id){
        $seats = array();

        foreach($this->seats as $seat){
            if(isset($seat['reserved'])){
                if($seat['reserved'] == $user_id){
                    $seats[] = $seat;
                }
            }
        }

        return $seats;
    }

    /**
     * Seat a seat as unreserved
     *
     * @return void
     */
    public function unreserveSeat($seat){
        if(isset($this->seats[$seat])){
            unset($this->seats[$seat]['reserved']);
        }
    }

    public function refreshSeats(){
        $this->generateSeats();
    }
}

