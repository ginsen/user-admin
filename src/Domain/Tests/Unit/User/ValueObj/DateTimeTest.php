<?php

declare(strict_types=1);

namespace App\Domain\Tests\Unit\User\ValueObj;

use App\Domain\User\ValueObj\DateTime;
use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{
    /**
     * @test
     */
    public function datetime_should_be_build_by_valid_datetime(): void
    {
        $dateTime = DateTime::now();

        self::assertInstanceOf(\DateTimeImmutable::class, $dateTime->toDateTime());
    }
}
