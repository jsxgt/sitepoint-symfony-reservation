<?php
namespace ReservationBundle\Validator\Constraints;

use Doctrine\ORM\EntityManager;
use ReservationBundle\Entity\Reservation;
use ReservationBundle\Entity\User;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SubmitCheckValidator extends ConstraintValidator
{

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function validate($entity, Constraint $constraint)
    {
        $passengers = count($entity->getPassengers());

        $flight = $entity->getFlight();
        $seats = count($flight->getUserSeats($this->user->getId()));

        $baggages = count($entity->getBaggages());
        $freeBaggages = 0;

        foreach($entity->getBaggages() as $baggage){
            if($entity->baggageTypes[$baggage]['price'] == 0){
                $freeBaggages = $freeBaggages + 1;
            }
        }

        if($passengers == 0){
            $this->context->buildViolation($constraint->min_passengers)
                ->atPath('passengers')
                ->addViolation();
        }

        if($seats < $passengers){
            $this->context->buildViolation($constraint->min_seats_message)
                ->atPath('flight')
                ->addViolation();
        }

        if($seats > $passengers){
            $this->context->buildViolation($constraint->max_seats_message)
                ->atPath('flight')
                ->addViolation();
        }

        if($baggages < $passengers){
            $this->context->buildViolation($constraint->min_baggages_message)
                ->atPath('baggages')
                ->addViolation();
        }

        if($freeBaggages > $passengers){
            $this->context->buildViolation($constraint->max_baggages_message)
                ->atPath('baggages')
                ->addViolation();
        }
    }
}