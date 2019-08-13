<?php

declare(strict_types=1);

namespace App\Domain\Tests\Unit\User\Event;

use App\Domain\User\Event\UserSignedIn;
use App\Domain\User\ValueObj\Email;
use Broadway\Serializer\Serializable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UserSignedInTest extends TestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function event_should_be_serializable_instance()
    {
        $uuid     = Uuid::uuid4();
        $email    = Email::fromStr('test@test.net');

        $event = new UserSignedIn($uuid, $email);

        self::assertInstanceOf(Serializable::class, $event);
    }


    /**
     * @test
     * @throws \Exception
     */
    public function event_should_return_array_when_serialize()
    {
        $uuid     = Uuid::uuid4();
        $email    = Email::fromStr('test@test.net');

        $event = new UserSignedIn($uuid, $email);
        $data  = $event->serialize();

        $expected = [
            'uuid'  => $uuid->toString(),
            'email' => $email->toStr(),
        ];

        self::assertEquals($expected, $data);
    }


    /**
     * @test
     * @throws \Exception
     */
    public function event_should_build_instance_when_deserialize()
    {
        $uuid     = Uuid::uuid4();
        $email    = Email::fromStr('test@test.net');

        $data = [
            'uuid'       => $uuid->toString(),
            'email'      => $email->toStr(),
        ];

        $event = UserSignedIn::deserialize($data);

        self::assertEquals($event->uuid, $uuid);
        self::assertEquals($event->email, $email);
    }
}