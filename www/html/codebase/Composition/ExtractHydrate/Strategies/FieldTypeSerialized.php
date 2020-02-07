<?php

namespace SmartHome\Composition\ExtractHydrate\Strategies;

class FieldTypeSerialized implements ConverterInterface
{
    function convertFromDatabaseDto($value)
    {
        return $value !== null ? unserialize($value) : null;
    }

    function convertToDatabaseDto($value)
    {
        return !empty($value) ? serialize($value) : $value;
    }
}
