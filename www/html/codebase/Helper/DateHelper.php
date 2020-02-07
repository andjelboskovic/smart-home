<?php

namespace SmartHome\Helper;

use DateInterval;
use DateTime;
use DateTimeZone;

class DateHelper
{
    const MONTH_NAMES = [
        'january',
        'february',
        'march',
        'april',
        'may',
        'june',
        'july',
        'august',
        'september',
        'october',
        'november',
        'december'
    ];

    const MONTH_FORMAT = 'm';
    const DAY_OF_MONTH_FORMAT = 'd';
    const DATETIME_TIMESTAMP_MYSQL_FORMAT = "Y-m-d H:i:s";
    const DATE_HUMAN_FORMAT = "F j, Y, g:i a";
    const MONTH_DAY_YEAR = "F j, Y";
    const DATE_MYSQL_FORMAT = "Y-m-d";
    const BOOKING_DATE_GET_PARAMETER_FORMAT = "m-d-Y";
    const TIME_MYSQL_FORMAT = "H:i:s";
    const YEAR_MYSQL_FORMAT = "Y";
    const MONTH_DAY_MYSQL_FORMAT = "m-d";
    const DAY_MONTH_YEAR_FORMAT = "D, M d";

    public static function parseDate(
        string $dateString,
        string $format = self::DATE_MYSQL_FORMAT,
        ?DateTimeZone $timeZone = null
    ): ?DateTime {
        $date = DateTime::createFromFormat($format, $dateString, $timeZone);
        return $date ? $date->setTime(0, 0, 0) : null;
    }

    public static function parseDateTime(
        string $dateString,
        string $format = self::DATETIME_TIMESTAMP_MYSQL_FORMAT,
        ?DateTimeZone $timeZone = null
    ): ?DateTime {
        $date = DateTime::createFromFormat($format, $dateString, $timeZone);
        return $date ?: null;
    }

    public static function parseTimestamp(int $timestamp): DateTime
    {
        return (new DateTime())->setTimestamp($timestamp);
    }

    public static function parseTime(
        string $datetimeString,
        string $format = self::DATETIME_TIMESTAMP_MYSQL_FORMAT,
        ?DateTimeZone $timeZone = null
    ): ?DateTime {
        return DateTime::createFromFormat($format, $datetimeString, $timeZone) ?: null;
    }

    public static function dateRangeDays(DateTime $startDate, int $days): array
    {
        $days--;
        $endDate = (clone $startDate)->add(new DateInterval("P{$days}D"));
        return self::dateRange($startDate, $endDate);
    }

    public static function datePlusMinutes(DateTime $startDate, int $numberOfMinutes): DateTime
    {
        if ($numberOfMinutes === 0) {
            return (clone $startDate);
        }

        if ($numberOfMinutes < 0) {
            $numberOfMinutes = -$numberOfMinutes;
            return (clone $startDate)->sub(new DateInterval("PT{$numberOfMinutes}M"));
        }

        return (clone $startDate)->add(new DateInterval("PT{$numberOfMinutes}M"));
    }

    public static function datePlusHours(DateTime $startDate, int $numberOfHours): DateTime
    {
        if ($numberOfHours === 0) {
            return (clone $startDate);
        }

        if ($numberOfHours < 0) {
            $numberOfHours = -$numberOfHours;
            return (clone $startDate)->sub(new DateInterval("PT{$numberOfHours}H"));
        }

        return (clone $startDate)->add(new DateInterval("PT{$numberOfHours}H"));
    }

    public static function datePlusDays(DateTime $startDate, int $days): DateTime
    {
        if ($days === 0) {
            return (clone $startDate);
        }

        if ($days < 0) {
            $days = -$days;
            return (clone $startDate)->sub(new DateInterval("P{$days}D"));
        }

        return (clone $startDate)->add(new DateInterval("P{$days}D"));
    }

    public static function datePlusMonths(DateTime $startDate, int $numberOfMonths): DateTime
    {
        if ($numberOfMonths === 0) {
            return (clone $startDate);
        }

        if ($numberOfMonths < 0) {
            $numberOfMonths = -$numberOfMonths;
            return (clone $startDate)->sub(new DateInterval("P{$numberOfMonths}M"));
        }

        return (clone $startDate)->add(new DateInterval("P{$numberOfMonths}M"));
    }

    public static function dateMinusMinutes(DateTime $startDate, int $numberOfMinutes): DateTime
    {
        return self::datePlusMinutes($startDate, -$numberOfMinutes);
    }

    public static function dateMinusHours(DateTime $startDate, int $numberOfHours): DateTime
    {
        return self::datePlusHours($startDate, -$numberOfHours);
    }

    public static function dateMinusDays(DateTime $startDate, int $numberOfDays): DateTime
    {
        return self::datePlusDays($startDate, -$numberOfDays);
    }

    public static function dateMinusMonths(DateTime $startDate, int $numberOfMonths): DateTime
    {
        return self::datePlusMonths($startDate, -$numberOfMonths);
    }

    public static function dateTimePlusTime(
        DateTime $datetime,
        float $hours,
        float $minutes = 0,
        int $seconds = 0
    ): DateTime {
        $seconds = round($seconds + $minutes * 60 + $hours * 3600);
        $datetime = clone $datetime;

        if ($seconds === 0) {
            return $datetime;
        }

        if ($seconds < 0) {
            $seconds = -$seconds;
            return $datetime->sub(new DateInterval("PT{$seconds}S"));
        }

        return $datetime->add(new DateInterval("PT{$seconds}S"));
    }

    /**
     * @param DateTime[] $dates
     * @param string $format
     * @return string[]
     */
    public static function convertDatesToStrings(array $dates, string $format = self::DATE_MYSQL_FORMAT): array
    {
        return array_map(function (DateTime $forDate) use ($format) {
            return $forDate->format($format);
        }, $dates);
    }

    public static function today(): DateTime
    {
        return (new DateTime())->setTime(0, 0, 0);
    }

    public static function getUTCTimeZone(): DateTimeZone
    {
        return new DateTimeZone("UTC");
    }

    public static function format(DateTime $date, string $format = self::DATE_MYSQL_FORMAT): string
    {
        return $date->format($format);
    }
}
