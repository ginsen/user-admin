<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Entity\UserInterface;
use App\Domain\User\ValueObj\Email;

interface UserFinderInterface
{
    public function findByEmail(Email $email): ? UserInterface;
}
