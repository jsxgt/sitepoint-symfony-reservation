<?php
namespace ReservationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class SubmitCheck extends Constraint
{
    public $min_passengers = 'You have not selected any passengers.';
    public $min_seats_message = 'You have selected fewer seats than required.';
    public $max_seats_message = 'You have selected more seats than allowed.';
    public $min_baggages_message = 'You have selected fewer baggages than required.';
    public $max_baggages_message = 'You have selected more free baggages than allowed.';

    public function __construct($options = array())
    {
        foreach($options as $key => $value){
            if(isset($this->$key)){
                $this->$key = $value;
            }
        }
    }

    public function validatedBy()
    {
        return 'submit_check';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}