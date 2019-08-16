<?php


namespace App\Infrastructure\User\Specification\Orm;


use App\Infrastructure\Doctrine\Specification\OrmSpecification;
use Doctrine\ORM\Query\Expr;

class UserIsActive extends OrmSpecification
{
    public function __construct(Expr $expr)
    {
        parent::__construct($expr);
    }


    public function getConditions()
    {
        return $this->expr->eq('user.active', true);
    }
}