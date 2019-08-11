<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\User\ValueObj\DateTime;
use App\Domain\User\ValueObj\Email;
use Assert\Assertion;
use Broadway\Serializer\Serializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UserEmailChanged implements Serializable
{
    /** @var UuidInterface */
    public $uuid;

    /** @var Email */
    public $email;

    /** @var DateTime */
    public $updatedAt;


    public function __construct(UuidInterface $uuid, Email $email, DateTime $updatedAt)
    {
        $this->uuid      = $uuid;
        $this->email     = $email;
        $this->updatedAt = $updatedAt;
    }


    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'uuid'       => $this->uuid->toString(),
            'email'      => $this->email->toStr(),
            'updated_at' => $this->updatedAt->toStr(),
        ];
    }


    /**
     * @param array $data
     * @return UserEmailChanged
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'uuid');
        Assertion::keyExists($data, 'email');

        return new self(
            Uuid::fromString($data['uuid']),
            Email::fromStr($data['email']),
            DateTime::fromStr($data['updated_at'])
        );
    }
}
