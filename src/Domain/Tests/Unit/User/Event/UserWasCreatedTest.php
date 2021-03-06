<?php

declare(strict_types=1);

namespace App\Domain\Tests\Unit\User\Event;

use App\Domain\User\Event\UserWasCreated;
use App\Domain\User\ValueObj\BoolObj;
use App\Domain\User\ValueObj\Credentials;
use App\Domain\User\ValueObj\DateTime;
use App\Domain\User\ValueObj\Email;
use App\Domain\User\ValueObj\Password;
use Broadway\Serializer\Serializable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UserWasCreatedTest extends TestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function event_should_be_serializable_instance()
    {
        $uuid        = Uuid::uuid4();
        $dateTime    = DateTime::now();
        $active      = BoolObj::fromBool(true);
        $credentials = new Credentials(
            Email::fromStr('test@test.net'),
            Password::encode('123456')
        );

        $event = new UserWasCreated($uuid, $credentials, $active, $dateTime);

        self::assertInstanceOf(Serializable::class, $event);
    }


    /**
     * @test
     * @throws \Exception
     */
    public function event_should_return_array_when_serialize()
    {
        $uuid        = Uuid::uuid4();
        $dateTime    = DateTime::now();
        $active      = BoolObj::fromBool(true);
        $credentials = new Credentials(
            Email::fromStr('test@test.net'),
            Password::encode('123456')
        );

        $event = new UserWasCreated($uuid, $credentials, $active, $dateTime);
        $data  = $event->serialize();

        $expected = [
            'uuid'       => $uuid->toString(),
            'email'      => $credentials->email->toStr(),
            'password'   => $credentials->password->toStr(),
            'active'     => $active->toStr(),
            'created_at' => $dateTime->toStr(),
        ];

        self::assertSame($expected, $data);
    }


    /**
     * @test
     * @throws \Exception
     */
    public function event_should_build_instance_when_deserialize()
    {
        $uuid        = Uuid::uuid4();
        $dateTime    = DateTime::now();
        $active      = BoolObj::fromBool(true);
        $credentials = new Credentials(
            Email::fromStr('test@test.net'),
            Password::encode('123456')
        );

        $data = [
            'uuid'       => $uuid->toString(),
            'email'      => $credentials->email->toStr(),
            'password'   => $credentials->password->toStr(),
            'active'     => $active->toStr(),
            'created_at' => $dateTime->toStr(),
        ];

        $event = UserWasCreated::deserialize($data);

        self::assertSame($event->uuid->toString(), $uuid->toString());
        self::assertSame($event->email->toStr(), $credentials->email->toStr());
        self::assertSame($event->password->toStr(), $credentials->password->toStr());
        self::assertSame($event->active->toStr(), $active->toStr());
        self::assertSame($event->createdAt->toStr(), $dateTime->toStr());
    }
}
