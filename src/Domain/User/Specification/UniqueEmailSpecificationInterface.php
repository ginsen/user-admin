<?php

declare(strict_types=1);

namespace App\Domain\User\Specification;

use App\Domain\User\ValueObj\Email;

interface UniqueEmailSpecificationInterface
{
    public function isUnique(Email $email): bool;
}
