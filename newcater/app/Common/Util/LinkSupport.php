<?php namespace Common\Util;

namespace Cafe\Backend\Library;
class LinkSupport
{
    ///////// Renew makelink
    public static function maketypecategory($item)
    {
        return BASEURI . str_replace("ex_", "", $item['type']) . "/" . self::cleanstring(self::convert_vi_to_en($item['name'])) . "_c" . $item['_id'] . ".html";
    }

    public static function makethemactic($type, $item)
    {
        return BASEURI . str_replace("ex_", "", $type) . "/" . self::cleanstring(self::convert_vi_to_en($item['name'])) . "_mt" . $item['_id'] . ".html";
    }

    public static function maketheexam($type, $item)
    {
        return BASEURI . str_replace("ex_", "", $type) . "/" . self::cleanstring(self::convert_vi_to_en($item['name'])) . "_ex" . $item['_id'] . ".html";
    }

    public static function makelevel($item, $level)
    {
        return BASEURI . str_replace("ex_", "", $item['type']) . "/" . self::cleanstring(self::convert_vi_to_en($item['name'])) . "_c" . $item['_id'] . ".html?lv=" . $level;
    }

    public static function makethetest($type)
    {
        return BASEURI . $type . '/thetest';
    }

    public static function makefulltest($item, $step)
    {
        return BASEURI . "thetest/" . self::cleanstring(self::convert_vi_to_en($item['name'])) . "_fulltest$step" . $item['_id'] . ".html";
    }

    public static function imagemedia($path)
    {
        $p = "http://cms.ielts-fighter.com" . str_replace("//", "/", BASEURI . $path);

        return $p;
    }

    public static function audiomedia($path)
    {
        $p = "http://cms.ielts-fighter.com" . str_replace("//", "/", BASEURI . $path);

        return $p;
    }

    public static function convert_vi_to_en($str)
    {
        if (!$str) return false;
        $unicode = [
            'a' => [
                'á',
                'à',
                'ả',
                'ã',
                'ạ',
                'ă',
                'ắ',
                'ặ',
                'ằ',
                'ẳ',
                'ẵ',
                'â',
                'ấ',
                'ầ',
                'ẩ',
                'ẫ',
                'ậ'
            ],
            'A' => [
                'Á',
                'À',
                'Ả',
                'Ã',
                'Ạ',
                'Ă',
                'Ắ',
                'Ặ',
                'Ằ',
                'Ẳ',
                'Ẵ',
                'Â',
                'Ấ',
                'Ầ',
                'Ẩ',
                'Ẫ',
                'Ậ'
            ],
            'd' => ['đ'],
            'D' => ['Đ'],
            'e' => [
                'é',
                'è',
                'ẻ',
                'ẽ',
                'ẹ',
                'ê',
                'ế',
                'ề',
                'ể',
                'ễ',
                'ệ'
            ],
            'E' => [
                'É',
                'È',
                'Ẻ',
                'Ẽ',
                'Ẹ',
                'Ê',
                'Ế',
                'Ề',
                'Ể',
                'Ễ',
                'Ệ'
            ],
            'i' => [
                'í',
                'ì',
                'ỉ',
                'ĩ',
                'ị'
            ],
            'I' => [
                'Í',
                'Ì',
                'Ỉ',
                'Ĩ',
                'Ị'
            ],
            'o' => [
                'ó',
                'ò',
                'ỏ',
                'õ',
                'ọ',
                'ô',
                'ố',
                'ồ',
                'ổ',
                'ỗ',
                'ộ',
                'ơ',
                'ớ',
                'ờ',
                'ở',
                'ỡ',
                'ợ'
            ],
            'O' => [
                'Ó',
                'Ò',
                'Ỏ',
                'Õ',
                'Ọ',
                'Ô',
                'Ố',
                'Ồ',
                'Ổ',
                'Ỗ',
                'Ộ',
                'Ơ',
                'Ớ',
                'Ờ',
                'Ở',
                'Ỡ',
                'Ợ'
            ],
            'u' => [
                'ú',
                'ù',
                'ủ',
                'ũ',
                'ụ',
                'ư',
                'ứ',
                'ừ',
                'ử',
                'ữ',
                'ự'
            ],
            'U' => [
                'Ú',
                'Ù',
                'Ủ',
                'Ũ',
                'Ụ',
                'Ư',
                'Ứ',
                'Ừ',
                'Ử',
                'Ữ',
                'Ự'
            ],
            'y' => [
                'ý',
                'ỳ',
                'ỷ',
                'ỹ',
                'ỵ'
            ],
            'Y' => [
                'Ý',
                'Ỳ',
                'Ỷ',
                'Ỹ',
                'Ỵ'
            ],
            '-' => [
                ' ',
                '&quot;',
                '.',
                '-–-'
            ]
        ];
        foreach ($unicode as $nonUnicode => $uni) {
            foreach ($uni as $value) $str = @str_replace($value, $nonUnicode, $str);
            $str = preg_replace("/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'| |\"|\&|\#|\[|\]|~|$|_/", "-", $str);
            $str = preg_replace("/-+-/", "-", $str);
            $str = preg_replace("/^\-+|\-+$/", "", $str);
        }
        $str = str_replace("-", " ", $str);

        return $str;
    }

    public static function cleanstring($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }
}

?>