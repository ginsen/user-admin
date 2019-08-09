<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\User\Exception\Credentials\InvalidCredentialsException;
use App\Domain\User\Service\UserFinder;
use App\Domain\User\ValueObj\Email;
use Ramsey\Uuid\UuidInterface;

class ObtainUuidFromEmail
{
    /** @var UserFinder */
    private $userFinder;


    /**
     * ObtainUuidFromEmail constructor.
     * @param UserFinder $finderByEmail
     */
    public function __construct(UserFinder $finderByEmail)
    {
        $this->userFinder = $finderByEmail;
    }


    public function get(Email $email): UuidInterface
    {
        $userView = $this->userFinder->findByEmail($email);

        if (null === $userView) {
            throw new InvalidCredentialsException('Invalid credentials entered.');
        }

        if (!$userView->isActive()) {
            throw new InvalidCredentialsException('User disabled.');
        }

        return $userView->getUuid();
    }
}