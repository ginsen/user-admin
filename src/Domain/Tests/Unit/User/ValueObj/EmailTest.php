<?php

declare(strict_types=1);

namespace App\Domain\Tests\Unit\User\ValueObj;

use App\Domain\User\ValueObj\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    /**
     * @test
     */
    public function email_should_throw_exception_when_set_invalid_param(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Email::fromStr('foo');
    }


    /**
     * @test
     */
    public function email_should_be_build_by_valid_email(): void
    {
        $email = Email::fromStr('foo@bar.com');

        self::assertSame('foo@bar.com', $email->toStr());
        self::assertSame('foo@bar.com', (string) $email);
    }
}
