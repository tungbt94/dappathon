<?php namespace Common\Util;

class Helper
{
    public static function encryptpassword($pass)
    {
        return md5(md5($pass));
    }

    public static function cpagerparm($para_need_remove, $suffixctr = null)
    {
        $request = new \Phalcon\Http\Request;
        $pa = $request->getQuery();
        $controller = $pa['_url'];
        unset($pa['_url']);
        ##Remove Item
        $s = explode(',', $para_need_remove);
        foreach ($s as $item) unset($pa["$item"]);
        ## Append Querystring
        $str = '';
        foreach ($pa as $key => $val) {
            if (is_array($val)) {
                foreach ($val as $sitem) $hs .= $key . '[]=' . $sitem . '&';
                $str .= $hs;
            } else $str .= $key . '=' . $val . '&';
        }
        if ($suffixctr == null) $link = $controller . "?" . $str;
        else $link = $suffixctr . "?" . $str;
        $link = rtrim($link, "&");
        return $link;
    }

    public static function paginginfo($rowcount, $limit, $page, $pagelimit = 3)
    {
        if ($page <= 1) $page = 1;
        $totalpage = ceil($rowcount / $limit);
        $startpaging = $page - $pagelimit;
        if ($startpaging <= 1) $startpaging = 1;
        $endpaging = $page + $pagelimit;
        if ($endpaging >= $totalpage) $endpaging = $totalpage;
        if ($endpaging <= $startpaging) $endpaging = $startpaging = 1;
        $paginginfo = [
            "rowcount" => $rowcount,
            "rangepage" => range($startpaging, $endpaging),
            "totalpage" => $totalpage,
            "page" => $page,
            "currentlink" => Helper::cpagerparm("p"),
            "maxpage" => $pagelimit,
            "limit" => $limit
        ];
        return $paginginfo;
    }

    public static function link_view_category($name, $id)
    {
        global $config;
        return $config['media']['host'] . Helper::Cleanurl(Helper::khongdau($name)) . '-c' . $id;
    }

    public static function br2nl($input)
    {
        return preg_replace('/<br(\s+)?\/?>/i', "\n", $input);
    }

    public static function nl2br2($string)
    {
        $string = str_replace([
            "\r\n",
            "\r",
            "\n"
        ], "<br />", $string);
        return $string;
    }

    public static function xss_clean($data)
    {
        if (is_array($data)) $data = array_map([
            self,
            "xss_process"
        ], $data);
        else $data = self::xss_process($data);
        return $data;
    }

    private function xss_process($data)
    {
        // Fix &entity\n;
        $data = str_replace([
            '&amp;',
            '&lt;',
            '&gt;'
        ], [
            '&amp;amp;',
            '&amp;lt;',
            '&amp;gt;'
        ], $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);
        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);
        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);
        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
        do {
            // Remove really unwanted tags
            $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        } while ($old_data !== $data);
        // we are done...
        return $data;
    }

    public static function Cleanurl($text)
    {
        $text = str_replace('-', ' ', $text);
        $text = str_replace(' -', ' ', $text);
        $text = str_replace([
            '&apos;',
            '&quot;'
        ], '', $text);
        $text = preg_replace('/[^a-zA-Z0-9_ -,.]/s', '', $text);
        $text = trim($text);
        $stripped = preg_replace([
            '/\s{2,}/',
            '/[\t\n]/'
        ], ' ', $text);
        $text = strtolower($stripped);
        $text = str_replace(',', ' ', $text);
        $code_entities_match = [
            ' ',
            '--',
            '&quot;',
            '!',
            '@',
            '#',
            '$',
            '%',
            '^',
            '&',
            '*',
            '(',
            ')',
            '_',
            '+',
            '{',
            '}',
            '|',
            ':',
            '"',
            '<',
            '>',
            '?',
            '[',
            ']',
            '\\',
            ';',
            "'",
            ',',
            '.',
            '/',
            '*',
            '+',
            '~',
            '`',
            '='
        ];
        $code_entities_replace = [
            '-',
            '-',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '-',
            '-',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        ];
        $text = str_replace($code_entities_match, $code_entities_replace, $text);
        return $text;
    }

    public static function khongdau($text)
    {
        $marTViet = [
            "à",
            "á",
            "ạ",
            "ả",
            "ã",
            "â",
            "ầ",
            "ấ",
            "ậ",
            "ẩ",
            "ẫ",
            "ă",
            "ằ",
            "ắ",
            "ặ",
            "ẳ",
            "ẵ",
            "è",
            "é",
            "ẹ",
            "ẻ",
            "ẽ",
            "ê",
            "ề",
            "ế",
            "ệ",
            "ể",
            "ễ",
            "ì",
            "í",
            "ị",
            "ỉ",
            "ĩ",
            "ò",
            "ó",
            "ọ",
            "ỏ",
            "õ",
            "ô",
            "ồ",
            "ố",
            "ộ",
            "ổ",
            "ỗ",
            "ơ",
            "ờ",
            "ớ",
            "ợ",
            "ở",
            "ỡ",
            "ù",
            "ú",
            "ụ",
            "ủ",
            "ũ",
            "ư",
            "ừ",
            "ứ",
            "ự",
            "ử",
            "ữ",
            "ỳ",
            "ý",
            "ỵ",
            "ỷ",
            "ỹ",
            "đ",
            "À",
            "Á",
            "Ạ",
            "Ả",
            "Ã",
            "Â",
            "Ầ",
            "Ấ",
            "Ậ",
            "Ẩ",
            "Ẫ",
            "Ă",
            "Ằ",
            "Ắ",
            "Ặ",
            "Ẳ",
            "Ẵ",
            "È",
            "É",
            "Ẹ",
            "Ẻ",
            "Ẽ",
            "Ê",
            "Ề",
            "Ế",
            "Ệ",
            "Ể",
            "Ễ",
            "Ì",
            "Í",
            "Ị",
            "Ỉ",
            "Ĩ",
            "Ò",
            "Ó",
            "Ọ",
            "Ỏ",
            "Õ",
            "Ô",
            "Ồ",
            "Ố",
            "Ộ",
            "Ổ",
            "Ỗ",
            "Ơ",
            "Ờ",
            "Ớ",
            "Ợ",
            "Ở",
            "Ỡ",
            "Ù",
            "Ú",
            "Ụ",
            "Ủ",
            "Ũ",
            "Ư",
            "Ừ",
            "Ứ",
            "Ự",
            "Ử",
            "Ữ",
            "Ỳ",
            "Ý",
            "Ỵ",
            "Ỷ",
            "Ỹ",
            "Đ"
        ];
        $marKoDau = [
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "e",
            "e",
            "e",
            "e",
            "e",
            "e",
            "e",
            "e",
            "e",
            "e",
            "e",
            "i",
            "i",
            "i",
            "i",
            "i",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "u",
            "u",
            "u",
            "u",
            "u",
            "u",
            "u",
            "u",
            "u",
            "u",
            "u",
            "y",
            "y",
            "y",
            "y",
            "y",
            "d",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "E",
            "E",
            "E",
            "E",
            "E",
            "E",
            "E",
            "E",
            "E",
            "E",
            "E",
            "I",
            "I",
            "I",
            "I",
            "I",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "U",
            "U",
            "U",
            "U",
            "U",
            "U",
            "U",
            "U",
            "U",
            "U",
            "U",
            "Y",
            "Y",
            "Y",
            "Y",
            "Y",
            "D"
        ];
        $str = str_replace($marTViet, $marKoDau, $text);
        return $str;

    }

    public static function startsWith($haystack, $needle) // full String - param
    {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
    }

    public static function endsWith($haystack, $needle) //full String - param
    {
        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
    }

    public static function get_client_ip()
    {
        $ipaddress = '';
        if ($_SERVER['HTTP_CLIENT_IP']) $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if ($_SERVER['HTTP_X_FORWARDED_FOR']) $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if ($_SERVER['HTTP_X_FORWARDED']) $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if ($_SERVER['HTTP_FORWARDED_FOR']) $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if ($_SERVER['HTTP_FORWARDED']) $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if ($_SERVER['REMOTE_ADDR']) $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public static function subWords($string, $start, $length)
    {
        $arrstring = explode(" ", $string); //convert string to array
        if (count($arrstring) > $length) {
            $arrsubstring = array_slice($arrstring, $start, $length); //return array with start position and number of word
            $result = implode(" ", $arrsubstring) . "...";// return n word after sub
        } else $result = $string;
        return $result;
    }

    public static function isMobile()
    {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }

    public static function get_youtube_id($input)
    {
        $input = preg_match('~https?://(?:[0-9A-Z-]+\.)?(?:youtu\.be/|youtube(?:-nocookie)?\.com\S*[^\w\s-])([\w-]{11})(?=[^\w-]|$)(?![?=&+%\w.-]*(?:[\'"][^<>]*>|</a>))[?=&+%\w.-]*~ix', $input, $match);
        return $match;
    }

    public static function removeTitle($string, $keyReplace = '-')
    {
        $string = Helper::RemoveSign($string);
        //neu muon de co dau
        $string = trim(preg_replace("/[^A-Za-z0-9.]/i", " ", $string)); // khong dau
        $string = str_replace(" ", "-", $string);
        $string = str_replace("--", "-", $string);
        $string = str_replace("--", "-", $string);
        $string = str_replace("--", "-", $string);
        $string = str_replace("--", "-", $string);
        $string = str_replace("--", "-", $string);
        $string = str_replace("--", "-", $string);
        $string = str_replace("--", "-", $string);
        $string = str_replace($keyReplace, "-", $string);
        $string = strtolower($string);
        return $string;
    }

    public static function RemoveSign($str)
    {
        $coDau = [
            "à",
            "á",
            "ạ",
            "ả",
            "ã",
            "â",
            "ầ",
            "ấ",
            "ậ",
            "ẩ",
            "ẫ",
            "ă",
            "ằ",
            "ắ",
            "ặ",
            "ẳ",
            "ẵ",
            "è",
            "é",
            "ẹ",
            "ẻ",
            "ẽ",
            "ê",
            "ề",
            "ế",
            "ệ",
            "ể",
            "ễ",
            "ì",
            "í",
            "ị",
            "ỉ",
            "ĩ",
            "ò",
            "ó",
            "ọ",
            "ỏ",
            "õ",
            "ô",
            "ồ",
            "ố",
            "ộ",
            "ổ",
            "ỗ",
            "ơ",
            "ờ",
            "ớ",
            "ợ",
            "ở",
            "ỡ",
            "ù",
            "ú",
            "ụ",
            "ủ",
            "ũ",
            "ư",
            "ừ",
            "ứ",
            "ự",
            "ử",
            "ữ",
            "ỳ",
            "ý",
            "ỵ",
            "ỷ",
            "ỹ",
            "đ",
            "À",
            "Á",
            "Ạ",
            "Ả",
            "Ã",
            "Â",
            "Ầ",
            "Ấ",
            "Ậ",
            "Ẩ",
            "Ẫ",
            "Ă",
            "Ằ",
            "Ắ",
            "Ặ",
            "Ẳ",
            "Ẵ",
            "È",
            "É",
            "Ẹ",
            "Ẻ",
            "Ẽ",
            "Ê",
            "Ề",
            "Ế",
            "Ệ",
            "Ể",
            "Ễ",
            "Ì",
            "Í",
            "Ị",
            "Ỉ",
            "Ĩ",
            "Ò",
            "Ó",
            "Ọ",
            "Ỏ",
            "Õ",
            "Ô",
            "Ồ",
            "Ố",
            "Ộ",
            "Ổ",
            "Ỗ",
            "Ơ",
            "Ờ",
            "Ớ",
            "Ợ",
            "Ở",
            "Ỡ",
            "Ù",
            "Ú",
            "Ụ",
            "Ủ",
            "Ũ",
            "Ư",
            "Ừ",
            "Ứ",
            "Ự",
            "Ử",
            "Ữ",
            "Ỳ",
            "Ý",
            "Ỵ",
            "Ỷ",
            "Ỹ",
            "Đ",
            "ê",
            "ù",
            "à"
        ];
        $khongDau = [
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "e",
            "e",
            "e",
            "e",
            "e",
            "e",
            "e",
            "e",
            "e",
            "e",
            "e",
            "i",
            "i",
            "i",
            "i",
            "i",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "u",
            "u",
            "u",
            "u",
            "u",
            "u",
            "u",
            "u",
            "u",
            "u",
            "u",
            "y",
            "y",
            "y",
            "y",
            "y",
            "d",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "E",
            "E",
            "E",
            "E",
            "E",
            "E",
            "E",
            "E",
            "E",
            "E",
            "E",
            "I",
            "I",
            "I",
            "I",
            "I",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "U",
            "U",
            "U",
            "U",
            "U",
            "U",
            "U",
            "U",
            "U",
            "U",
            "U",
            "Y",
            "Y",
            "Y",
            "Y",
            "Y",
            "D",
            "e",
            "u",
            "a"
        ];
        return str_replace($coDau, $khongDau, $str);
    }

    public static function get_first_char($string)
    {
        $words = explode(" ", self::khongdau($string));
        $acronym = "";
        foreach ($words as $w) {
            $acronym .= strtoupper($w[0]);
        }
        return $acronym;
    }

    public static function debug()
    {
        $vars = func_get_args();
        foreach ($vars as $var) {
            echo "<pre>";
            print_r($var);
            echo "</pre>";
        }
        die;
    }

    public static function convert($number)
    {
        $matches = [];
        if (preg_match('/(\d+(?:\.\d+)?)E(-?\d+)/i', $number, $matches)) {
            $number = (float)$matches[1] * pow(10, (int)$matches[2]);
        }

        return $number;
    }

    public static function minutes_ago($date)
    {
        $diff = time() - $date;
        if ($diff > (86400 * 2)) {
            return date('M j/y \a\t h:i', $date);
        } else {
            if ($diff > 86400) {
                return ((int)($diff / 86400)) . ' days ago';
            } else {
                if ($diff > 3600) {
                    return ((int)($diff / 3600)) . ' hours ago';
                } else {
                    if (((int)($diff / 60)) <= 0) {
                        return " 1 minute ago";
                    } else {
                        return (int)($diff / 60) . ' minutes ago';
                    }
                }
            }
        }
    }

    public static function process_date_form($date)
    {
        if ($date) {
            $vote_start = str_replace("-", "", $date . ":00");
            $date = strtotime($vote_start);
        } else {
            $date = null;
        }
        return $date;
    }

}