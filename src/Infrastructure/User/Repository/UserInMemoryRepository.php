<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Repository;

use App\Domain\Common\Specification\SpecificationInterface;
use App\Domain\User\Entity\UserViewInterface;
use App\Domain\User\Repository\UserRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;

class UserInMemoryRepository implements UserRepositoryInterface
{
    protected $entities;

    public function __construct()
    {
        $this->entities = new ArrayCollection();
    }

    public function getOneOrNull(SpecificationInterface $specification): ?UserViewInterface
    {
        $entity = null;
        dump($specification->getConditions());
        dump($specification->getParameters());

        return $entity;
    }

    /**
     * @param UserViewInterface $obj
     * @param bool $flush
     * @param bool $clear
     */
    public function save($obj, bool $flush = true, bool $clear = false): void
    {
        $this->entities->set($obj->getUuid()->toString(), $obj);
    }


    /**
     * @param UserViewInterface $obj
     * @param bool $flush
     */
    public function update($obj, bool $flush = true): void
    {
        $this->entities->set($obj->getUuid()->toString(), $obj);
    }


    /**
     * @param UserViewInterface $obj
     * @param bool $flush
     */
    public function remove($obj, $flush = true): void
    {
        $this->entities->remove($obj->getUuid()->toString());
    }
}