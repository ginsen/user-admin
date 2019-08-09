<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\Common\Specification\SpecificationInterface;
use Broadway\ReadModel\SerializableReadModel;

interface UserRepositoryInterface
{
    public function getOneOrNull(SpecificationInterface $specification): ?SerializableReadModel;
}
