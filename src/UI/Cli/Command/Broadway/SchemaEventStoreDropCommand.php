<?php

declare(strict_types=1);

namespace App\UI\Cli\Command\Broadway;

use App\UI\Cli\Command\Base\CustomCommand;
use Broadway\EventStore\Dbal\DBALEventStore;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SchemaEventStoreDropCommand extends CustomCommand
{
    /** @var string */
    protected static $defaultName = 'broadway:event-store:schema:drop';

    /** @var DBALEventStore */
    protected $eventStore;

    /** @var Connection */
    protected $connection;

    /** @var \Exception */
    protected $exception;


    /**
     * SchemaEventStoreCreateCommand constructor.
     * @param DBALEventStore         $eventStore
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(DBALEventStore $eventStore, EntityManagerInterface $entityManager)
    {
        $this->eventStore = $eventStore;
        $this->connection = $entityManager->getConnection();

        parent::__construct();
    }


    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setDescription('Drops the event store schema.');
    }


    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $schemaManager = $this->connection->getSchemaManager();
            $table         = $this->eventStore->configureTable();

            if ($schemaManager->tablesExist([$table->getName()])) {
                $schemaManager->dropTable($table->getName());
                $output->writeln('<info>Dropped Broadway event-store schema</info>');
            } else {
                $output->writeln('<info>Broadway event-store schema does not exist</info>');
            }
        } catch (\Exception $e) {
            $output->writeln('<error>Could not drop Broadway event-store schema</error>');
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
        }
    }
}
