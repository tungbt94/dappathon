<?php namespace Common\Util;

use Phalcon\Di as DI;

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 3/9/2017
 * Time: 9:17 AM
 */
class Pql
{
    /**
     * @param $parameters
     * @param $col
     * @param $value
     *
     * @return mixed
     */
    static function paramsAddConditions($parameters, $col, $value)
    {
        if ($parameters != null) {
            if (array_key_exists('conditions', $parameters)) {
                $conditions = $parameters['conditions'];
                if (empty($conditions)) {
                    $conditions = $col . " = " . $value;
                } else {
                    $conditions .= " AND " . $col . " = " . $value;
                }
                $parameters['conditions'] = $conditions;
            }
        }
        return $parameters;
    }

    /**
     * @param $fields
     * @param $clazz
     *
     * @return null|string
     */
    static function validateFields($fields, $clazz)
    {
        $match_fields = '';
        if ($fields != null) {
            if (is_string($fields)) {
                $fields = explode(',', $fields);
            }
            if (is_array($fields)) {
                foreach ($fields as $f) {
                    if (property_exists($clazz, $f)) {
                        $match_fields .= ',' . trim($f);
                    };
                }
            }
        }
        if (empty($match_fields)) {
            $match_fields = null;
        } else {
            $match_fields = trim($match_fields, ',');
        }
        return $match_fields;
    }


    /**
     * @param \Phalcon\Mvc\Model\Query\Builder $builder
     *
     * @return mixed
     */
    static function getRaw($builder)
    {
        $intermediate = $builder->getQuery()->parse();
        $dialect = DI::getDefault()->get('db')->getDialect();
        $sql = $dialect->select($intermediate);
        return $sql;
    }


    /**
     * @param        $records
     * @param        $parent_prefix
     * @param        $child_prefix
     * @param string $parent_key
     *
     * @return array|null
     * @throws \InvalidArgumentException()
     */
    static function flatRowTable($records, $parent_prefix, $child_prefix, $parent_key = "id")
    {
        if (!is_array($records)) {
            throw new \InvalidArgumentException("Invalid input: ");
        }
        $id_parent_key = "$parent_prefix$parent_key";
        $final_res = null;
        foreach ($records as $row) {
            $current_parent = null;
            $current_child = null;
            $found = -1;
            foreach ($row as $k => $v) {
                if (strpos($k, $parent_prefix) === 0) {
                    if ($id_parent_key == $k) {
                        // Check existed
                        $found = self::firstIndexArrayMatch($final_res, $parent_key, $v);
                    }
                    if ($found == -1) {
                        $name = substr($k, strlen($parent_prefix));
                        $current_parent[$name] = $v;
                    }
                }
                if (strpos($k, $child_prefix) === 0) {

                    $name = substr($k, strlen($child_prefix));
                    $current_child[$name] = $v;
                }
            }
            if ($found != -1) {
                $current_parent = $final_res[$found];
            }
            $current_parent[$child_prefix][] = $current_child;
            if ($found != -1) {
                $final_res[$found] = $current_parent;
            } else {
                $final_res[] = $current_parent;
            }
        }
        return $final_res;
    }

    /**
     * @param $arr
     * @param $key
     * @param $value
     *
     * @return int
     */
    public static function firstIndexArrayMatch($arr, $key, $value)
    {
        for ($i = 0, $l = count($arr); $i < $l; ++$i) {
            if ($arr[$i][$key] == $value) {
                return $i;
            }
        }
        return -1;
    }

    /**
     * @param        $records
     * @param        $parent_prefix
     * @param        $child_prefix
     * @param string $parent_key
     *
     * @return array|null
     * @throws \InvalidArgumentException()
     */
    static function flatRowTableIgnoreDelFlag($records, $parent_prefix, $child_prefix, $parent_key = "id") // TODO should be set
    {
        if (!is_array($records)) {
            throw new \InvalidArgumentException("Invalid input: ");
        }
        $id_parent_key = "$parent_prefix$parent_key";
        $final_res = null;
        foreach ($records as $row) {
            $current_parent = null;
            $current_child = null;
            $del_flag = 0;
            $found = -1;
            foreach ($row as $k => $v) {
                if (strpos($k, $parent_prefix) === 0) {
                    if ($id_parent_key == $k) {
                        // Check existed
                        $found = self::firstIndexArrayMatch($final_res, $parent_key, $v);
                    }
                    if ($found == -1) {
                        $name = substr($k, strlen($parent_prefix));
                        $current_parent[$name] = $v;
                    }
                }
                if (strpos($k, $child_prefix) === 0 && !$del_flag) {
                    $name = substr($k, strlen($child_prefix));
                    if ($name == 'del_flag' && 1 === intval($k)) {
                        $del_flag = 1;
                    }
                    $current_child[$name] = $v;
                }
            }
            if ($found != -1) {
                $current_parent = $final_res[$found];
            }
            if (!$del_flag) {
                $current_parent[$child_prefix][] = $current_child;
            }
            if ($found != -1) {
                $final_res[$found] = $current_parent;
            } else {
                $final_res[] = $current_parent;
            }
        }
        return $final_res;
    }


    /**
     * @param                $prefix
     * @param string | array $fields
     * @param bool $convert_date
     *
     * @return array
     */
    static function columnsFrom($prefix, $fields, $convert_date = true)
    {
        if ($fields == null) {
            return null;
        }
        is_string($fields) && $fields = explode(',', preg_replace('/\s/', '', $fields));

        $columns = array_map(function ($item) use ($prefix, $convert_date) {
            if ($convert_date && $item === 'datecreate') {
                return "DATE_FORMAT( FROM_UNIXTIME( $prefix." . $item . ' ), "%d/%m/%Y %H:%i") AS datecreate';
            } else {
                return "$prefix.$item AS $item";
            }
        }, $fields);

        return $columns;
    }


    /**
     * @param                $model
     * @param string | array $fields
     * @param                $prefix
     * @param bool $convert_date
     *
     * @return array
     */
    static function selectFrom($model, $fields, $prefix = null, $convert_date = true)
    {
        if ($fields == null) {
            return null;
        }
        is_string($fields) && $fields = explode(',', preg_replace('/\s/', '', $fields));

        $columns = array_map(function ($item) use ($model, $convert_date, $prefix) {
            if ($convert_date && $item === 'datecreate') {
                return "DATE_FORMAT( FROM_UNIXTIME( [$model]." . $item . ' ), "%d/%m/%Y") AS datecreate';
            } else {
                return "[$model].$item AS $prefix$item";
            }
        }, $fields);

        return $columns;
    }


    static function criteriaFromArray(array $filters, array $options = [])
    {
        $conditions = "";
        foreach ($filters as $param => $value) {
            $conditions = $conditions
                ? $conditions . " AND $param = :$param:"
                : "$param = :$param:";

        }

        return $conditions == null
            ? null
            : [
                'conditions' => $conditions,
                'bind'       => $filters
            ];
    }


    /* static function tableToAssociateArray(array $table, array $keys, $delimiter = '_')
     {
         $fields = [];
         foreach ($keys as $key) {
             $table_keys = array_keys($table);
             $prefix = substr($key, 0, strrpos($key, $delimiter));

             in_array($key, $table_keys)
             && array_walk($table_keys, function ($item) use ($prefix, $fields, $key) {
                 if (strpos($item, $prefix) === 0) {
                     $fields[$key][] = $item;
                 }
             });

         }

         $root = [];
         foreach ($table as $row) {
             $f = 0;
             $parent = $root;
             foreach ($fields as $key_field => $sub_fields) {
                 if ($child = $parent[$row[$key_field]]) { // If key exist => child = parent
                     if (is_array($child))
                         $parent = &$parent[$row[$key_field]];
                     continue;
                 }
                 $prefix = substr($key_field, strrpos($key_field, $delimiter)) . $delimiter;
                 $parent[$row[$key_field]] = static::arrayFrom($row, $sub_fields, $prefix);
             }
         }

         return $root;
     }


     static function arrayFrom(array $data, array $fields, $prefix)
     {

         $fields_with_prefix = array_map(function ($item) use ($prefix) {
             return $prefix . $item;
         }, $fields);

         $array_res = array_intersect_key($data, array_flip($fields_with_prefix));

         $res = [];
         $ind = 0;
         foreach ($array_res as $v) {
             $res[$fields[$ind++]] = $v;
         }

         return $res;
     }*/


    /**
     * @param \Phalcon\Mvc\Model\Resultset\Simple $result_set
     *
     * @return Model[]
     */
    static function arrayFromSimple(\Phalcon\Mvc\Model\Resultset\Simple $result_set)
    {
        $res = [];
        for ($i = 0, $l = count($result_set); $i < $l; ++$i) {
            $res[] = $result_set->offsetGet($i);
        }
        return $res;
    }
}