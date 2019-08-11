<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObj;

use App\Domain\User\Exception\DateTime\DateTimeException;

class DateTime
{
    const FORMAT = 'Y-m-d\TH:i:s.uP';

    /** @var \DateTimeImmutable */
    private $dateTime;


    /**
     * @return DateTime
     */
    public static function now(): self
    {
        return self::create();
    }


    /**
     * @param  string   $dateTime
     * @return DateTime
     */
    public static function fromStr(string $dateTime): self
    {
        return self::create($dateTime);
    }


    /**
     * @param  string   $dateTime
     * @return DateTime
     */
    private static function create(string $dateTime = ''): self
    {
        $self = new self();

        try {
            $self->dateTime = new \DateTimeImmutable($dateTime);
        } catch (\Exception $e) {
            throw new DateTimeException($e);
        }

        return $self;
    }


    /**
     * @return string
     */
    public function toStr(): string
    {
        return $this->dateTime->format(self::FORMAT);
    }


    /**
     * @return \DateTimeImmutable
     */
    public function toDateTime(): \DateTimeImmutable
    {
        return $this->dateTime;
    }


    private function __construct()
    {
    }
}
