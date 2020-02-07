<?php

namespace SmartHome\Composition\ExtractHydrate\Strategies;

class FieldTypeDefault implements ConverterInterface
{
    function convertFromDatabaseDto($value)
    {
        return $value;
    }

    function convertToDatabaseDto($value)
    {
        return $value;
    }
}
