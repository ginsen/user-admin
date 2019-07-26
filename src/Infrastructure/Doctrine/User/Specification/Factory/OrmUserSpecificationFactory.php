<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\User\Specification\Factory;

use App\Domain\Common\Specification\SpecificationInterface;
use App\Domain\User\Specification\UserSpecificationFactoryInterface;
use App\Domain\User\ValueObj\Email;
use App\Infrastructure\Doctrine\User\Specification\Orm\UserWithEmail;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr;

class OrmUserSpecificationFactory implements UserSpecificationFactoryInterface
{
    /** @var Expr */
    private $expr;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->expr = $entityManager->getExpressionBuilder();
    }


    public function createForFindOneWithEmail(Email $email): SpecificationInterface
    {
        return new UserWithEmail($this->expr, $email);
    }
}
