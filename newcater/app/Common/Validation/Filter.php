<?php

namespace Common\Validation;


use Common\Util\DateUtil;
use Phalcon\Filter as PhalconFilter;

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 4/5/2017
 * Time: 9:27 AM
 */
class Filter extends PhalconFilter
{

    const FILTER_LIMIT = 'm';

    const FILTER_PAGE = 'p';

    const FILTER_ORDER = 'o';

    const FILTER_QUERY = 'q';

    const FILTER_FIELDS = 'f';

    const FILTER_DMY_SINCE = 'DMY_SINCE';

    const FILTER_DMY_TO = 'DMY_TO';

    const FILTER_CONVERT_FIELDS = 'array_fields';

    const FILTER_COMMA_SEPERATOR_AND_UNIQ = 'comma_seperator';

    const FILTER_EXPLODE_COMMA = 'explode_comma';

    const FILTER_UNVIETNAMESE = 'vietnamese';

    const FILTER_NULL = 'null';

    const FILTER_MIN1 = 'min1';

    const FILTER_JWT_AUTHORIZATION = 'jwt';

    const FILTER_PRICE = 'price';

    const FILTER_MULTI_ID = 'multi_id';

    const FILTER_MULTI_PRICE = 'multi_price';

    const FILTER_MULTI_NUMBER = 'multi_number';

    const FILTER_VN_WORD = 'vn_word';

    const FILTER_CODE = 'code';

    const FILTER_PHONE = 'phone';

    const FILTER_CURRENCY = 'currency';

    const FILTER_DATE_TO_TIMESTAMP = 'timestamp';

    const FILTER_DATE_TO_TIMESTAMP_FROM = 'timestamp_from';

    const FILTER_DATE_TO_TIMESTAMP_TO = 'timestamp_to';

    const FILTER_QUANTITY = 'quantity';

    const DEFAULT_LIMIT = 25;


    function __construct()
    {
        $this->initialize();
    }


    protected function initialize()
    {

        $number_min_1_func = function ($value) {
            $value = intval($value);
            return $value <= 0 ? 1 : $value;
        };

        $field_filter_function = function ($field) {
            $field = preg_replace('/[^a-zA-Z0-9_,]/', '', $field);
            $field = trim($field, ',');
            return $field;
        };


        $this->add(static::FILTER_PAGE, $number_min_1_func);


        $this->add(static::FILTER_MIN1, $number_min_1_func);


        // LIMIT should be 1 in case being null
        $this->add(static::FILTER_LIMIT, function ($value) {
            $value = intval($value);
            return $value <= 0 ? static::DEFAULT_LIMIT : $value;
        });


        $this->add(static::FILTER_ORDER, function ($field) {
            $field = preg_replace('/[^a-zA-Z0-9_-]/', '', $field);
            return $field;
        });


        $this->add(static::FILTER_QUERY, function ($value) {
            return $value;
        });

        $this->add(static::FILTER_NULL, function ($value) {
            if ($value == null) {
                return null;
            }
            return $value;
        });

        $this->add('int', function ($value) {
            return intval($value);
        });

        $this->add(static::FILTER_FIELDS, $field_filter_function);


        $this->add(static::FILTER_JWT_AUTHORIZATION, function ($jwt) {
            $jwt = preg_replace('/[^a-zA-Z0-9-_.=\/]/', '', $jwt);
            return $jwt;
        });

        $this->add(static::FILTER_CURRENCY, function ($input) {
            $input = preg_replace('/[^0-9.]/', '', $input);
            $amount = doubleval($input);
            return $amount;
        });

        /*
         * Hàm này remove các dấu phảy đi nếu như client gửi lên
         */
        $this->add(static::FILTER_PRICE, function ($price) {
            if (isset($price)) {
                if (count(explode('.', $price)) > 1) {
                    return round(preg_replace('/[a-z,]/i', '', $price), 2);
                } else {
                    return floatval(preg_replace('/[a-z,]/i', '', $price));
                }
            }
            return 0;
        });

        $this->add(static::FILTER_MULTI_PRICE, function ($params) {
            $keys = $params['keys'];
            $data = $params['data'];
            foreach ($keys as $value) {
                if (isset($data[$value])) {
                    if (count(explode('.', $data[$value])) > 1) {
                        $data[$value] = round(preg_replace('/[a-zA-Z,]\s+/i', '', $data[$value]), 2);
                    } else {
                        $data[$value] = doubleval(preg_replace('/\D+/', '', $data[$value]));
                    }
                } else {
                    $data[$value] = 0;
                }
            }
            return $data;
        });

        /*
         * Set null cho các khóa ngoại nếu gửi lên = 0
         */
        $this->add(static::FILTER_MULTI_ID, function ($params) {
            $keys = $params['keys'];
            $data = $params['data'];
            foreach ($keys as $item) {
                $data[$item] = !$data[$item] ? null : $data[$item];
            }
            return $data;
        });

        $this->add(static::FILTER_CONVERT_FIELDS, function ($fields) {
            if (is_array($fields)) {
                return $fields;

            } elseif (is_string($fields)) {
                $fields = explode(',', preg_replace('/[^a-zA-Z0-9_,]/', '', $fields));
                return $fields;

            }
            return null;
        });

        $this->add(static::FILTER_COMMA_SEPERATOR_AND_UNIQ, function ($fields) {
            if (is_array($fields)) {
                return $fields;

            } elseif (is_string($fields)) {
                $fields = explode(',', preg_replace('/\s+/', '', $fields));
                $fields = array_unique($fields);
                return $fields;

            }
            return [];
        });

        $this->add(static::FILTER_EXPLODE_COMMA, function ($fields) {
            if (is_array($fields)) {
                return $fields;

            } elseif (is_string($fields)) {
                $fields = explode(',', preg_replace('/[^a-zA-Z0-9_,\/]/', '', $fields));
                return $fields;

            }
            return null;
        });

        $this->add(static::FILTER_JWT_AUTHORIZATION, function ($jwt) {
            $jwt = preg_replace('/[^a-zA-Z0-9-_.=\/]/', '', $jwt);
            return $jwt;
        });

        $this->add(static::FILTER_DATE_TO_TIMESTAMP, function ($date) {
            $date = \DateTime::createFromFormat('d/m/Y', $date);
            return $date != null ? $date->getTimestamp() : null;
        });

        $this->add(static::FILTER_DATE_TO_TIMESTAMP_FROM, function ($date) {
            $date = \DateTime::createFromFormat('d/m/Y H:i:s', "$date 00:00:00");
            return $date != null ? $date->getTimestamp() : null;
        });

        $this->add(static::FILTER_DATE_TO_TIMESTAMP_TO, function ($date) {
            $date = \DateTime::createFromFormat('d/m/Y H:i:s', "$date 23:59:59");
            return $date != null ? $date->getTimestamp() : null;
        });

        $this->add(static::FILTER_UNVIETNAMESE, function ($str) {
            return static::vn_str_filter($str);
        });

        $this->add(static::FILTER_VN_WORD, function ($str) {
            $str = preg_replace('/[^ 0-9a-zA-Z_ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽễềếểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ]/', '', $str);
            $str = trim($str);
            return $str;
        });

        $this->add(static::FILTER_CODE, function ($str) {
            $str = strtoupper($str);
            $str = preg_replace('/(\s)|(\W)/', '', $str);
            return $str;
        });

        $this->add(static::FILTER_PHONE, function ($str) {
            $str = strval($str);
            strpos($str, '0') === 0 && $str = sprintf("0%s", ltrim($str, '0'));
            $plus = (strpos($str, '+') === 0) ? '+' : '';
            $str = preg_replace('/\D/', '', $str);
            $str = "$plus$str";
            return $str;
        });

        $this->add(static::FILTER_QUANTITY, function ($params) {
            $keys = $params['keys'];
            $data = $params['data'];
            foreach ($keys as $value) {
                if (isset($data[$value])) {
                    $data[$value] = doubleval(preg_replace('/[a-z,]/i', '', $data[$value]));

                } else {
                    $data[$value] = 0;
                }
            }
            return $data;
        });


        $this->add(static::FILTER_DMY_SINCE, function ($since) {
            !$since && $since = time();
            $since = DateUtil::dateToUnixTimestamp($since);
            $since = strtotime("midnight", $since);
            return $since;
        });

        $this->add(static::FILTER_DMY_TO, function ($to) {
            !$to && $to = time();
            $to = DateUtil::dateToUnixTimestamp($to);
            $to = strtotime("tomorrow", $to) - 1;
            return $to;
        });
    }


    /**
     * Bo dau tieng Viet
     *
     * @param $str
     *
     * @return mixed
     */
    static function vn_str_filter($str)
    {

        $unicode = array(

            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',

            'd' => 'đ',

            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',

            'i' => 'í|ì|ỉ|ĩ|ị',

            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',

            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',

            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',

            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',

            'D' => 'Đ',

            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',

            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',

            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',

            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',

            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',

        );

        foreach ($unicode as $nonUnicode => $uni) {

            $str = preg_replace("/($uni)/i", $nonUnicode, $str);

        }

        return $str;

    }
}