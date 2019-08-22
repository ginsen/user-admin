<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\User\ValueObj\BoolObj;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\DBAL\Types\ConversionException;

class BoolObjType extends BooleanType
{
    /**
     * {@inheritdoc}
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return $value;
        }

        if ($value instanceof BoolObj) {
            return $platform->convertBooleansToDatabaseValue($value->toBool());
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', 'BoolObj']);
    }


    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value || $value instanceof BoolObj) {
            return $value;
        }

        return BoolObj::fromBool($platform->convertFromBoolean($value));
    }
}
