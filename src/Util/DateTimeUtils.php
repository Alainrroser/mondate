<?php

namespace App\Util;

use App\Repository\UserRepository;
use Cassandra\Date;
use DateTime;
use DateTimeZone;

class DateTimeUtils {

    public static function convertLocalToUTC($dateTime) {
        $format = "Y-m-d H:i:s";
        $localString = $dateTime->format($format);
        $local = DateTime::createFromFormat($format, $localString, new DateTimeZone($_SESSION["timezone"]));

        $result = clone $local;
        $result->setTimeZone(new DateTimeZone("UTC"));

        return $result;
    }

    public static function convertUTCToLocal($dateTime) {
        $format = "Y-m-d H:i:s";
        $utcString = $dateTime->format($format);
        $utc = DateTime::createFromFormat($format, $utcString, new DateTimeZone("UTC"));

        $result = clone $utc;
        $result->setTimeZone(new DateTimeZone($_SESSION["timezone"]));

        return $result;
    }

    public static function parseDatabaseDateTime($dateTimeString) {
        return DateTime::createFromFormat("Y-m-d H:i:s", $dateTimeString);
    }

}