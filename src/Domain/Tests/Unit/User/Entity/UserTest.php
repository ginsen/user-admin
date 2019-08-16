<?php

declare(strict_types=1);

namespace App\Domain\Tests\Unit\User\Entity;

use App\Domain\User\Entity\User;
use App\Domain\User\Exception\Email\EmailAlreadyExistException;
use App\Domain\User\Specification\UniqueEmailSpecificationInterface;
use App\Domain\User\ValueObj\Credentials;
use App\Domain\User\ValueObj\Email;
use App\Domain\User\ValueObj\Password;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UserTest extends TestCase implements UniqueEmailSpecificationInterface
{
    /** @var bool */
    protected $isUnique;


    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->isUnique = true;
    }


    /**
     * @test
     * @throws \Exception
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
     * @test
     * @throws \Exception
     */
    public function it_should_return_exception_if_email_is_not_unique()
    {
        $this->isUnique = false;
        $this->expectException(EmailAlreadyExistException::class);

        $uuid = Uuid::uuid4();
        $credentials = new Credentials(
            Email::fromStr('test@test.net'),
            Password::encode('123456')
        );

        User::create($uuid, $credentials, $this);
    }


    /**
     * @test
     * @throws \Exception
     */
    public function it_should_sign_in_user()
    {
        $uuid = Uuid::uuid4();
        $credentials = new Credentials(
            Email::fromStr('sign.in@test.net'),
            Password::encode('123456')
        );

        $user = User::create($uuid, $credentials, $this);
        $user->signIn('123456');

        self::assertInstanceOf(User::class, $user);
    }


    /**
     * @test
     * @throws \Exception
     */
    public function it_should_change_email_user()
    {
        $uuid = Uuid::uuid4();
        $credentials = new Credentials(
            Email::fromStr('foo@test.net'),
            Password::encode('123456')
        );

        $user = User::create($uuid, $credentials, $this);
        $user->changeEmail(Email::fromStr('bar@test.net'), $this);

        self::assertEquals('bar@test.net', $user->getEmail()->toStr());
    }


    /**
     * @test
     * @throws \Exception
     */
    public function it_should_change_alive_status_user()
    {
        $uuid = Uuid::uuid4();
        $credentials = new Credentials(
            Email::fromStr('active@test.net'),
            Password::encode('123456')
        );

        $user = User::create($uuid, $credentials, $this);

        $user->changeActive(false);
        self::assertFalse($user->isActive());

        $user->changeActive(true);
        self::assertTrue($user->isActive());
    }


    /**
     * {@inheritDoc}
     */
    public function isUnique(Email $email): bool
    {
        if (!$this->isUnique) {
            throw new EmailAlreadyExistException('Email already registered.');
        }

        return $this->isUnique;
    }
}