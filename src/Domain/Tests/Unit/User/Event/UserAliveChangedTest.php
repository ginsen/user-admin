<?php

declare(strict_types=1);

namespace App\Domain\Tests\Unit\User\Event;

use App\Domain\User\Event\UserAliveChanged;
use App\Domain\User\ValueObj\BoolObj;
use App\Domain\User\ValueObj\DateTime;
use Broadway\Serializer\Serializable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UserAliveChangedTest extends TestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function event_should_be_serializable_instance()
    {
        $uuid     = Uuid::uuid4();
        $dateTime = DateTime::now();
        $active   = BoolObj::fromBool(true);

        $event = new UserAliveChanged($uuid, $active, $dateTime);

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
        $active   = BoolObj::fromBool(true);

        $event = new UserAliveChanged($uuid, $active, $dateTime);
        $data  = $event->serialize();

        $expected = [
            'uuid'       => $uuid->toString(),
            'active'     => $active->toStr(),
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
        $dateTime = DateTime::now();
        $active   = BoolObj::fromBool(true);

        $data = [
            'uuid'       => $uuid->toString(),
            'active'     => $active->toStr(),
            'updated_at' => $dateTime->toStr(),
        ];

        $event = UserAliveChanged::deserialize($data);

        self::assertSame($event->uuid->toString(), $uuid->toString());
        self::assertSame($event->active->toStr(), $active->toStr());
        self::assertSame($event->updatedAt->toStr(), $dateTime->toStr());
    }
}
