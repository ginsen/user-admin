<?php

declare(strict_types=1);

namespace App\Domain\Tests\Unit\User\Entity;

use App\Domain\User\Entity\User;
use App\Domain\User\Exception\Email\EmailAlreadyExistException;
use App\Domain\User\Specification\UniqueEmailSpecificationInterface;
use App\Domain\User\ValueObj\BoolObj;
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
     * {@inheritdoc}
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
        $uuid        = Uuid::uuid4();
        $active      = BoolObj::fromBool(true);
        $credentials = new Credentials(
            Email::fromStr('test@test.net'),
            Password::encode('123456')
        );

        $user = User::create($uuid, $credentials, $active, $this);

        self::assertInstanceOf(User::class, $user);
        self::assertSame($uuid, $user->getUuid());
        self::assertSame($credentials->email, $user->getEmail());
        self::assertSame($credentials->password, $user->getPassword());
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

        $uuid        = Uuid::uuid4();
        $active      = BoolObj::fromBool(true);
        $credentials = new Credentials(
            Email::fromStr('test@test.net'),
            Password::encode('123456')
        );

        User::create($uuid, $credentials, $active, $this);
    }


    /**
     * @test
     * @throws \Exception
     */
    public function it_should_sign_in_user()
    {
        $uuid        = Uuid::uuid4();
        $active      = BoolObj::fromBool(true);
        $credentials = new Credentials(
            Email::fromStr('sign.in@test.net'),
            Password::encode('123456')
        );

        $user = User::create($uuid, $credentials, $active, $this);
        $user->signIn('123456');

        self::assertInstanceOf(User::class, $user);
    }


    /**
     * @test
     * @throws \Exception
     */
    public function it_should_change_email_user()
    {
        $uuid        = Uuid::uuid4();
        $active      = BoolObj::fromBool(true);
        $credentials = new Credentials(
            Email::fromStr('foo@test.net'),
            Password::encode('123456')
        );

        $user = User::create($uuid, $credentials, $active, $this);
        $user->changeEmail(Email::fromStr('bar@test.net'), $this);

        self::assertSame('bar@test.net', $user->getEmail()->toStr());
    }


    /**
     * @test
     * @throws \Exception
     */
    public function it_should_change_alive_status_user()
    {
        $uuid        = Uuid::uuid4();
        $active      = BoolObj::fromBool(true);
        $credentials = new Credentials(
            Email::fromStr('active@test.net'),
            Password::encode('123456')
        );

        $user = User::create($uuid, $credentials, $active, $this);

        $user->changeActive(BoolObj::fromBool(false));
        self::assertFalse($user->isActive());

        $user->changeActive(BoolObj::fromBool(true));
        self::assertTrue($user->isActive());
    }


    /**
     * {@inheritdoc}
     */
    public function isUnique(Email $email): bool
    {
        if (!$this->isUnique) {
            throw new EmailAlreadyExistException('Email already registered.');
        }

        return $this->isUnique;
    }
}
