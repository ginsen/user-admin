<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\Common\ReadModel\ReadModelInterface;
use App\Domain\Common\Specification\SpecificationInterface;
use App\Domain\User\Entity\UserViewInterface;

interface UserReadModelInterface extends ReadModelInterface
{
    public function getOneOrNull(SpecificationInterface $specification): ?UserViewInterface;
}
