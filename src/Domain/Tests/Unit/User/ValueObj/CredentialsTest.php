<?php

declare(strict_types=1);

namespace App\Domain\Tests\Unit\User\ValueObj;

use App\Domain\User\ValueObj\Credentials;
use App\Domain\User\ValueObj\Email;
use App\Domain\User\ValueObj\Password;
use PHPUnit\Framework\TestCase;

class CredentialsTest extends TestCase
{
    /**
     * @test
     */
    public function credentials_should_be_valid_instance(): void
    {
        $credentials = new Credentials(
            Email::fromStr('foo@bar.com'),
            Password::encode('123456')
        );

        self::assertInstanceOf(Credentials::class, $credentials);
    }


    /**
     * @test
     */
    public function credentials_should_get_validator_fun(): void
    {
        $fun = Credentials::getPasswordValidator('123456');

        self::assertSame('123456', $fun('123456'));
    }


    /**
     * @test
     * @dataProvider dataProviderExceptions
     * @param string $password
     * @param mixed  $firstPass
     */
    public function credentials_should_throw_exception_when_fail_validator(string $password, $firstPass): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $fun = Credentials::getPasswordValidator($firstPass);
        $fun($password);
    }


    /**
     * @return array
     */
    public function dataProviderExceptions(): array
    {
        return [
            ['123', null],
            ['123456', '777888'],
        ];
    }
}
