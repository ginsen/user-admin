<?php

declare(strict_types=1);

namespace App\Domain\Tests\Unit\User\Entity;

use App\Domain\User\Entity\User;
use App\Domain\User\Specification\UniqueEmailSpecificationInterface;
use App\Domain\User\ValueObj\Credentials;
use App\Domain\User\ValueObj\DateTime;
use App\Domain\User\ValueObj\Email;
use App\Domain\User\ValueObj\Password;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UserTest extends TestCase implements UniqueEmailSpecificationInterface
{
    protected $isUnique = true;


    /**
     * @test
     */
    public function it_should_create_one_user_instance()
    {
        $uuid = Uuid::uuid4();
        $credentials = new Credentials(
            Email::fromStr('test@test.net'),
            Password::encode('123456')
        );

        $user = User::create($uuid, $credentials, $this);

        self::assertInstanceOf(User::class, $user);
        self::assertEquals($uuid, $user->getUuid());
        self::assertEquals($credentials->email, $user->getEmail());
        self::assertEquals($credentials->password, $user->getPassword());
        self::assertTrue($user->isActive());
    }


    /**
     * {@inheritDoc}
     */
    public function isUnique(Email $email): bool
    {
        return $this->isUnique;
    }
}