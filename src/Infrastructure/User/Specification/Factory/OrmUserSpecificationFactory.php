<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Specification\Factory;

use App\Domain\Common\Specification\SpecificationInterface;
use App\Domain\User\Specification\UserSpecificationFactoryInterface;
use App\Domain\User\ValueObj\Email;
use App\Infrastructure\User\Specification\Orm\UserWithEmail;
use App\Infrastructure\User\Specification\Orm\UserWithUuid;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr;
use Ramsey\Uuid\UuidInterface;

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

    public function createForFindOneWithUuid(UuidInterface $uuid): SpecificationInterface
    {
        return new UserWithUuid($this->expr, $uuid);
    }
}
