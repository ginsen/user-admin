<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\CommonBag\ORM;

use App\Domain\Common\PersistLayer\PersistLayerInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder as DbalQueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder as OrmQueryBuilder;

abstract class MySqlRepository implements PersistLayerInterface
{
    /** @var string */
    protected $class;

    /** @var EntityRepository */
    protected $repository;

    /** @var EntityManagerInterface */
    private $entityManager;


    /**
     * MySqlRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository    = $this->entityManager->getRepository($this->class);
    }


    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->entityManager->getConnection();
    }


    /**
     * @param $obj
     * @param bool $flush
     * @param bool $clear
     */
    public function save($obj, bool $flush = true, bool $clear = false): void
    {
        $this->entityManager->persist($obj);

        if ($flush) {
            $this->flushDb();
        }

        if ($clear) {
            $this->clearDb();
        }
    }


    /**
     * @param $obj
     * @param bool $flush
     */
    public function update($obj, bool $flush = true): void
    {
        $this->entityManager->merge($obj);

        if ($flush) {
            $this->flushDb();
        }
    }


    /**
     * @param $obj
     * @param bool $flush
     */
    public function remove($obj, $flush = true): void
    {
        $this->entityManager->remove($obj);

        if ($flush) {
            $this->flushDb();
        }
    }


    public function flushDb(): void
    {
        $this->entityManager->flush();
    }


    public function clearDb(): void
    {
        $this->entityManager->clear();
    }


    /**
     * @return OrmQueryBuilder
     */
    public function createOrmQueryBuilder(): OrmQueryBuilder
    {
        return $this->entityManager->createQueryBuilder();
    }


    /**
     * @return DbalQueryBuilder
     */
    public function createDbalQueryBuilder(): DbalQueryBuilder
    {
        return $this->getConnection()->createQueryBuilder();
    }
}
