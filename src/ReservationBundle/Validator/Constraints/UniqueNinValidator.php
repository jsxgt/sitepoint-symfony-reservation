<?php
namespace ReservationBundle\Validator\Constraints;

use Doctrine\ORM\EntityManager;
use ReservationBundle\Entity\User;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueNinValidator extends ConstraintValidator
{

    private $em;
    private $user;

    public function __construct(EntityManager $entityManager, User $user)
    {
        $this->em = $entityManager;
        $this->user = $user;
    }

    public function validate($value, Constraint $constraint)
    {
        $passengerRepository = $this->em->getRepository('ReservationBundle:Passenger');
        $passenger = $passengerRepository->findOneByNin($value);

        if(!is_null($passenger)){
            $message = $constraint->message;
            if($this->user == $passenger->getUser()){
                if($constraint->registeredUser){
                    $message = $constraint->registeredUser;
                }
            }else{
                if($constraint->registeredOther){
                    $message = $constraint->registeredOther;
                }
            }

            $this->context->buildViolation($message)
                ->addViolation();
        }
    }
}