<?php

declare(strict_types=1);

namespace App\Domain\Tests\Unit\User\ValueObj;

use App\Domain\User\ValueObj\Password;
use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{
    private $plainText = '123456';


    /**
     * @test
     */
    public function password_should_encode_plain_text(): void
    {
        $password = Password::encode($this->plainText);

        $hash = (string) $password;
        self::assertTrue(60 == \strlen($hash));
        self::assertTrue($password->match($this->plainText));
    }


    /**
     * @test
     */
    public function password_should_set_from_hash(): void
    {
        $hash     = '$2y$12$9g1LWkXjpyUISJGq5s3vreD0gej6PjGlsXaGjpoW9sFT.knzYk6IG';
        $password = Password::fromHash($hash);

        self::assertTrue($password->match($this->plainText));
    }
}
