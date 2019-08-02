<?php

declare(strict_types=1);

namespace App\UI\Cli\Command\Broadway;

use App\UI\Cli\Command\Base\CustomCommand;
use Broadway\EventStore\Dbal\DBALEventStore;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SchemaEventStoreCreateCommand extends CustomCommand
{
    /** @var string */
    protected static $defaultName = 'broadway:event-store:schema:init';

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
        $this->setDescription('Creates the event store schema.');
    }


    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $schemaManager = $this->connection->getSchemaManager();
            $schema        = $schemaManager->createSchema();
            $table         = $this->eventStore->configureSchema($schema);

            if (null !== $table) {
                $schemaManager->createTable($table);
                $output->writeln('<info>Created Broadway event-store schema</info>');
            } else {
                $output->writeln('<info>Broadway event-store schema already exists</info>');
            }
        } catch (\Exception $e) {
            $output->writeln('<error>Could not create Broadway event-store schema</error>');
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
        }
    }
}
