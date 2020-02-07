<?php

namespace SmartHome\Composition\ExtractHydrate\Strategies;

class FieldTypeArray implements ConverterInterface
{
    function convertFromDatabaseDto($value)
    {
        return $value !== null ? json_decode($value, true) : null;
    }

    function convertToDatabaseDto($value)
    {
        return $value !== null ? json_encode($value) : null;
    }
}
