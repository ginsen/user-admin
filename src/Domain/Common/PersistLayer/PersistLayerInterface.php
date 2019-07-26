<?php

declare(strict_types=1);

namespace App\Domain\Common\PersistLayer;

interface PersistLayerInterface
{
    public function save($obj, bool $flush = true, bool $clear = false): void;

    public function update($obj, bool $flush = true): void;

    public function remove($obj, $flush = true): void;
}
