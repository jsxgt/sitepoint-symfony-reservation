<?php
namespace ReservationBundle\Form\DataTransformer;

use Doctrine\ORM\EntityManager;
use ReservationBundle\Entity\Flight;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FlightNumberTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Transforms a flight object to a flight number.
     *
     * @param  Flight|null $flight
     * @return string
     */
    public function transform($flight)
    {
        if (null === $flight) {
            return '';
        }

        return $flight->getNumber();
    }

    /**
     * Transforms a flight number to a flight object.
     *
     * @param  string $flightNumber
     * @return \ReservationBundle\Entity\Flight|null
     */
    public function reverseTransform($flightNumber)
    {
        if (!$flightNumber) {
            return null;
        }

        $flightRepository = $this->em->getRepository('ReservationBundle:Flight');
        $flight = $flightRepository->findOneByNumber($flightNumber);

        if (null === $flight) {
            throw new TransformationFailedException(sprintf(
                'The flight with number "%s" does not exist!',
                $flightNumber
            ));
        }

        return $flight;
    }
}
