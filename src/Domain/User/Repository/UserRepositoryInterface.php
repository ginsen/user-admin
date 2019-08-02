<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\Common\Specification\SpecificationInterface;
use App\Domain\User\Entity\UserViewInterface;

interface UserRepositoryInterface
{
    public function getOneOrNull(SpecificationInterface $specification): ?UserViewInterface;
}
