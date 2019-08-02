<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\User\ValueObj\Email;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

class EmailType extends Type
{
    const EMAIL = 'email';


    /**
     * @return string
     */
    public function getName(): string
    {
        return static::EMAIL;
    }


    /**
     * @param  array            $fieldDeclaration
     * @param  AbstractPlatform $platform
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return sprintf('varchar(%d)', Email::MAX_LENGTH);
    }


    /**
     * @param  mixed            $value
     * @param  AbstractPlatform $platform
     * @return Email|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Email
    {
        if (null === $value || $value instanceof Email) {
            return $value;
        }

        return Email::fromStr($value);
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

        if ($value instanceof Email) {
            return (string) $value;
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', 'Email']);
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
