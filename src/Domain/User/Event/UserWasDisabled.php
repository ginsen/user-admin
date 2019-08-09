<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use Assert\Assertion;
use Broadway\Serializer\Serializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UserWasDisabled implements Serializable
{
    /** @var UuidInterface */
    public $uuid;

    /** @var bool */
    public $active;

    /** @var \DateTime */
    public $updatedAt;


    public function __construct(UuidInterface $uuid, bool $active, \DateTime $updatedAt)
    {
        $this->uuid      = $uuid;
        $this->active    = $active;
        $this->updatedAt = $updatedAt;
    }


    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'uuid'       => $this->uuid->toString(),
            'active'     => ($this->active) ? 'true' : 'false',
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }


    /**
     * @param  array      $data
     * @throws \Exception
     * @return self
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'uuid');
        Assertion::keyExists($data, 'active');
        Assertion::keyExists($data, 'updated_at');

        return new self(
            Uuid::fromString($data['uuid']),
            (bool) $data['active'],
            new \DateTime($data['updated_at'])
        );
    }
}
