<?php
namespace ReservationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueNin extends Constraint
{
    public $message = 'This NIN has already been registered.';
    public $registeredUser = '';
    public $registeredOther = '';

    public function __construct($options = array())
    {
        if(isset($options['registered_user'])){
            $this->registeredUser = $options['registered_user'];
        }

        if(isset($options['registered_other'])){
            $this->registeredOther = $options['registered_other'];
        }
    }

    public function validatedBy()
    {
        return 'unique_nin';
    }

    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}