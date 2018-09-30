<?php


namespace Common\Auth\Handler;


class Factory
{
    private static $handlers = [];


    /**
     * @param $type
     *
     * @return Handler
     * @throws \ReflectionException
     */
    static function getHandler($type)
    {
        if (isset(static::$handlers[$type])) {
            return static::$handlers[$type];
        }

        foreach (static::handlers() as $handler) {

            if ($handler::getName() == $type) {
                $handleInstance = self::generateHandler($handler);
                static::$handlers[$type] = $handleInstance;
                return $handleInstance;
            }
        }

        return null;
    }


    /**
     * @param $class
     *
     * @return mixed
     * @throws \ReflectionException
     */
    static function generateHandler($class)
    {
        if (is_subclass_of($class, Handler::class)) {

            switch (call_user_func([$class, 'getName'])) {
                case 'facebook':
                    $constructParams = [config('facebook:app_id'), config('facebook:app_secret')];
                    break;

                case 'google':
                    $constructParams = [config('google:client_id'), config('google:client_secret')];
                    break;

                default:
                    $constructParams = null;
            }

            $reflection = new \ReflectionClass($class);

            return call_user_func_array([$reflection, 'newInstance'], $constructParams ?: []);
        } else {

            throw new \InvalidArgumentException("$class is not valid");
        }
    }


    /**
     * @return Handler[]
     */
    static function handlers()
    {
        return [
            Facebook::class,
            Google::class,
            Password::class,
            Id::class
        ];
    }
}