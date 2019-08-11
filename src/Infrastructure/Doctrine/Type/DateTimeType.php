<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\User\Exception\DateTime\DateTimeException;
use App\Domain\User\ValueObj\DateTime;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeImmutableType;

class DateTimeType extends DateTimeImmutableType
{
    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'DateTime';
    }


    /**
     * {@inheritdoc}
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return $value;
        }

        if ($value instanceof DateTime) {
            return $value->toDateTime()->format($platform->getDateTimeFormatString());
        }

        if ($value instanceof \DateTimeImmutable) {
            return $value->format($platform->getDateTimeFormatString());
        }

        throw ConversionException::conversionFailedInvalidType(
            $value,
            $this->getName(),
            ['null', DateTime::class]
        );
    }


    /**
     * {@inheritdoc}
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value || $value instanceof DateTime) {
            return $value;
        }

        try {
            $dateTime = DateTime::fromStr($value);
        } catch (DateTimeException $e) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getDateTimeFormatString()
            );
        }

        return $dateTime;
    }
}
