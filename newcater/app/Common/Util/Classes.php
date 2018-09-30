<?php namespace Common\Util;

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 3/9/2017
 * Time: 9:17 AM
 */
class Classes
{

    /**
     * @param $sourceArrayObject
     * @param $destination
     *
     * @return array
     */
    public static function mapListObject(array $sourceArrayObject, $destination)
    {
        if (!$sourceArrayObject) {
            return [];
        }
        $final_data = [];
        foreach ($sourceArrayObject as $sourceObject) {
            if (!is_array($sourceObject)) {
                throw new \InvalidArgumentException('All element must be array');
            }
            $final_data[] = static::mapObject($sourceObject, $destination);
        }
        return $final_data;
    }

    /**
     * @param $sourceObject
     * @param $destination
     *
     * @return $destination
     */
    public static function mapObject($sourceObject, $destination)
    {
        if ($sourceObject == null || $destination == null) {
            return null;
        }
        if ($sourceObject instanceof $destination) {
            return $sourceObject;
        }
        is_array($sourceObject) && $sourceObject = (object)$sourceObject;

        if (!is_object($sourceObject)) {
            return null;
        }

        if (is_string($destination)) {
            $destination = new $destination();
        }
        $sourceReflection = new \ReflectionObject($sourceObject);
        $destinationReflection = new \ReflectionObject($destination);
        $sourceProperties = $sourceReflection->getProperties();

        foreach ($sourceProperties as $sourceProperty) {
            if (!property_exists(get_class($destination), $sourceProperty->getName())) {
                continue;
            }

            $sourceProperty->setAccessible(true);
            $name = $sourceProperty->getName();
            $value = $sourceProperty->getValue($sourceObject);
            if ($destinationReflection->hasProperty($name)) {

                $camel_case = str_replace('_', '', $name);
                if (method_exists($destination, 'set' . $camel_case)) { // Support setter
                    $methodFunc = "set$camel_case";
                    $destination->$methodFunc($value);

                } else {
                    $propDest = $destinationReflection->getProperty($name);
                    $propDest->setAccessible(true);
                    $propDest->setValue($destination, $value);
                }
            } else {
                $destination->$name = $value;
            }
        }
        return $destination;
    }

    /**
     * @param $class
     *
     * @internal param $model
     * @return null|array
     */
    public static function getAllProperties($class)
    {
        if ($class == null) {
            return null;
        }
        if (is_string($class)) {
            $class = new $class();
        }

        $reflection = new \ReflectionObject($class);
        $properties = $reflection->getProperties();
        $properties = array_map(function (\ReflectionProperty $ref_property) {
            return $ref_property->getName();
        }, $properties);
        return $properties;
    }

//
//    /**
//     * @param $clazz
//     * @param array $ignore
//     * @return array
//     */
//    public static function getProperties($clazz, $ignore = ['del_flag'])
//    {
//        if (is_string($clazz) && !empty($clazz)) {
//            $map_props = array_keys(get_class_vars($clazz));
//            return array_diff($map_props, $ignore);
//        }
//        return [];
//    }


    /**
     * @param       $prefix
     * @param array $arr
     * @param       $as_prefix
     *
     * @return array
     */
    public static function selectAsSQL($prefix, array $arr, $as_prefix)
    {
        return array_map(function ($item) use ($prefix, $as_prefix) {
            return "$prefix$item AS $as_prefix$item";
        }, $arr);
    }


    /**
     * @param $arr
     * @param $value
     */
    public static function removeArrayValue(&$arr, $value)
    {
        if (($key = array_search($value, $arr)) !== false) {
            unset($arr[$key]);
        }
    }


    /**
     * @param $arr
     */
    public static function removeNull(&$arr, $replace = 0)
    {
        $arr = array_map(function ($item) use ($replace) {
            return $item === null ? $replace : $item;
        }, $arr);
    }


    /**
     * @param $class
     *
     * @return bool
     * @throws \ReflectionException
     */
    public static function isAbstract($class)
    {
        return (new \ReflectionClass($class))->isAbstract();
    }

}