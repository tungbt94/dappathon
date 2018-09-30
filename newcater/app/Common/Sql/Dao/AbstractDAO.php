<?php namespace Common\Sql\Dao;

use Phalcon\Events\ManagerInterface;

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 5/13/2017
 * Time: 4:02 PM
 */
abstract class AbstractDAO implements DaoInterface, \Phalcon\Events\EventsAwareInterface
{

    protected $_model;

    /**
     * @var \Phalcon\Events\Manager
     */
    protected $_events_manager;


    public function createList(array $data, $fields = null)
    {
        $this->_events_manager->fire('dao:beforeCreateList', $this, $data);
        $res = $this->_createList($data, $fields);
        $this->_events_manager->fire('dao:afterCreateList', $this, $res);
        return $res;
    }

    abstract protected function _createList(array $data, $fields = null);

    public function deleteConditions($params, $check = true, $permanent = false)
    {
        $this->_events_manager->fire('dao:beforeDeleteConditions', $this, $params);
        $res = $this->_deleteConditions($params, $check, $permanent);
        $this->_events_manager->fire('dao:afterDeleteConditions', $this, $res);
        return $res;
    }

    abstract protected function _deleteConditions($params, $check = true, $permanent = false);

    public function updateConditions(array $data, $params)
    {
        $this->_events_manager->fire('dao:beforeUpdateConditions', $this, [$data, $params]);
        $res = $this->_updateConditions($data, $params);
        $this->_events_manager->fire('dao:afterUpdateConditions', $this, $res);
        return $res;
    }

    abstract protected function _updateConditions(array $data, $params);

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->_model;
    }

    /**
     * @param mixed $table
     *
     * @return $this
     */
    public function setModel($table)
    {
        $this->_model = $table;
        return $this;
    }

    /**
     * @return \Phalcon\Events\Manager
     */
    public function getEventsManager()
    {
        return $this->_events_manager;
    }

    /**
     * @param \Phalcon\Events\Manager|ManagerInterface $events_manager
     *
     * @return $this
     */
    public function setEventsManager(ManagerInterface $events_manager)
    {
        $this->_events_manager = $events_manager;
        return $this;
    }

}