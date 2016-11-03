<?php

namespace ReservationBundle\Form;

use ReservationBundle\Entity\Salutation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PassengerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nin', TextType::class)
            ->add('salutation', EntityType::class, array(
                'class' => Salutation::class,
                'choice_label' => 'salutation'
            ))
            ->add('firstName')
            ->add('lastName');

        $builder
            ->get('nin')
            ->addModelTransformer(new CallbackTransformer(
                function ($ninFromDatabase){
                    return $ninFromDatabase;
                },
                function ($ninFromInput){
                    return preg_replace('/[\s\-\.]/', '', $ninFromInput);
                }
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ReservationBundle\Entity\Passenger'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'reservationbundle_passenger';
    }


}
