<?php

namespace App\Util;

use App\Repository\UserRepository;
use Cassandra\Date;
use DateTime;

class DateTimeUtils {

    public static function convertLocalToUTC($dateTime) {
        $timezoneOffset = $_SESSION["timezoneOffset"];

        $result = new DateTime();
        $result->setTimestamp($dateTime->getTimestamp() + $timezoneOffset);
        return $result;
    }

    public static function convertUTCToLocal($dateTime) {
        $timezoneOffset = $_SESSION["timezoneOffset"];

        $result = new DateTime();
        $result->setTimestamp($dateTime->getTimestamp() - $timezoneOffset);
        return $result;
    }

    public static function parseDatabaseDateTime($dateTimeString) {
        return DateTime::createFromFormat("Y-m-d H:i:s", $dateTimeString);
    }

}