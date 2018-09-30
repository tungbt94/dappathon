<?php namespace Common\Query;


/**
 * Created by PhpStorm.
 * User: balol
 * Date: 5/6/2017
 * Time: 1:15 AM
 */
class ElasticQueryTransformer implements QueryTransformerInterface
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
            '<' => 'range.$field.lt-$value',

            '>' => 'range.$field.gt-$value',

            '=' => 'match_phrase.$field-$value',

            '~' => 'match.$field-$value',

            '.' => 'bool.must.[$field-$value]',

            ',' => 'bool.should.[$field-$value]',
        ];
    }

    static function newInstance()
    {
        return new static();
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
        $field = $expression->getField();
        $operator = $this->_map_operator[$expression->getOperator()];
        $value = $expression->getValue();

        if ($filter != null) {
            $filter_res = $filter($field, $operator, $value);

        } else {
            $filter_res = $this->elasticFilter($field, $operator, $value);
        }

        return $filter_res;

    }


    function defaultFilter(&$field, &$operator, &$value)
    {
    }


    function elasticFilter($field, $operator, $value)
    {
        if ($field == static::FULL_TEXT_SEARCH_KEY) {
            if ($this->_model) {
                $fullTextSearchFields = call_user_func([$this->_model, 'getFullTextSearchFields']);
                if ($fullTextSearchFields) {

                    is_string($fullTextSearchFields) && $fullTextSearchFields = explode(',', str_replace(' ', '', $fullTextSearchFields));
                    $conditions = [];

                    $select = [
                        'match.$field-$value'        => 'match',
                        'match_phrase.$field-$value' => 'match_phrase',
                    ];

                    foreach ($fullTextSearchFields as $field) {
                        $conditions[] = [
                            $select[$operator] => [$field => $value]
                        ];
                    }
                    return [
                        'bool' => [
                            'should' => $conditions
                        ]
                    ];
                }
            }
        }

        $explodedOperators = [];
        if (preg_match('/\[.+\]/', $operator, $explodedOperators)) {
            $insideOperator = $explodedOperators[0];

            $insideOperator = substr($insideOperator, 1, strlen($insideOperator) - 2);

            $outsideOperator = preg_replace('/\.\[.+\]/', '', $operator);
            $inside = $this->elasticFilter($field, $insideOperator, $value);
            $outside = $this->elasticFilter($field, $outsideOperator, $value);
            $childOutside = ArrayUtils::getChildest($outside);
            $childOutside[] = $inside;
            return $outside;
        }

        if ($field instanceof QueryExpression && $value instanceof QueryExpression) {
            $operator = explode('.', $operator);

            $res = [];
            $current = &$res;
            foreach ($operator as $simpleOperator) {
                $left = $simpleOperator;
                $current[$left] = [];
                $current = &$current[$left];
            }
            $current = array_merge($current, [
                $this->elasticFilter($field->getField(), $this->_map_operator[$field->getOperator()], $field->getValue()),
                $this->elasticFilter($value->getField(), $this->_map_operator[$value->getOperator()], $value->getValue()),
            ]);

            return $res;
        }

        $specialValues = [
            '$field' => $field,
            '$value' => $value,
        ];

        $operator = explode('.', $operator);

        $res = [];
        $current = &$res;
        foreach ($operator as $simpleOperator) {

            if (strpos($simpleOperator, '-') === false) {
                $field = isset($specialValues[$simpleOperator]) ? $specialValues[$simpleOperator] : $simpleOperator;
                $current[$field] = [];
                $current = &$current[$field];

            } else {

                $expressions = explode('-', $simpleOperator);
                $left = isset($specialValues[$expressions[0]]) ? $specialValues[$expressions[0]] : $expressions[0];
                $right = isset($specialValues[$expressions[1]]) ? $specialValues[$expressions[1]] : $expressions[1];
                $current[$left] = $right;
            }
        }
        return $res;
    }

    /**
     * @return mixed
     */
    public function getMapOperator()
    {
        return $this->_map_operator;
    }

    /**
     * @param mixed $map_operator
     */
    public function setMapOperator($map_operator)
    {
        $this->_map_operator = $map_operator;
    }

    /**
     * @return mixed
     */
    public function getWhiteListFields()
    {
        return $this->_white_list_fields;
    }

    /**
     * @param mixed $white_list_fields
     */
    public function setWhiteListFields($white_list_fields)
    {
        $this->_white_list_fields = $white_list_fields;
    }

    /**
     * @return mixed
     */
    public function getWhiteListOperators()
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