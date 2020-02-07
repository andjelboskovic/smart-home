<?php

namespace SmartHome\Composition\ExtractHydrate\Strategies;

use DateTime;
use DateTimeZone;
use SmartHome\Helper\DateHelper;

class FieldTypeDatetime implements ConverterInterface
{
    function convertFromDatabaseDto($value)
    {
        return !empty($value) ? DateTime::createFromFormat(DateHelper::DATETIME_TIMESTAMP_MYSQL_FORMAT, $value) : null;
    }

    function convertToDatabaseDto($value)
    {
        if (!empty($value)) {
            /** @var DateTime $value */
            $value->setTimezone(new DateTimeZone('UTC'));
            return $value->format(DateHelper::DATETIME_TIMESTAMP_MYSQL_FORMAT);
        }
        return $value;
    }
}
