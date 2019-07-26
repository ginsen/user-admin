<?php

declare(strict_types=1);

namespace App\Domain\Common\Specification;

interface SpecificationInterface
{
    public function getConditions();

    public function getParameters(): array;

    public function getTypes(): array;

    public function andX(self $specification): self;

    public function orX(self $specification): self;
}
