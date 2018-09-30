<?php

namespace Common\Util;


use DateTime;

class DateUtil
{

    static function dateToUnixTimestamp($date)
    {
        if (preg_match('/^\d+$/', $date)) {
            return $date;
        }

        if ($date == null) return "";
        $date = DateTime::createFromFormat('d/m/Y', $date);
        return $date != null ? $date->getTimestamp() : '';
    }

    static function timestampWeekBefore()
    {
        return time() - 604800;
    }


    /**
     * Convert all time stamp formatted element to DateTime-formatted
     * Fields are detected as timestamp ( length = 10 and key contains 'date' )
     * @param $array
     * @param null $ignore which fields to ignore
     * @param array $extra
     * @param bool $includeTime
     */
    static function timeStamp2DateTime(&$array, $ignore = null, $extra = [], $includeTime = false)
    {
        if (!is_array($array)) {
            return;
        }

        foreach ($array as $k => &$v) {

            if($v === null){
                continue;
            }

            if (is_array($v)) {
                self::timeStamp2DateTime($v, $ignore, $extra, $includeTime);
            }

            if ($ignore && in_array($k, $ignore) !== false) {
                continue;
            }
            if (((strpos($k, 'date') !== false) || (in_array($k, $extra))) && preg_match('/^-?\d{0,11}$/', $v)) {
                    $array[$k] = date($includeTime ? 'd/m/Y H:i:s' : 'd/m/Y', intval($v));
            }
        }
    }


    /**
     * Get default format of timestamp
     *
     * @param        $time_stamp
     * @param string $datetimeFormat
     *
     * @return false|string
     */
    static function timeStampToDate($time_stamp, $datetimeFormat = 'd/m/Y H:i')
    {
        $time_stamp = intval($time_stamp);
        return $time_stamp ? date($datetimeFormat, $time_stamp) : null;
    }

    static function get_timestamp_end_day(){
        $date = date('d/m/Y');
        $date = DateTime::createFromFormat('d/m/Y H:i:s', "$date 23:59:59");
        return $date != null ? $date->getTimestamp() : null;
    }

    static function get_timestamp_start_day(){
        $date = date('d/m/Y');
        $date = DateTime::createFromFormat('d/m/Y H:i:s', "$date 00:00:00");
        return $date != null ? $date->getTimestamp() : null;
    }

}