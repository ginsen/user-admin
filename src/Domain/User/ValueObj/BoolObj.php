<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObj;

use Assert\Assertion;

class BoolObj
{
    /** @var bool */
    private $value;


    /**
     * @param  bool    $value
     * @return BoolObj
     */
    public static function fromBool(bool $value): self
    {
        return self::create($value);
    }


    /**
     * @param  int     $value
     * @return BoolObj
     */
    public static function fromTinyInt(int $value): self
    {
        Assertion::greaterThan($value, -1, 'Value must be 0 or 1');
        Assertion::lessThan($value, 2, 'Value must be 0 or 1');

        return self::create((bool) $value);
    }


    /**
     * @param  string  $value
     * @return BoolObj
     */
    public static function fromStr(string $value): self
    {
        Assertion::regex($value, '~(true|false|yes|no|1|0)~i', "Value don't has valid format");

        $bool = (bool) (preg_match('~(true|yes|1)~i', $value));

        return self::create($bool);
    }


    /**
     * @param  bool    $value
     * @return BoolObj
     */
    private static function create(bool $value): self
    {
        $self        = new self();
        $self->value = $value;

        return $self;
    }


    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toStr();
    }


    /**
     * @return string
     */
    public function toStr(): string
    {
        return ($this->value) ? 'true' : 'false';
    }


    /**
     * @return string
     */
    public function toHuman(): string
    {
        return ($this->value) ? 'Yes' : 'No';
    }


    /**
     * @return int
     */
    public function toTinyInt(): int
    {
        return ($this->value) ? 1 : 0;
    }


    /**
     * @return bool
     */
    public function toBool(): bool
    {
        return $this->value;
    }


    private function __construct()
    {
    }
}
