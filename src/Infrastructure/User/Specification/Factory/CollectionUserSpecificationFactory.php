<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Specification\Factory;

use App\Domain\Common\Specification\SpecificationInterface;
use App\Domain\User\Specification\UserSpecificationFactoryInterface;
use App\Domain\User\ValueObj\Email;
use App\Infrastructure\User\Specification\Collection\UserWithEmail;
use App\Infrastructure\User\Specification\Collection\UserWithUuid;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ExpressionBuilder;
use Ramsey\Uuid\UuidInterface;

class CollectionUserSpecificationFactory implements UserSpecificationFactoryInterface
{
    /** @var ExpressionBuilder */
    private $expr;


    public function __construct()
    {
        $this->expr = Criteria::expr();
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
