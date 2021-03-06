<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Repository;

use App\Domain\Common\Specification\SpecificationInterface;
use App\Domain\User\Entity\UserViewInterface;
use App\Domain\User\Repository\UserReadModelInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\NonUniqueResultException;

class UserInMemoryReadModel implements UserReadModelInterface
{
    protected $entities;

    public function __construct()
    {
        $this->entities = new ArrayCollection();
    }


    /**
     * @param  SpecificationInterface $specification
     * @throws \Exception
     * @return UserViewInterface|null
     */
    public function getOneOrNull(SpecificationInterface $specification): ?UserViewInterface
    {
        $criteria   = $this->getCriteria($specification);
        $collection = $this->entities->matching($criteria);

        if (\count($collection) > 1) {
            throw new NonUniqueResultException();
        }

        if (0 == \count($collection)) {
            return null;
        }

        $elements = current($collection);

        return current($elements);
    }


    /**
     * @param UserViewInterface $obj
     * @param bool              $flush
     * @param bool              $clear
     */
    public function save($obj, bool $flush = true, bool $clear = false): void
    {
        $this->entities->set($obj->getUuid()->toString(), $obj);
    }


    /**
     * @param UserViewInterface $obj
     * @param bool              $flush
     */
    public function update($obj, bool $flush = true): void
    {
        $this->entities->set($obj->getUuid()->toString(), $obj);
    }


    /**
     * @param UserViewInterface $obj
     * @param bool              $flush
     */
    public function remove($obj, $flush = true): void
    {
        $this->entities->remove($obj->getUuid()->toString());
    }


    /**
     * @param  SpecificationInterface $specification
     * @return Criteria
     */
    protected function getCriteria(SpecificationInterface $specification): Criteria
    {
        $criteria = Criteria::create();

        return $criteria->where($specification->getConditions());
    }
}
