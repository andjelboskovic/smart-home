<?php

namespace SmartHome\Composition\ExtractHydrate\Strategies;

class FieldTypeFloat implements ConverterInterface
{
    function convertFromDatabaseDto($value)
    {
        return $value !== null ? floatval($value) : null;
    }

    function convertToDatabaseDto($value)
    {
        return $value;
    }
}
