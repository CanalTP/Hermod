<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Question\ChoiceQuestion;

class UpdateUserRoleCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'hermod:user:role';

    private $io = null;
    private $user = null;
    private $roles = [
        'ROLE_ROOT',
        'ROLE_V1_STATUS_PATCH_CREATE_EQUIPMENT_STATUS',
        'ROLE_V1_LOCATION_PATCH_CREATE',
        'ROLE_V1_LOCATION_PATCH_CREATE_FROM_REPORTER',
        'ROLE_V1_GET_EQUIPMENTS'
    ];

    protected function configure()
    {
        $this
            ->setDescription('Update roles of user.')
            ->setHelp(
                'This command helps you to update role of user by providing its username.'
            )
            ->addArgument('username', InputArgument::OPTIONAL, 'The username of the user');
    }

    private function findUser($username)
    {
        return $this->getContainer()
            ->get('doctrine')
            ->getRepository('AppBundle:User')
            ->findOneByUsername($username);
    }

    private function askWichRoles(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $rolesQuestion = new ChoiceQuestion('Please select roles', $this->roles);

        $this->io->title('Choose roles for ' . $this->user->getUsername());
        $rolesQuestion->setMultiselect(true);

        return $helper->ask($input, $output, $rolesQuestion);
    }

    private function saveUser()
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $em->persist($this->user);
        $em->flush();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');

        $this->user = $this->findUser($username);
        while (is_null($this->user)) {
            $username = $this->io->ask('Username ' . $username . ' is not found, please choose another one');
            $this->user = $this->findUser($username);
        }
        $this->user->setRoles($this->askWichRoles($input, $output));
        $this->saveUser();

        $this->io->success('Now user "' . $username . '" have ' . implode(', ', $this->user->getRoles()) . ' roles.');
    }
}
