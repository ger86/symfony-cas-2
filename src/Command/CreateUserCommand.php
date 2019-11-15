<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:user:create';

    private $userRepository;
    private $userPasswordEncoder;
    private $em;

    public function __construct(
        UserRepository $userRepository, 
        UserPasswordEncoderInterface $userPasswordEncoder,
        EntityManagerInterface $em)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->em = $em;
    }

    public function configure() {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'The email of the user.')
            ->addArgument('password', InputArgument::REQUIRED, 'The password');
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $email = $input->getArgument('email');
        $plainPassword = $input->getArgument('password');

        $user = $this->userRepository->findOneByEmail($email);
        if (!!$user) {
            $output->writeln('Ese email ya existe');
            return;
        }

        $user = new User();
        $user->setEmail($email);
        $password = $this->userPasswordEncoder->encodePassword($user, $plainPassword);
        $user->setPassword($password);

        $this->em->persist($user);
        $this->em->flush();

        $output->writeln(sprintf('User created with email %s and password %s',
            $email,
            $password));
    }

}