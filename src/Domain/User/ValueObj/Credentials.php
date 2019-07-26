<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObj;

use Assert\Assertion;

class Credentials
{
    const MIN_PASSWORD_LENGTH = 5;


    /** @var Email */
    public $email;

    /** @var Password */
    public $password;


    /**
     * Credentials constructor.
     * @param Email    $email
     * @param Password $password
     */
    public function __construct(Email $email, Password $password)
    {
        $this->email    = $email;
        $this->password = $password;
    }


    /**
     * @param  string|null $password
     * @return \Closure
     */
    public static function getPasswordValidator(string $password = null): \Closure
    {
        return function ($value) use ($password) {
            Assertion::minLength(
                $value,
                static::MIN_PASSWORD_LENGTH,
                sprintf('The password is very sort, minimum length %d', static::MIN_PASSWORD_LENGTH)
            );

            if (null !== $password) {
                Assertion::eq($value, $password, 'The passwords are not equals');
            }

            return $value;
        };
    }
}
