<?php

namespace SmartHome\Composition\ExtractHydrate\Strategies;

class FieldTypeBool implements ConverterInterface
{
    function convertFromDatabaseDto($value)
    {
        return $value === null ? null : ($value ? true : false);
    }

    function convertToDatabaseDto($value)
    {
        return $value === null ? null : ($value ? 1 : 0);
    }
}
