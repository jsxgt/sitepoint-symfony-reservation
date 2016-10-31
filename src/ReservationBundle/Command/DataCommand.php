<?php
namespace ReservationBundle\Command;

use Proxies\__CG__\ReservationBundle\Entity\Salutation;
use ReservationBundle\Entity\Flight;
use ReservationBundle\Entity\Passenger;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DataCommand extends ContainerAwareCommand
{

    public function __construct($name = null)
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('data:add')
            ->setDescription('Add sample data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $users = [
            [
                'id' => 1,
                'username' => 'john.smith',
                'email' => 'john.smith@email.com',
                'password' => 'password'
            ],
            [
                'id' => 2,
                'username' => 'jane.smith',
                'email' => 'jane.smith@email.com',
                'password' => 'password'
            ]
        ];

        $salutations = [
            'Mr.',
            'Mrs.',
            'Miss',
            'Dr.',
            'Ms.',
            'Prof.'
        ];

        $flight = [
            'number' => '12345',
            'takeoff' => 'London',
            'landing' => 'Manchester'
        ];

        $passengers = [
            [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'nin' => 'EN335577A',
                'salutation' => 1,
                'user' => 1
            ],
            [
                'first_name' => 'Johanna',
                'last_name' => 'Smith',
                'nin' => 'FN557799A',
                'salutation' => 2,
                'user' => 1
            ],
            [
                'first_name' => 'Jeannie',
                'last_name' => 'Smith',
                'nin' => 'GN559977A',
                'salutation' => 3,
                'user' => 1
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'nin' => 'EJ577993A',
                'salutation' => 2,
                'user' => 2
            ],
            [
                'first_name' => 'James',
                'last_name' => 'Smith',
                'nin' => 'HJ759535A',
                'salutation' => 1,
                'user' => 2
            ],
            [
                'first_name' => 'Johnny',
                'last_name' => 'Smith',
                'nin' => 'KJ935373A',
                'salutation' => 1,
                'user' => 2
            ]
        ];

        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $manager = $this->getContainer()->get('fos_user.user_manager');
        $userRepository = $em->getRepository('ReservationBundle:User');
        $salutationRepository = $em->getRepository('ReservationBundle:Salutation');
        $flightRepository = $em->getRepository('ReservationBundle:Flight');
        $passengerRepository = $em->getRepository('ReservationBundle:Passenger');

        foreach($users as $user){
            $userEntity = $userRepository->findOneById($user['id']);
            if(is_null($userEntity)){
                $userEntity = $manager->createUser();
                $userEntity
                    ->setUsername($user['username'])
                    ->setEmail($user['john.smith@email.com'])
                    ->setPlainPassword($user['password'])
                    ->setEnabled(true);

                $em->persist($userEntity);
            }
        }

        for($i = 0; $i < count($salutations); $i++){
            $salutationEntity = $salutationRepository->findOneById(($i + 1));
            if(is_null($salutationEntity)){
                $salutationEntity = new Salutation();
                $salutationEntity->setSalutation($salutations[$i]);

                $em->persist($salutationEntity);
            }
        }

        $flightEntity = $flightRepository->findOneById(1);
        if(is_null($flightEntity)){
            $flightEntity = new Flight();
            $flightEntity->setNumber($flight['number']);
            $flightEntity->setTakeoff($flight['takeoff']);
            $flightEntity->setLanding($flight['landing']);

            $em->persist($flightEntity);
        }

        $em->flush();

        foreach($passengers as $passenger){
            $passengerEntity = $passengerRepository->findOneByNin($passenger['nin']);
            if(is_null($passengerEntity)){
                $passengerEntity = new Passenger();
                $salutationEntity = $salutationRepository->findOneById($passenger['salutation']);
                $userEntity = $userRepository->findOneById($passenger['user']);

                $passengerEntity->setFirstName($passenger['first_name']);
                $passengerEntity->setLastName($passenger['last_name']);
                $passengerEntity->setNin($passenger['nin']);

                $passengerEntity->setSalutation($salutationEntity);
                $passengerEntity->setUser($userEntity);

                $em->persist($passengerEntity);
            }
        }

        $em->flush();

        $output->writeln('Sample data added.');
    }
}
