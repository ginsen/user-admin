<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Repository;

use App\Domain\Common\Specification\SpecificationInterface;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Infrastructure\Doctrine\ORM\MySqlRepository;
use App\Infrastructure\User\Projection\UserView;
use Broadway\ReadModel\SerializableReadModel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;

class UserRepository extends MySqlRepository implements UserRepositoryInterface
{
    /**
     * UserRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->class = UserView::class;
        parent::__construct($entityManager);
    }


    /**
     * @param SpecificationInterface $specification
     * @return SerializableReadModel|null
     * @throws NonUniqueResultException
     */
    public function getOneOrNull(SpecificationInterface $specification): ?SerializableReadModel
    {
        $query = $this->getOrmQuery($specification);

        return $query->getOneOrNullResult();
    }


    /**
     * @param  SpecificationInterface $specification
     * @return Query
     */
    protected function getOrmQuery(SpecificationInterface $specification): Query
    {
        $builder = $this->createOrmQueryBuilder();

        $builder
            ->select('user')
            ->from(UserView::class, 'user')
            ->where($specification->getConditions())
            ->setParameters($specification->getParameters());

        return $builder->getQuery();
    }
}
