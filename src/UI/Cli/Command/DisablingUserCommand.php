<?php

declare(strict_types=1);

namespace App\UI\Cli\Command;

use App\Application\Command\User\DisableUser\DisableUserCommand;
use App\UI\Cli\Command\Base\CustomCommand;
use League\Tactician\CommandBus;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DisablingUserCommand extends CustomCommand
{
    /** @var CommandBus */
    private $commandBus;

    /** @var string */
    protected static $defaultName = 'app:user:disabling';


    /**
     * DisableUserCommand constructor.
     * @param CommandBus $commandBus
     */
    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;

        parent::__construct();
    }


    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Disabling one user by username (email) given')
            ->addArgument('email', InputArgument::REQUIRED, 'Email username');
    }


    public function execute(InputInterface $input, OutputInterface $output)
    {
        $command = new DisableUserCommand(
            $email = (string) $input->getArgument('email')
        );

        $this->commandBus->handle($command);
    }
}