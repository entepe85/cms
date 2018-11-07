<?php

declare(strict_types=1);

namespace RevisionTen\CMS\Command\Console;

use Ramsey\Uuid\Uuid;
use RevisionTen\CMS\Model\UserRead;
use Doctrine\ORM\EntityManagerInterface;
use RevisionTen\CMS\Services\UserService;
use RevisionTen\CQRS\Services\CommandBus;
use RevisionTen\CQRS\Services\MessageBus;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserCreateCommand.
 *
 * This command lets you create a user from the command line.
 */
class UserCreateCommand extends Command
{
    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    /** @var UserPasswordEncoderInterface $encoder */
    private $encoder;

    /** @var CommandBus $commandBus */
    private $commandBus;

    /** @var MessageBus $messageBus */
    private $messageBus;

    /** @var UserService $userService */
    private $userService;

    /** @var array */
    private $config;

    /**
     * UserCreateCommand constructor.
     *
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface       $entityManager
     * @param CommandBus                   $commandBus
     * @param MessageBus                   $messageBus
     * @param UserService                  $userService
     * @param array                        $config
     */
    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager, CommandBus $commandBus, MessageBus $messageBus, UserService $userService, array $config)
    {
        $this->entityManager = $entityManager;
        $this->encoder = $encoder;
        $this->commandBus = $commandBus;
        $this->messageBus = $messageBus;
        $this->userService = $userService;
        $this->config = $config;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('cms:user:create')
            ->setDescription('Creates a user account.')
            ->addArgument('username', InputArgument::OPTIONAL, 'The username.')
            ->addArgument('password', InputArgument::OPTIONAL, 'The users password.')
            ->addArgument('email', InputArgument::OPTIONAL, 'The users email.')
            ->addArgument('avatarUrl', InputArgument::OPTIONAL, 'The users avatar url.')
            ->addOption('sendLoginMail', null, InputOption::VALUE_REQUIRED, 'Whether or not to send a mail with the login info to the user.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        // Get the username or ask for it.
        $username = $input->getArgument('username');
        if (!$username) {
            $usernameQuestion = new Question('Please enter a username: ');

            $usernameQuestion->setValidator(function ($answer) {
                if ($this->entityManager->getRepository(UserRead::class)->findOneByUsername($answer)) {
                    throw new \RuntimeException('This username is already taken.');
                }

                return $answer;
            });
            $usernameQuestion->setMaxAttempts(5);

            $username = $helper->ask($input, $output, $usernameQuestion);
        }

        // Get the email or ask for it.
        $email = $input->getArgument('email');
        if (!$email) {
            $emailQuestion = new Question('Please enter an email: ');

            $emailQuestion->setValidator(function ($answer) {
                if ($this->entityManager->getRepository(UserRead::class)->findOneByEmail($answer)) {
                    throw new \RuntimeException('This email is already taken.');
                }

                return $answer;
            });
            $emailQuestion->setMaxAttempts(5);

            $email = $helper->ask($input, $output, $emailQuestion);
        }

        // Get the password or ask for it.
        $password = $input->getArgument('password');
        if (!$password) {
            $passwordQuestion = new Question('Please enter a password: ');

            $passwordQuestion->setValidator(function ($answer) {
                if (empty($answer)) {
                    throw new \RuntimeException('The password may not be empty.');
                }

                return $answer;
            });
            $passwordQuestion->setMaxAttempts(5);

            $password = $helper->ask($input, $output, $passwordQuestion);
        }

        // Get the avatarUrl or ask for it.
        $avatarUrl = $input->getArgument('avatarUrl');
        if (!$avatarUrl) {
            $avatarUrlQuestion = new Question('(optional) Please enter an avatar url: ');
            $avatarUrl = $helper->ask($input, $output, $avatarUrlQuestion);
        }

        // Ask If the login data should be sent via mail.
        $sendLoginMail = $input->getOption('sendLoginMail');
        if (!$sendLoginMail) {
            $sendLoginMailQuestion = new ChoiceQuestion('Do you want to send a mail with the login info to the user? ', [
                'Yes',
                'No',
            ]);
            $sendLoginMailQuestion->setErrorMessage('Answer %s is invalid. Please answer Yes or No.');
            $sendLoginMailQuestion->setAutocompleterValues([
                'Yes',
                'No',
            ]);
            $sendLoginMailQuestion->setValidator(function ($answer) {
                if ('Yes' !== $answer && 'No' !== $answer) {
                    throw new \RuntimeException('Yes or No?');
                }

                return $answer;
            });
            $sendLoginMailQuestion->setMaxAttempts(5);

            $sendLoginMail = $helper->ask($input, $output, $sendLoginMailQuestion);
            $sendLoginMail = ('Yes' === $sendLoginMail);
        }

        $useMailCodes = $this->config['use_mail_codes'] ?? false;

        // Encoded the password.
        $encodedPassword = $this->encoder->encodePassword(new UserRead(), $password);

        // Generate a secret.
        $googleAuthenticator = new GoogleAuthenticator();
        $secret = $googleAuthenticator->generateSecret();

        // Create the User.
        $payload = [
            'username' => $username,
            'email' => $email,
            'avatarUrl' => $avatarUrl,
            'password' => $encodedPassword,
            'secret' => $secret,
            'color' => null,
            'sendLoginMail' => $sendLoginMail,
            'useMailCodes' => $useMailCodes,
        ];

        $success = false;
        $successCallback = function ($commandBus, $event) use (&$success) { $success = true; };
        $userUuid = Uuid::uuid1()->toString();
        $userCreateCommand = new \RevisionTen\CMS\Command\UserCreateCommand(-1, null, $userUuid, 0, $payload, $successCallback);
        $this->commandBus->dispatch($userCreateCommand);

        if ($success) {
            // Return info about the new user.
            $output->writeln('User saved.');
            $output->writeln('Username: '.$username);
            $output->writeln('Password: '.$password);
            $output->writeln('Avatar url: '.$avatarUrl);
            $output->writeln('Email: '.$email);
            if ($sendLoginMail) {
                $this->userService->sendLoginInfo($userUuid, $password);
                $output->writeln('Email with login info was sent to '.$email);
            }
        } else {
            $messages = $this->messageBus->getMessagesJson();
            $output->writeln('UserCreateCommand failed.');
            $output->writeln($messages);
        }
    }

    /**
     * Generate a random string, using a cryptographically secure
     * pseudorandom number generator (random_int).
     *
     * For PHP 7, random_int is a PHP core function
     * For PHP 5.x, depends on https://github.com/paragonie/random_compat
     *
     * @param int    $length   How many characters do we want?
     * @param string $keyspace A string of all possible characters
     *                         to select from
     *
     * @return string
     */
    private function random_str($length, $keyspace = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces[] = $keyspace[random_int(0, $max)];
        }

        return implode('', $pieces);
    }
}
