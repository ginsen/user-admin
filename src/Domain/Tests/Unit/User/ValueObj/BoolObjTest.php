<?php

declare(strict_types=1);

namespace App\Domain\Tests\Unit\User\ValueObj;

use App\Domain\User\ValueObj\BoolObj;
use PHPUnit\Framework\TestCase;

class BoolObjTest extends TestCase
{
    /**
     * @test
     * @dataProvider getStringToCreate
     * @param bool   $expected
     * @param string $value
     */
    public function it_should_build_instance_from_string($expected, $value): void
    {
        $bool = BoolObj::fromStr($value);

        self::assertSame($expected, $bool->toBool());
    }


    /**
     * @test
     * @dataProvider getIntToCreate
     * @param bool $expected
     * @param int  $value
     */
    public function it_should_build_instance_from_integer($expected, $value): void
    {
        $bool = BoolObj::fromTinyInt($value);

        self::assertSame($expected, $bool->toBool());
    }

    public function it_should_build_instance_from_bool(): void
    {
        $boolTrue = BoolObj::fromBool(true);

        self::assertTrue($boolTrue->toBool());
        self::assertSame('Yes', $boolTrue->toHuman());
        self::assertSame('true', $boolTrue->toStr());
        self::assertSame(1, $boolTrue->toTinyInt());

        $boolFalse = BoolObj::fromBool(false);

        self::assertFalse($boolFalse->toBool());
        self::assertSame('No', $boolFalse->toHuman());
        self::assertSame('false', $boolFalse->toStr());
        self::assertSame(0, $boolFalse->toTinyInt());
    }


    /**
     * @return array
     */
    public function getStringToCreate(): array
    {
        return [
            [true, 'true'],
            [true, 'True'],
            [true, 'Yes'],
            [true, '1'],

            [false, 'false'],
            [false, 'FALSE'],
            [false, 'NO'],
            [false, '0'],
        ];
    }


    /**
     * @return array
     */
    public function getIntToCreate(): array
    {
        return [
            [true, 1],
            [false, 0],
        ];
    }
}
