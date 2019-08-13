<?php

declare(strict_types=1);

namespace App\Domain\Tests\Unit\User\Event;

use App\Domain\User\Event\UserAliveChanged;
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

        $event = new UserAliveChanged($uuid, true, $dateTime);

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

        $event = new UserAliveChanged($uuid, $active, $dateTime);
        $data  = $event->serialize();

        $expected = [
            'uuid'       => $uuid->toString(),
            'active'     => $active,
            'updated_at' => $dateTime->toStr(),
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

        $data = [
            'uuid'       => $uuid->toString(),
            'active'     => $active,
            'updated_at' => $dateTime->toStr(),
        ];

        $event = UserAliveChanged::deserialize($data);

        self::assertEquals($event->uuid, $uuid);
        self::assertEquals($event->active, $active);
        self::assertEquals($event->updatedAt, $dateTime);
    }
}
