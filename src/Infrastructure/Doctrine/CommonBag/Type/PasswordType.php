<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\CommonBag\Type;

use App\Domain\User\ValueObj\Password;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

class PasswordType extends Type
{
    const NAME = 'password';


    /**
     * @return string
     */
    public function getName(): string
    {
        return static::NAME;
    }


    /**
     * @param  array            $fieldDeclaration
     * @param  AbstractPlatform $platform
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return sprintf('varchar(%d)', Password::MAX_LENGTH);
    }


    /**
     * @param  mixed            $value
     * @param  AbstractPlatform $platform
     * @return Password|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Password
    {
        if (null === $value || $value instanceof Password) {
            return $value;
        }

        return Password::fromHash($value);
    }


    /**
     * @param  mixed               $value
     * @param  AbstractPlatform    $platform
     * @throws ConversionException
     * @return string|null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return $value;
        }

        if ($value instanceof Password) {
            return $value->toStr();
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', 'Password']);
    }


    /**
     * @param  AbstractPlatform $platform
     * @return bool
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
