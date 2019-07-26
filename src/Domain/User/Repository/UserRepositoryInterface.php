<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\Common\PersistLayer\PersistLayerInterface;
use App\Domain\Common\Specification\SpecificationInterface;
use App\Domain\User\Entity\UserInterface;

interface UserRepositoryInterface extends PersistLayerInterface
{
    public function getOneOrNull(SpecificationInterface $specification): ?UserInterface;
}
