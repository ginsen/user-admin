<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\User\Specification\Orm;

use App\Domain\User\ValueObj\Email;
use App\Infrastructure\Doctrine\CommonBag\Specification\OrmSpecification;
use Doctrine\ORM\Query\Expr;

class UserWithEmail extends OrmSpecification
{
    public function __construct(Expr $expr, Email $email)
    {
        $this->setParameter('email', $email);

        parent::__construct($expr);
    }


    public function getConditions()
    {
        return $this->expr->eq('user.credentials.email', ':email');
    }
}
