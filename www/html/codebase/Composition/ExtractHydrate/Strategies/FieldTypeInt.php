<?php

namespace SmartHome\Composition\ExtractHydrate\Strategies;

class FieldTypeInt implements ConverterInterface
{
    function convertFromDatabaseDto($value)
    {
        return $value !== null ? intval($value) : null;
    }

    function convertToDatabaseDto($value)
    {
        return $value;
    }
}
