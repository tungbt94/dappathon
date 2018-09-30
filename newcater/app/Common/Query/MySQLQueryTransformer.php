<?php namespace Common\Query;

use Common\Util\Arrays;

/**
 * Created by PhpStorm.
 * User: balol
 * Date: 5/6/2017
 * Time: 1:15 AM
 */
class MySQLQueryTransformer implements QueryTransformerInterface
{

    const FULL_TEXT_SEARCH_KEY = 'q';

    /**
     * @var array
     */
    protected $_map_operator;

    /**
     * @var array
     */
    protected $_white_list_fields;

    /**
     * @var array
     */
    protected $_white_list_operators;

    /**
     * @var array
     */
    protected $_full_text_search_fields;

    /**
     * @var
     */
    protected $_model;


    function __construct()
    {
        $this->_white_list_fields = '*';
        $this->_white_list_operators = '*';
        $this->_map_operator = [
            '<' => '<',
            '>' => '>',
            '=' => '=',
            '~' => 'LIKE',
            '.' => 'AND',
            ',' => 'OR',
        ];
    }

    static function newInstance()
    {
        return new MySQLQueryTransformer();
    }


    /**
     * @param QueryExpression $expression
     * @param \Closure|null $filter
     *
     * @return string
     */
    function transform(QueryExpression $expression, \Closure $filter = null)
    {
        return $this->_transform($expression, $filter);
    }


    /**
     * @param QueryExpression $expression
     * @param \Closure|null $filter
     *
     * @return string
     */
    protected function _transform(QueryExpression &$expression, \Closure $filter = null)
    {
        if ($expression->isBasic()) {

            $field = $expression->getField();
            $operator = $this->_map_operator[$expression->getOperator()];
            $value = $expression->getValue();
            if ($filter != null) {
                $filter_res = $filter($field, $operator, $value);

            } else {
                $filter_res = $this->defaultFilter($field, $operator, $value);
            }

            if ($filter_res) {
                $sql = $filter_res;

            } else {
                $value = sql_encoded_value($value, true);
                $sql = sprintf("%s %s %s", $field, $operator, trim($value));
            }

        } else {

            $left = $this->_transform($expression->getField(), $filter);

            $operator = $this->_map_operator[$expression->getOperator()];

            $right = $this->_transform($expression->getValue(), $filter);

            $sql = "$left $operator $right";
        }

        return $sql === null ? "" : "($sql)";
    }

    function defaultFilter(&$field, &$operator, &$value)
    {

        if ($operator == 'LIKE') {
            $value = "%$value%";

        } elseif ($field == static::FULL_TEXT_SEARCH_KEY && $operator == '=') {

            if ($this->_full_text_search_fields == null) {
                throw new \InvalidArgumentException("Model {$this->_model} does not support text search");
            }


            if (strlen($value) <= 5) {
                $sql = "";
                foreach ($this->_full_text_search_fields as $search_field) {
                    $search_field = ($this->_model == null ? "" : "[$this->_model].") . "$search_field";
                    $sql = $sql ? $sql . " OR $search_field LIKE '%$value%'" : "$search_field LIKE '%$value%'";
                }
                return $sql;

            } else {
                $full_text_search_fields = $this->_full_text_search_fields;
                $this->_model && $full_text_search_fields = Arrays::appendElement("[$this->_model].", $full_text_search_fields);
                $fields = join(',', $full_text_search_fields);
                return "MATCH($fields) AGAINST ('$value')";
            }
        }

        $this->_model && $field = "[$this->_model].$field";

        return null;
    }

    /**
     * @return mixed
     */
    public
    function getMapOperator()
    {
        return $this->_map_operator;
    }

    /**
     * @param mixed $map_operator
     */
    public
    function setMapOperator($map_operator)
    {
        $this->_map_operator = $map_operator;
    }

    /**
     * @return mixed
     */
    public
    function getWhiteListFields()
    {
        return $this->_white_list_fields;
    }

    /**
     * @param mixed $white_list_fields
     */
    public
    function setWhiteListFields($white_list_fields)
    {
        $this->_white_list_fields = $white_list_fields;
    }

    /**
     * @return mixed
     */
    public
    function getWhiteListOperators()
    {
        return $this->_white_list_operators;
    }

    /**
     * @param mixed $white_list_operators
     */
    public function setWhiteListOperators($white_list_operators)
    {
        $this->_white_list_operators = $white_list_operators;
    }

    /**
     * @param array|string $fields
     *
     * @return $this
     */
    public function setFullTextSearchFields($fields)
    {

        if (is_string($fields)) {
            $this->_full_text_search_fields = preg_split('/,/', $fields);

        } elseif (is_array($fields)) {
            $this->_full_text_search_fields = $fields;
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->_model;
    }

    /**
     * @param mixed $model
     *
     * @return $this
     */
    public function setModel($model)
    {
        $this->_model = $model;
        return $this;
    }

    protected function _checkWhiteList(QueryExpression &$epxression)
    {
        $field = $epxression->getField();
        if (!$field instanceof QueryExpression && !$this->_checkInArrayOrAntMatch($field, $this->_white_list_fields)) {
            return false;
        }

        $operator = $epxression->getField();
        if (!$operator instanceof QueryExpression && !$this->_checkInArrayOrAntMatch($operator, $this->_white_list_operators)) {
            return false;
        }

        return true;
    }

    protected function _checkInArrayOrAntMatch($needle, $haystack)
    {
        if (is_array($haystack)) {
            if (!in_array($needle, $haystack)) {
                return false;
            }
        } else {
            $regex = str_replace('*', '\\w*', $haystack);
            if (!preg_match("/$regex/", $needle)) {
                return false;
            }
        }

        return true;
    }
}