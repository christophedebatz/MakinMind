<?php

namespace MakinMind\UserBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use FOS\UserBundle\Command\CreateUserCommand as BaseCommand;

class CreateUserCommand extends BaseCommand
{
	/**
     * @see Command
     */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('bundle:user:create')
            ->getDefinition()->addArguments(array(
                new InputArgument('firstname', InputArgument::REQUIRED, 'The firstname'),
                new InputArgument('lastname', InputArgument::REQUIRED, 'The lastname'),
                new InputArgument('phone', InputArgument::REQUIRED, 'The phone'),
                new InputArgument('address', InputArgument::REQUIRED, 'The address'),
                new InputArgument('zipcode', InputArgument::REQUIRED, 'The zipcode'),
                new InputArgument('city', InputArgument::REQUIRED, 'The city'),
                new InputArgument('country', InputArgument::REQUIRED, 'The country'),
                new InputArgument('job', InputArgument::REQUIRED, 'The job'),
                new InputArgument('company', InputArgument::REQUIRED, 'The company')
            ));
    }


     /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $username   = $input->getArgument('username');
        $email      = $input->getArgument('email');
        $password   = $input->getArgument('password');

        $firstname  = $input->getArgument('firstname');
        $lastname   = $input->getArgument('lastname');
        $phone      = $input->getArgument('phone');
        $address    = $input->getArgument('address');
        $zipcode    = $input->getArgument('zipcode');
        $city       = $input->getArgument('city');
        $country    = $input->getArgument('country');
        $job        = $input->getArgument('job');
        $company    = $input->getArgument('company');

        $inactive   = $input->getOption('inactive');
        $superadmin = $input->getOption('super-admin');

         /*
          * @var \FOS\UserBundle\Model\UserManager $user_manager 
          */
        $user_manager = $this->getContainer()->get('fos_user.user_manager');

         /*
          * @var \MakinMind\UserBundle\Entity\User $user
          */
		$user = $user_manager->createUser();

        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPlainPassword($password);
        $user->setEnabled((Boolean) !$inactive);
        $user->setSuperAdmin((Boolean) $superadmin);

        $user->setFirstName($firstname);
        $user->setLastName($lastname);
        $user->setPhone($phone);
        $user->setAddress($address);
        $user->setZipCode($zipcode);
        $user->setCity($city);
        $user->setCountry($country);
        $user->setJob($job);
        $user->setCompany($company);
        $user->setBirthdate(new \Datetime());
        $user->setSuscribeDate(new \Datetime());

        $user_manager->updateUser($user);
        $output->writeln(sprintf('Created user <comment>%s</comment>', $username));
    }



    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);
        if (!$input->getArgument('firstname')) {
            $firstname = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose a firstname:',
                function($firstname) {
                    if (empty($firstname)) {
                        throw new \Exception('Firstname can not be empty');
                    }

                    return $firstname;
                }
            );
            $input->setArgument('firstname', $firstname);
        }
        if (!$input->getArgument('lastname')) {
            $lastname = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose a lastname:',
                function($lastname) {
                    if (empty($lastname)) {
                        throw new \Exception('Lastname can not be empty');
                    }

                    return $lastname;
                }
            );
            $input->setArgument('lastname', $lastname);
        }
        if (!$input->getArgument('phone')) {
            $phone = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose a phone:',
                function($phone) {
                    if (empty($phone)) {
                        throw new \Exception('Phone can not be empty');
                    }

                    return $phone;
                }
            );
            $input->setArgument('phone', $phone);
        }
        if (!$input->getArgument('address')) {
            $address = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose a address:',
                function($address) {
                    if (empty($address)) {
                        throw new \Exception('address can not be empty');
                    }

                    return $address;
                }
            );
            $input->setArgument('address', $address);
        }
        if (!$input->getArgument('zipcode')) {
            $zipcode = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose a zipcode:',
                function($zipcode) {
                    if (empty($zipcode)) {
                        throw new \Exception('zipcode can not be empty');
                    }

                    return $zipcode;
                }
            );
            $input->setArgument('zipcode', $zipcode);
        }
        if (!$input->getArgument('city')) {
            $city = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose a city:',
                function($city) {
                    if (empty($city)) {
                        throw new \Exception('city can not be empty');
                    }

                    return $city;
                }
            );
            $input->setArgument('city', $city);
        }
        if (!$input->getArgument('country')) {
            $country = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose a country:',
                function($country) {
                    if (empty($country)) {
                        throw new \Exception('country can not be empty');
                    }

                    return $country;
                }
            );
            $input->setArgument('country', $country);
        }
        if (!$input->getArgument('job')) {
            $job = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose a job:',
                function($job) {
                    if (empty($job)) {
                        throw new \Exception('job can not be empty');
                    }

                    return $job;
                }
            );
            $input->setArgument('job', $job);
        }
        if (!$input->getArgument('company')) {
            $company = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose a company:',
                function($company) {
                    if (empty($company)) {
                        throw new \Exception('company can not be empty');
                    }

                    return $company;
                }
            );
            $input->setArgument('company', $company);
        }
       
    }
}
