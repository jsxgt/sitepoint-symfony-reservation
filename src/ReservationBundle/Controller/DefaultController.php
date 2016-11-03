<?php

namespace ReservationBundle\Controller;

use ReservationBundle\Entity\Flight;
use ReservationBundle\Entity\Passenger;
use ReservationBundle\Entity\Reservation;
use ReservationBundle\Form\PassengerType;
use ReservationBundle\Form\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/reservation")
 */

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {

        $passenger = new Passenger();

        $passengerForm = $this->createForm(PassengerType::class, $passenger);

        $reservation = new Reservation();

        $reservationForm = $this->createForm(ReservationType::class, $reservation);

        return $this->render(
            'ReservationBundle:Default:index.html.twig',
            array(
                'form' => $reservationForm->createView(),
                'passenger_form' => $passengerForm->createView()
            )
        );
    }

    /**
     * @Route("/add-passenger", name="reservation_add_passenger")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addPassengerAction(Request $request){
        $return = array();

        $form = $this->createForm(PassengerType::class, new Passenger());

        $form->handleRequest($request);

        if($form->isSubmitted()){

            if($form->isValid()){

                $em = $this->getDoctrine()->getManager();

                $user = $this->getUser();

                $passenger = $form->getData();
                $passenger->setUser($user);

                $em->persist($passenger);
                $em->flush();

                $return = array(
                    'message' => 'ok',
                    'id' => $passenger->getId(),
                    'label' => $passenger->getLabelString()
                );
            }else{
                $formRender = $this->render(
                    'ReservationBundle:Default:passenger-form.html.twig',
                    array(
                        'form' => $form->createView()
                    )
                );

                $return = array(
                    'message' => 'error',
                    'form' => $formRender->getContent()
                );
            }

            return new JsonResponse($return);
        }else{
            return $this->render(
                'ReservationBundle:Default:passenger-form.html.twig',
                array(
                    'form' => $form->createView()
                )
            );
        }
    }
}
