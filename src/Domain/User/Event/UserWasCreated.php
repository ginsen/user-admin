<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\User\ValueObj\BoolObj;
use App\Domain\User\ValueObj\Credentials;
use App\Domain\User\ValueObj\DateTime;
use App\Domain\User\ValueObj\Email;
use App\Domain\User\ValueObj\Password;
use Assert\Assertion;
use Broadway\Serializer\Serializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UserWasCreated implements Serializable
{
    /** @var UuidInterface */
    public $uuid;

    /** @var Email */
    public $email;

    /** @var Password */
    public $password;

    /** @var BoolObj */
    public $active;

    /** @var DateTime */
    public $createdAt;


    /**
     * UserWasCreated constructor.
     * @param UuidInterface $uuid
     * @param Credentials   $credentials
     * @param BoolObj       $active
     * @param DateTime      $createdAt
     */
    public function __construct(UuidInterface $uuid, Credentials $credentials, BoolObj $active, DateTime $createdAt)
    {
        $this->uuid        = $uuid;
        $this->email       = $credentials->email;
        $this->password    = $credentials->password;
        $this->active      = $active;
        $this->createdAt   = $createdAt;
    }


    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'uuid'       => $this->uuid->toString(),
            'email'      => $this->email->toStr(),
            'password'   => $this->password->toStr(),
            'active'     => $this->active->toStr(),
            'created_at' => $this->createdAt->toStr(),
        ];
    }


    /**
     * @param  array          $data
     * @return UserWasCreated
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'uuid');
        Assertion::keyExists($data, 'email');
        Assertion::keyExists($data, 'password');
        Assertion::keyExists($data, 'active');
        Assertion::keyExists($data, 'created_at');

        return new self(
            Uuid::fromString($data['uuid']),
            new Credentials(
                Email::fromStr($data['email']),
                Password::fromHash($data['password'])
            ),
            BoolObj::fromStr($data['active']),
            DateTime::fromStr($data['created_at'])
        );
    }
}
