<?php

namespace SmartHome\Composition\ExtractHydrate\Strategies;

use DateTime;
use DateTimeZone;
use SmartHome\Helper\DateHelper;

class FieldTypeDate implements ConverterInterface
{
    function convertFromDatabaseDto($value)
    {
        $value = !empty($value) ? DateTime::createFromFormat(DateHelper::DATE_MYSQL_FORMAT, $value) : null;
        if ($value) {
            $value->setTime(0, 0, 0);
        }
        return $value;
    }

    function convertToDatabaseDto($value)
    {
        if (!empty($value)) {
            /** @var DateTime $value */
            $value->setTimezone(new DateTimeZone('UTC'));
            return $value->format(DateHelper::DATE_MYSQL_FORMAT);
        }
        return $value;
    }
}
