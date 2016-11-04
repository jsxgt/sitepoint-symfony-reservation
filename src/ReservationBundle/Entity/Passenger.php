<?php

namespace ReservationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ReservationBundle\Validator\Constraints as ReservationAssert;

/**
 * Passenger
 *
 * @ORM\Table(name="passenger")
 * @ORM\Entity(repositoryClass="ReservationBundle\Repository\PassengerRepository")
 */
class Passenger
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
     * @ORM\ManyToOne(targetEntity="Salutation")
     *
     * @ORM\JoinColumn(name="salutation_id", referencedColumnName="id")
     */
    private $salutation;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="nin", type="string", length=9, unique=true)
     *
     * @Assert\NotBlank()
     *
     * @Assert\Regex(
     *     pattern="/(?![DFIQUV])[A-Z](?![DFIOQUV])[A-Z]\d{6}[A-D]/",
     *     message="This NIN is not in the proper format."
     * )
     *
     * @ReservationAssert\UniqueNin(
     *     registered_user = "This NIN has already been registered by you.",
     *     registered_other = "This NIN has been registered by another user."
     * )
    */
    private $nin;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     *
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

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
     * Set salutation
     *
     * @param \ReservationBundle\Entity\Salutation $salutation
     *
     * @return Passenger
     */
    public function setSalutation(\ReservationBundle\Entity\Salutation $salutation = null)
    {
        $this->salutation = $salutation;

        return $this;
    }

    /**
     * Get salutation
     *
     * @return \ReservationBundle\Entity\Salutation
     */
    public function getSalutation()
    {
        return $this->salutation;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Passenger
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Passenger
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set nin
     *
     * @param string $nin
     *
     * @return Passenger
     */
    public function setNin($nin)
    {
        $this->nin = $nin;

        return $this;
    }

    /**
     * Get nin
     *
     * @return string
     */
    public function getNin()
    {
        return $this->nin;
    }

    /**
     * Set user
     *
     * @param \ReservationBundle\Entity\User $user
     *
     * @return Passenger
     */
    public function setUser(\ReservationBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \ReservationBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get string for form label
     *
     * @return string
     */
    public function getLabelString(){
        $return = array(
            $this->firstName,
            $this->lastName,
            '(' . $this->nin . ')'
        );

        return implode(' ', $return);
    }
}
