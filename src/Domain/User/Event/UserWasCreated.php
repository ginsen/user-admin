<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\User\ValueObj\Credentials;
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

    /** @var Credentials */
    public $credentials;

    /** @var bool */
    public $active;

    /** @var \DateTime */
    public $createdAt;


    /**
     * UserWasCreated constructor.
     * @param UuidInterface $uuid
     * @param Credentials   $credentials
     * @param bool          $active
     * @param \DateTime     $createdAt
     */
    public function __construct(UuidInterface $uuid, Credentials $credentials, bool $active, \DateTime $createdAt)
    {
        $this->uuid        = $uuid;
        $this->credentials = $credentials;
        $this->active      = $active;
        $this->createdAt   = $createdAt;
    }


    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'uuid'        => $this->uuid->toString(),
            'credentials' => [
                'email'    => $this->credentials->email->toStr(),
                'password' => $this->credentials->password->toStr(),
            ],
            'active'     => ($this->active) ? 'true' : 'false',
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }


    /**
     * @param  array          $data
     * @throws \Exception
     * @return UserWasCreated
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'uuid');
        Assertion::keyExists($data, 'credentials');
        Assertion::keyExists($data, 'active');
        Assertion::keyExists($data, 'created_at');

        return new self(
            Uuid::fromString($data['uuid']),
            new Credentials(
                Email::fromStr($data['credentials']['email']),
                Password::fromHash($data['credentials']['password'])
            ),
            (bool) $data['active'],
            new \DateTime($data['created_at'])
        );
    }
}
