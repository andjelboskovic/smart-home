<?php

namespace SmartHome\Composition\ExtractHydrate\Strategies;

use DateTimeZone;

class FieldTypeTimezone implements ConverterInterface
{
    function convertFromDatabaseDto($value)
    {
        return $value ? new DateTimeZone($value) : null;
    }

    function convertToDatabaseDto($value)
    {
        /** @var DateTimeZone $value */
        return $value ? $value->getName() : null;
    }
}
