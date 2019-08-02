<?php

declare(strict_types=1);

namespace App\Domain\User\Entity\AggregateRoot;

use App\Domain\User\Event\UserSignedIn;
use App\Domain\User\Exception\Credentials\InvalidCredentialsException;
use App\Domain\User\ValueObj\Password;

/**
 * @method Password getPassword
 */
trait UserSignInTrait
{
    public function signIn(string $plainPassword): void
    {
        if (!$this->isActive()) {
            throw new InvalidCredentialsException('User inactive.');
        }

        $match = $this->getPassword()->match($plainPassword);

        if (!$match) {
            throw new InvalidCredentialsException('Invalid credentials entered.');
        }

        $this->apply(new UserSignedIn($this->uuid, $this->email));
    }
}
