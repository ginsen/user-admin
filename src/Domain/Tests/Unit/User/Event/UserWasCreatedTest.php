<?php

declare(strict_types=1);

namespace App\Domain\Tests\Unit\User\Event;

use App\Domain\User\Event\UserWasCreated;
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
        $uuid     = Uuid::uuid4();
        $dateTime = DateTime::now();
        $active   = true;
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
        $uuid     = Uuid::uuid4();
        $dateTime = DateTime::now();
        $active   = true;
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
            'active'     => $active,
            'created_at' => $dateTime->toStr(),
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
        $dateTime = DateTime::now();
        $active   = true;
        $credentials = new Credentials(
            Email::fromStr('test@test.net'),
            Password::encode('123456')
        );

        $data = [
            'uuid'       => $uuid->toString(),
            'email'      => $credentials->email->toStr(),
            'password'   => $credentials->password->toStr(),
            'active'     => $active,
            'created_at' => $dateTime->toStr(),
        ];

        $event = UserWasCreated::deserialize($data);

        self::assertEquals($event->uuid, $uuid);
        self::assertEquals($event->email, $credentials->email);
        self::assertEquals($event->password, $credentials->password);
        self::assertEquals($event->active, $active);
        self::assertEquals($event->createdAt, $dateTime);
    }
}
