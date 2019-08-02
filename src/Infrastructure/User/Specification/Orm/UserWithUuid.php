<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Specification\Orm;

use App\Infrastructure\Doctrine\Specification\OrmSpecification;
use Doctrine\ORM\Query\Expr;
use Ramsey\Uuid\UuidInterface;

class UserWithUuid extends OrmSpecification
{
    public function __construct(Expr $expr, UuidInterface $uuid)
    {
        $this->setParameter('uuid', $uuid);

        parent::__construct($expr);
    }


    public function getConditions()
    {
        return $this->expr->eq('user.uuid', ':uuid');
    }
}
