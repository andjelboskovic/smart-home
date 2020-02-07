<?php

namespace SmartHome\Composition\ExtractHydrate\Strategies;

interface ConverterInterface
{
    function convertFromDatabaseDto($value);

    function convertToDatabaseDto($value);
}
