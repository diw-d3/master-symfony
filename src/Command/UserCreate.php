<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class UserCreate extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;     
        
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:user:create')
            ->setDescription('A command to create an user.')
            ->addArgument('username', InputArgument::REQUIRED, 'Username for new user.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        // $output->writeln('<bg=green>Hello</>');
        $username = $input->getArgument('username');
        $username = $io->askQuestion((new Question('Votre username ?', null))->setAutocompleterValues(['toto', 'titi', 'tata']));

        if (strlen($username) < 3) {
            $io->error('Username not valid');

            return Command::FAILURE;
        }

        // Instancier une entité
        $user = new User();
        $user->setUsername($username);
        // Persister avec Doctrine
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success("{$username} has been created with success.");

        return Command::SUCCESS;
    }
}
