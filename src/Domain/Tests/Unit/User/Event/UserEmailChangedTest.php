<?php

declare(strict_types=1);

namespace App\Domain\Tests\Unit\User\Event;

use App\Domain\User\Event\UserEmailChanged;
use App\Domain\User\ValueObj\DateTime;
use App\Domain\User\ValueObj\Email;
use Broadway\Serializer\Serializable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UserEmailChangedTest extends TestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function event_should_be_serializable_instance()
    {
        $uuid     = Uuid::uuid4();
        $email    = Email::fromStr('test@test.net');
        $dateTime = DateTime::now();

        $event = new UserEmailChanged($uuid, $email, $dateTime);

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
        $dateTime = DateTime::now();

        $event = new UserEmailChanged($uuid, $email, $dateTime);
        $data  = $event->serialize();

        $expected = [
            'uuid'       => $uuid->toString(),
            'email'      => $email->toStr(),
            'updated_at' => $dateTime->toStr(),
        ];

        self::assertSame($expected, $data);
    }


    /**
     * @test
     * @throws \Exception
     */
    public function event_should_build_instance_when_deserialize()
    {
        $uuid     = Uuid::uuid4();
        $email    = Email::fromStr('test@test.net');
        $dateTime = DateTime::now();

        $data = [
            'uuid'       => $uuid->toString(),
            'email'      => $email->toStr(),
            'updated_at' => $dateTime->toStr(),
        ];

        $event = UserEmailChanged::deserialize($data);

        self::assertSame($event->uuid->toString(), $uuid->toString());
        self::assertSame($event->email->toStr(), $email->toStr());
        self::assertSame($event->updatedAt->toStr(), $dateTime->toStr());
    }
}
