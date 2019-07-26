<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObj;

use Assert\Assertion;

class Email
{
    const MAX_LENGTH = 255;

    /** @var string */
    private $email;


    /**
     * @param  string $email
     * @return Email
     */
    public static function fromStr(string $email): self
    {
        Assertion::email($email, 'Not a valid email');

        $obj        = new static();
        $obj->email = $email;

        return $obj;
    }


    public function __toString(): string
    {
        return $this->email;
    }


    public function toStr(): string
    {
        return $this->email;
    }


    private function __construct()
    {
    }
}
