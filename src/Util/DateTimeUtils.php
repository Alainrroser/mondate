<?php
    
    namespace App\Util;
    
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
        
        public static function extractDate($dateTime) {
            $string = $dateTime->format("Y-m-d");
            $string .= " 00:00:00";
            return DateTime::createFromFormat("Y-m-d H:i:s", $string, $dateTime->getTimeZone());
        }
        
    }