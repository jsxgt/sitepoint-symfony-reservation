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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

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
        $em = $this->getDoctrine()->getManager();

        $flightRepository = $em->getRepository('ReservationBundle:Flight');

        $flight = $flightRepository->findOneById(1);

        $passenger = new Passenger();

        $passengerForm = $this->createForm(PassengerType::class, $passenger);

        $reservation = new Reservation();

        $reservation->setFlight($flight);

        $reservationForm = $this->createForm(ReservationType::class, $reservation);

        return $this->render(
            'ReservationBundle:Default:index.html.twig',
            array(
                'form' => $reservationForm->createView(),
                'flight' => $flight
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

    /**
     * @Route("/update-seat/{flight}", name="reservation_update_seat")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateSeatAction(Request $request, $flight){
        $action = $request->get('action', 'reserve');

        $em = $this->getDoctrine()->getManager();

        $flightRepository = $em->getRepository('ReservationBundle:Flight');

        $flightEntity = $flightRepository->findOneByNumber($flight);

        if(!is_null($flightEntity)){
            $error = false;

            if($request->get('seat')){
                $seat = $request->get('seat');

                if($action == 'reserve'){
                    $user = $this->getUser();

                    $seatReservation = $flightEntity->reserveSeat($seat, $user->getId());
                    if($seatReservation === false){
                        $error = 'reserved';
                    }

                    if(is_null($seatReservation)){
                        $error = 'not_found';
                    }
                }

                if($action == 'unreserve'){
                    $flightEntity->unreserveSeat($seat);
                }
            }

            $em->flush();

            $seats = $flightEntity->getSeats();

            $seatsRender = $this->render(
                'ReservationBundle:Default:seats.html.twig',
                array(
                    'seats' => $seats,
                    'error' => $error
                )
            );

            if($request->get('seat')) {
                $return = array(
                    'seats' => $seatsRender->getContent(),
                    'error' => $error
                );
                return new JsonResponse($return);
            }else{
                return $seatsRender;
            }
        }else{
            return new Response();
        }
    }

    /**
     * @Route("/randomise-seats/{flight}", name="reservation_randomise_seats")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function randomiseSeatsAction(Request $request, $flight){
        $em = $this->getDoctrine()->getManager();

        $flightRepository = $em->getRepository('ReservationBundle:Flight');

        $flightEntity = $flightRepository->findOneByNumber($flight);

        $flightEntity->refreshSeats();

        $seats = $flightEntity->getSeats();

        for($i = 0; $i < 15; $i++){
            $seatKeys = array_keys($seats);
            $seat = $seatKeys[rand(0, count($seatKeys) - 1)];

            $flightEntity->reserveSeat($seat, 0);
        }

        $em->flush();

        return new Response('OK');
    }

}
