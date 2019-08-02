<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\User\ValueObj\Email;
use Assert\Assertion;
use Broadway\Serializer\Serializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UserSignedIn implements Serializable
{
    /** @var UuidInterface */
    public $uuid;

    /** @var Email */
    public $email;


    /**
     * UserSignedIn constructor.
     * @param UuidInterface $uuid
     * @param Email         $email
     */
    public function __construct(UuidInterface $uuid, Email $email)
    {
        $this->uuid  = $uuid;
        $this->email = $email;
    }


    /**
     * @param  array        $data
     * @return UserSignedIn
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'uuid');
        Assertion::keyExists($data, 'email');

        return new self(
            Uuid::fromString($data['uuid']),
            Email::fromStr($data['email'])
        );
    }


    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'uuid'  => $this->uuid->toString(),
            'email' => $this->email->toStr(),
        ];
    }
}
