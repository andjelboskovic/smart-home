<?php

namespace SmartHome\Composition\ExtractHydrate\Strategies;

class FieldTypeObject implements ConverterInterface
{
    function convertFromDatabaseDto($value)
    {
        return $value !== null ? json_decode($value) : null;
    }

    function convertToDatabaseDto($value)
    {
        return $value !== null ? json_encode($value) : null;
    }
}
