<?php namespace Common\Sql\Dao;

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 5/15/2017
 * Time: 9:37 AM
 */
class MySqlDAO extends AbstractDAO
{

    /**
     * @var \Phalcon\Db\AdapterInterface
     */
    protected $_db;


    function __construct($model, $events_manager = null)
    {
        $this->_model = $model;
        $this->_events_manager = $events_manager instanceof \Phalcon\Events\Manager
            ? $events_manager
            : new \Phalcon\Events\Manager();

        $this->_db = provider('db');
    }


    /**
     * @param array $data
     * @param null $fields
     *
     * @return bool
     */
    protected function _createList(array $data, $fields = null)
    {
        if ($fields == null) {
            if (is_array($data[0])) {
                $fields = array_keys($data[0]);
            } else {

                $fields = array_keys($data);
            }
        } elseif (is_string($fields)) {
            $fields = explode(',', $fields);
        }

        $columns = sprintf('`%s`', implode('`,`', $fields));

        $str = '';
        foreach ($data as $values) {

            foreach ($values as &$val) {
                if (is_null($val)) {
                    $val = 'NULL';
                    continue;
                }
                if (is_string($val)) {
                    $val = str_replace("'", '\\\'', $val);
                    $val = "'" . $val . "'";
                }
            }

            $str .= sprintf('(%s),', implode(',', $values));
        }

        $str = rtrim($str, ',');

        $query = sprintf("INSERT INTO `%s` (%s) VALUES %s",
            $this->_getTable(),
            $columns,
            $str
        );

        return $this->_db->execute($query);
    }

    protected function _getTable()
    {
        return (new $this->_model())->getSource();
    }

    /**
     * @param      $data
     * @param bool $check
     * @param bool $permanent
     *
     * @return bool
     * @throws \LogicException
     */
    protected function _deleteConditions($data, $check = true, $permanent = false)
    {
        $where = $this->_getWhereCriteria($data);

        $source = (new $this->_model())->getSource();

        $table = $this->_getTable();

        if ($permanent) {
            $res = $this->_db->execute("DELETE FROM $table WHERE $where");
            return $res;
        }

        if (in_array('del_flag', call_user_func([$this->_model, 'getAttributes'])) && $check) {
            $q = "SELECT id FROM $source WHERE $where AND del_flag = '0'";

            $res = $this->_db->query($q)->fetchAll(PDO::FETCH_ASSOC);
            $ids = [];
            foreach ($res as $r) {
                $ids[] = $r['id'];
            }

            if ($ids == null) {
                throw new \LogicException("Nothing to delete");
            }
        }

        if (!$check) {
            return $this->updateConditions(['del_flag' => 1],
                $data
            );
        }

        return $this->updateConditions(
            ['del_flag' => 1],
            [
                'conditions' => 'id IN ({ids:array})',
                'bind'       => [
                    'ids' => $ids
                ]
            ]
        );

    }

    /**
     * @param $data
     *
     * @return mixed|string
     * @throws \InvalidArgumentException()
     */
    protected function _getWhereCriteria($data)
    {
        $where = "";
        if (is_string($data)) {
            $where = $data;
        } elseif (is_array($data)) {
            $where = $data['conditions'];
            if ($where == null) {
                throw new \InvalidArgumentException("Missing where");
            }
            if ($bind = $data['bind']) {
                foreach ($bind as $k => $v) {
                    if (is_array($v)) {
                        array_walk($v, function ($vv) {
                            $vv = str_replace("'", '\\\'', $vv);
                            return $vv;
                        });

                        $flat_value = sprintf("'%s'", implode("','", $v));
                        $where = str_replace("{" . "$k:array}", $flat_value, $where);
                    } else {
                        $v = trim($v, "'");
                        $v = str_replace("'", '\\\'', $v);
                        $where = str_replace(":$k:", "'$v'", $where);
                    }
                }
            }
        }

        return $where;
    }

    /**
     * @param array $data
     * @param       $params
     *
     * @return bool
     */
    protected function _updateConditions(array $data, $params)
    {
        $where = $this->_getWhereCriteria($params);
        $table = $this->_getTable();

        $set = "";
        foreach ($data as $k => $v) {
            $v = (trim($v, "'"));
            $v = str_replace("'", '\\\'', $v);
            $set = $set ? "$set , $k = '$v'" : "$k = '$v'";
        }

        $res = $this->_db->execute("UPDATE $table SET $set WHERE $where");
        return $res;
    }


    /**
     * @return \Phalcon\Db\AdapterInterface
     */
    public function getDb()
    {
        return $this->_db;
    }
}