<?php


if (!function_exists('server_name')) {

    function server_name()
    {
        try {
            return empty($_SERVER['HTTP_HOST']) ? $_SERVER['SERVER_NAME'] : $_SERVER['HTTP_HOST'];

        } catch (Exception $e) {
            return '';

        }
    }
}

if (!function_exists('path_from')) {
    function path_from($path, $suffix = '', $fromModuleConfig = true)
    {
        if ($fromModuleConfig) {
            $path = module_config($path);
        }

        if ($path) {
            $path = str_replace('$base', BASE_PATH, $path);
            $path = str_replace('$app', APP_PATH, $path);
            $suffix && $path = $path . DIRECTORY_SEPARATOR . $suffix;

        } else {

            $path = BASE_PATH;
        }

        return preg_replace('/[\/\\\]+/', DIRECTORY_SEPARATOR, $path);
    }
}


if (!function_exists('measure_time')) {
    function measure_time(Closure $func)
    {
        $start = microtime(true);
        $func();

        return microtime(true) - $start;
    }
}


if (!function_exists('provider')) {
    /**
     * Calls the default Dependency Injection container.
     *
     * @param  mixed
     *
     * @return mixed
     */
    function provider(...$input)
    {
        $default = Phalcon\Di::getDefault();

        $args = func_get_args();

        if (empty($args)) {
            return $default;
        }

        if ($default->has($args[0])) {
            return call_user_func_array([$default, 'get'], $args);

        }

        return false;
    }
}

if (!function_exists('config')) {
    /**
     * Calls the default Dependency Injection container.
     *
     * @param $path
     * @param null $default
     *
     * @return mixed
     *
     */
    function config($path = null, $default = null)
    {
        $di = Phalcon\Di::getDefault();
        /** @var \Phalcon\Config $config */
        $config = $di->getShared('config');

        if ($config == null) {
            return $default;
        }

        if ($path == null) {
            return $config->toArray();
        }

        $ps = explode(':', $path);
        $res = $config;
        foreach ($ps as &$p) {

            if (is_object($res)) {
                $res = $res->$p;

            } elseif (is_array($res)) {
                $res = $res[$p];
            }
        }
        return ($res === null) ? $default : $res;
    }
}


if (!function_exists('module_config')) {
    /**
     * Calls the default Dependency Injection container.
     *
     * @param $path
     * @param null $default
     *
     * @return mixed
     *
     */
    function module_config($path = null, $default = null)
    {
        $di = Phalcon\Di::getDefault();

        /** @var \Phalcon\Config $config */
        $common = $di->getShared('config')->{'module'};

        if ($common == null) {
            return $default;
        }

        if ($path == null) {
            return $common;
        }

        $ps = explode(':', $path);
        $res = $common;
        foreach ($ps as &$p) {

            if (is_object($res)) {
                $res = $res->$p;

            } elseif (is_array($res)) {
                $res = $res[$p];
            }
        }
        return ($res === null) ? $default : $res;
    }
}


if (!function_exists('remote_address')) {
    /**
     * Dump function, replace var_dump() => for debug
     *
     * @return
     */
    function remote_address()
    {
        return $_SERVER['HTTP_CLIENT_IP'] ?: ($_SERVER['HTTP_X_FORWARDE‌​D_FOR'] ?: $_SERVER['REMOTE_ADDR']);
    }
}


if (!function_exists('actual_request')) {
    function actual_request()
    {
        return (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }
}


if (!function_exists('origin_url')) {
    function origin_url()
    {
        return (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    }
}


if (!function_exists('dump_to_str')) {
    function dump_to_str(... $input)
    {
        ob_start();
        var_dump($input);
        $res = ob_get_contents();
        ob_end_clean();
        return $res;
    }
}


if (!function_exists('array_from')) {

    /**
     * @param $data
     *
     * @return array
     */
    function array_from($data)
    {
        return json_decode(json_encode($data), true);
    }

}


if (!function_exists('get_class_simple')) {

    /**
     * @param $object object|string
     *
     * @return string
     *
     */
    function get_class_simple($object)
    {
        if (is_object($object)) {
            $object = get_class($object);
        }
        $split = explode('\\', $object);
        return $split ? $split[count($split) - 1] : null;
    }

}

if (!function_exists('sql_encoded_value')) {

    /**
     * @param $value
     * @param bool $add_quotes
     *
     * @return string
     */
    function sql_encoded_value($value, $add_quotes = false)
    {
        $value = str_replace("\\", "\\\\", $value);
        $value = str_replace("'", '\\\'', $value);
        if ($add_quotes) {
            return "'$value'";
        }
        return $value;
    }

}

if (!function_exists('enableCacheControl')) {

    /**
     * @param $options
     */
    function enableCacheControl($options = 'max-age=86400, public')
    {
        header('Cache-Control: ' . $options);
    }

}


if (!function_exists('d')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param  mixed
     *
     * @return void
     */
    function d(...$ags)
    {
        call_user_func_array('dump', func_get_args());
        die(1);
    }
}


if (!function_exists('dump')) {
    /**
     * Dump the passed variables without end the script.
     *
     * @param  mixed
     *
     * @return void
     */
    function dump(...$ags)
    {
        $trace = debug_backtrace();
        $rootPath = dirname(dirname(__FILE__));
        $index = ($trace[1]['function'] == 'd') ? 1 : 0;
        $file = str_replace($rootPath, '', $trace[$index]['file']);
        $line = $trace[$index]['line'];
        echo $file . ' ' . $line;

        array_map(function ($x) {
            $string = (new Phalcon\Debug\Dump(null, true))->variable($x);
            echo(PHP_SAPI == 'cli' ? strip_tags($string) . PHP_EOL : $string);
        }, func_get_args());
    }
}


if (!function_exists('under_score')) {
    /**
     * @param  string
     *
     * @return string
     */
    function under_score($input)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }
}


if (!function_exists('start_with')) {
    /**
     * @param $haystack
     * @param $needle
     *
     * @return string
     */
    function start_with($haystack, $needle)
    {
        if ($haystack == null) {
            return false;
        }

        return (substr($haystack, 0, strlen($needle)) == $needle);
    }
}


