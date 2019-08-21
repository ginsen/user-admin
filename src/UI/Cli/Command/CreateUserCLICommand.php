<?php

declare(strict_types=1);

namespace App\UI\Cli\Command;

use App\Application\Command\User\SignUp\SignUpCommand;
use App\Application\Query\Item;
use App\Application\Query\User\FindByEmail\FindByEmailQuery;
use App\Domain\User\ValueObj\Credentials;
use App\UI\Cli\Command\Base\CustomCommand;
use League\Tactician\CommandBus;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateUserCLICommand extends CustomCommand
{
    /** @var CommandBus */
    private $commandBus;

    /** @var CommandBus */
    private $queryBus;

    /** @var string */
    private $password;

    /** @var string */
    protected static $defaultName = 'app:user:create';


    /**
     * CreateUserCLICommand constructor.
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
            ->setDescription('Given a email and password, generate once user')
            ->addArgument('email', InputArgument::REQUIRED, 'Email user');
    }


    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $question  = $this->getQuestionPassword('Enter password for user');
        $firstResp = $helper->ask($input, $output, $question);

        $question = $this->getQuestionPassword('Verifying - Enter password for user again', $firstResp);
        $password = $helper->ask($input, $output, $question);

        $this->password = $password;
    }


    /**
     * @param  string      $phrase
     * @param  string|null $firstPassword
     * @return Question
     */
    private function getQuestionPassword(string $phrase, string $firstPassword = null): Question
    {
        $question = new Question(sprintf('%s: ', $phrase));
        $question->setValidator(Credentials::getPasswordValidator($firstPassword));
        $question->setHidden(true);
        $question->setMaxAttempts(4);

        return $question;
    }


    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $command = new SignUpCommand(
            $uuid     = Uuid::uuid4()->toString(),
            $email    = (string) $input->getArgument('email'),
            $password = (string) $this->password
        );

        $this->commandBus->handle($command);

        $query = new FindByEmailQuery($email);
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
        $table->setHeaders(['', 'User Created']);
        $table->addRow(['Uuid', $user->id]);
        $table->addRow(['UserName', $user->resource['credentials']['email']]);
        $table->render();
    }
}
