<?php

declare(strict_types=1);

namespace App\Domain\User\Specification;

use App\Domain\Common\Specification\SpecificationInterface;
use App\Domain\User\ValueObj\Email;
use Ramsey\Uuid\UuidInterface;

interface UserSpecificationFactoryInterface
{
    public function createForFindOneWithEmail(Email $email): SpecificationInterface;

    public function createForFindOneWithUuid(UuidInterface $uuid): SpecificationInterface;
}
