<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Specification\Collection;

use App\Infrastructure\Doctrine\Specification\CollectionSpecification;
use Doctrine\Common\Collections\ExpressionBuilder;
use Ramsey\Uuid\UuidInterface;

class UserWithUuid extends CollectionSpecification
{
    /** @var UuidInterface */
    private $uuid;

    public function __construct(ExpressionBuilder $expr, UuidInterface $uuid)
    {
        $this->uuid = $uuid;

        parent::__construct($expr);
    }


    public function getConditions()
    {
        return $this->expr->eq('uuid', $this->uuid);
    }
}
