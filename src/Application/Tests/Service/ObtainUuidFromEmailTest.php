<?php

declare(strict_types=1);

namespace App\Application\Tests\Service;

use App\Application\Service\ObtainUuidFromEmail;
use App\Domain\User\Service\UserFinder;
use App\Domain\User\ValueObj\DateTime;
use App\Domain\User\ValueObj\Email;
use App\Domain\User\ValueObj\Password;
use App\Infrastructure\User\Projection\UserView;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ObtainUuidFromEmailTest extends TestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function it_should_return_uuid_by_email()
    {
        $email      = Email::fromStr('foo@bar.net');
        $userFinder = $this->getUserFinderFake($email);
        $obtainUuid = new ObtainUuidFromEmail($userFinder);

        $uuid = $obtainUuid->get($email);

        self::assertInstanceOf(UuidInterface::class, $uuid);
    }


    /**
     * @param  Email      $email
     * @throws \Exception
     * @return UserFinder
     */
    protected function getUserFinderFake(Email $email): UserFinder
    {
        $userView = $this->createUserView($email);

        $userFinder = m::mock(UserFinder::class);
        $userFinder->shouldReceive('findByEmail')->andReturn($userView);

        return $userFinder;
    }


    /**
     * @param  Email      $email
     * @throws \Exception
     * @return UserView
     */
    protected function createUserView(Email $email): UserView
    {
        $uuid      = Uuid::uuid4();
        $password  = Password::encode('123456');
        $createdAt = DateTime::now();

        $userView = UserView::deserialize([
            'uuid'       => $uuid->toString(),
            'email'      => $email->toStr(),
            'password'   => $password->toStr(),
            'active'     => true,
            'created_at' => $createdAt->toStr(),
        ]);

        return $userView;
    }
}
