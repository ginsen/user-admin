<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObj;

final class Password
{
    const MAX_LENGTH = 255;
    const COST       = 12;

    /** @var string */
    private $hashedPassword;


    /**
     * @param  string   $hashedPassword
     * @return Password
     */
    public static function fromHash(string $hashedPassword): self
    {
        $pass                 = new self();
        $pass->hashedPassword = $hashedPassword;

        return $pass;
    }


    /**
     * @param  string   $plainPassword
     * @return Password
     */
    public static function encode(string $plainPassword): self
    {
        $pass = new self();
        $pass->hash($plainPassword);

        return $pass;
    }


    /**
     * @param  string $plainPassword
     * @return bool
     */
    public function match(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->hashedPassword);
    }


    /**
     * @param string $plainPassword
     */
    private function hash(string $plainPassword): void
    {
        $this->validate($plainPassword);

        $this->hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT, [
            'cost' => self::COST,
        ]);
    }


    /**
     * @param string $raw
     */
    private function validate(string $raw): void
    {
        $validator = Credentials::getPasswordValidator();
        $validator($raw);
    }


    public function __toString(): string
    {
        return $this->hashedPassword;
    }


    public function toStr(): string
    {
        return $this->hashedPassword;
    }


    private function __construct()
    {
    }
}
