<?php

namespace ReservationBundle\Form;

use Doctrine\ORM\EntityManager;
use ReservationBundle\Entity\Flight;
use ReservationBundle\Entity\Passenger;
use ReservationBundle\Entity\User;
use ReservationBundle\Form\DataTransformer\FlightNumberTransformer;
use ReservationBundle\Repository\PassengerRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    private $user;
    private $em;

    public function __construct(User $user, EntityManager $entityManager)
    {
        $this->user = $user;
        $this->em = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('passengers', EntityType::class, array(
                'class' => Passenger::class,
                'query_builder' => function (PassengerRepository $repository) {
                    return $repository->createQueryBuilder('r')
                        ->where('r.user = :user')
                        ->setParameter('user', $this->user);
                },
                'choice_label' => function ($passenger) {
                    return $passenger->getLabelString();
                },
                'multiple' => true,
                'expanded' => true
            ))
            ->add('flight', HiddenType::class);

        $builder
            ->get('flight')
            ->addModelTransformer(new FlightNumberTransformer($this->em));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ReservationBundle\Entity\Reservation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'reservationbundle_reservation';
    }


}
