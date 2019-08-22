<?php

declare(strict_types=1);

namespace App\UI\Cli\Command;

use App\Application\Command\User\ChangeAlive\ChangeAliveCommand;
use App\Application\Query\Item;
use App\Application\Query\User\FindByEmail\FindByEmailQuery;
use App\UI\Cli\Command\Base\CustomCommand;
use Assert\Assertion;
use League\Tactician\CommandBus;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ChangeUserAliveCLICommand extends CustomCommand
{
    /** @var CommandBus */
    private $commandBus;

    /** @var CommandBus */
    private $queryBus;

    /** @var string */
    private $email;

    /** @var bool */
    private $active;

    /** @var string */
    protected static $defaultName = 'app:user:change:alive';


    /**
     * ChangeUserAliveCLICommand constructor.
     * @param CommandBus $commandBus
     * @param CommandBus $queryBus
     */
    public function __construct(CommandBus $commandBus, CommandBus $queryBus)
    {
        $this->commandBus = $commandBus;
        $this->queryBus   = $queryBus;

        parent::__construct();
    }


    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Change flag "user::active" to enable/disable user')
            ->setDefinition([
                new InputArgument('email', InputArgument::REQUIRED, 'Email user'),
                new InputOption('enable', null, InputOption::VALUE_NONE, 'Enable user'),
                new InputOption('disable', null, InputOption::VALUE_NONE, 'Disable user'),
            ]);

        $this->addOptionForce();
    }


    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->email  = (string) $input->getArgument('email');
        $this->active = $this->getActiveOption($input);
    }


    /**
     * @param  InputInterface $input
     * @return bool
     */
    private function getActiveOption(InputInterface $input): bool
    {
        $enable  = (bool) $input->getOption('enable');
        $disable = (bool) $input->getOption('disable');

        Assertion::notEq($enable, $disable, 'One option is required, you must type ONE valid option (enable or disable)');

        return $enable;
    }


    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        if (!$this->isDryRun()) {
            $command = new ChangeAliveCommand($this->email, $this->active);
            $this->commandBus->handle($command);
        }

        $query = new FindByEmailQuery($this->email);
        $user  = $this->queryBus->handle($query);

        $this->showUser($output, $user);
    }


    /**
     * @param OutputInterface $output
     * @param Item            $user
     */
    private function showUser(OutputInterface $output, Item $user): void
    {
        $output->writeln('');

        $table = new Table($output);
        $table->setHeaders(['', 'User']);
        $table->addRow(['Uuid', $user->id]);
        $table->addRow(['UserName', $user->resource['credentials']['email']]);
        $table->addRow(['Active', ('true' == $user->resource['active']) ? 'Yes' : 'No']);
        $table->render();
    }
}
