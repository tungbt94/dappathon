<?php namespace Common\Query;

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 5/5/2017
 * Time: 9:55 AM
 */
class QueryExpression
{

    const OPERATORS = '<>=~';

    /**
     * @var string
     */
    public $operator;

    /**
     * @var QueryExpression|string
     */
    public $field;

    /**
     * @var QueryExpression|string
     */
    public $value;

    function __construct($condition = null)
    {
        if ($condition) {
            $fv = preg_split("/[" . static::OPERATORS . "]/", $condition);
            if (count($fv) == 2) {
                preg_match("/[" . static::OPERATORS . "]/", $condition, $operators);
                if (count($operators) == 1) {
                    $this->field = $fv[0];
                    $this->value = $fv[1];
                    $this->operator = $operators[0];
                    return;
                }
            }

            throw new \InvalidArgumentException("Lỗi cú pháp trong \$q: $condition");
        }
    }


    /**
     * @param null $alias
     *
     * @param \Closure $transform
     *
     * @return string
     */
    public function getSql($alias = null, \Closure $transform = null)
    {
        if ($transform === null) {
            $this->defaultTransform();
        } else {
            $transform($this->field, $this->operator, $this->value);
        }

        $op = self::getSqlOperator($this->operator);
        $value = trim($this->value, '\'');
        $value = $op == 'LIKE' ? "'%$value%'" : "'$this->value'";
        $param = $alias == null ? $this->field : "$alias.{$this->field}";
        $sql = join(' ', [$param, $op, $value]);
        return $sql;
    }


    /**
     * @param $param
     *
     * @return array
     */
    public function getValues($param)
    {
        $result = [];
        if ($this->isBasic()) {
            if ($this->field == $param) {
                $result[] = $this->value;
            }

        } else {
            $left = $this->field->getValues($param);
            $right = $this->value->getValues($param);

            $result = array_merge($left, $result);
            $result = array_merge($right, $result);
        }

        return $result;
    }


    protected function defaultTransform()
    {
        if (strpos($this->field, 'date') !== false && preg_match('/[0-9]+\/[0-9]+\/[0-9]+/', $this->value)) {
            $date = \DateTime::createFromFormat('d/m/Y', $this->value);
            $this->value = $date->getTimestamp();
        }
    }

    public static function getSqlOperator($op)
    {
        $mapSql = [
            '<' => '<',
            '>' => '>',
            '=' => '=',
            '~' => 'LIKE',
        ];

        return $mapSql[$op];
    }

    /**
     * @return QueryExpression
     */
    public function getField()
    {
        if ($this->value == null && $this->operator == null && $this->field instanceof QueryExpression) {
            return $this->field->getField();
        }

        return $this->field;
    }

    /**
     * @param mixed $field
     *
     * @return $this
     */
    public function setField($field)
    {
        $this->field = $field;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @param mixed $operator
     *
     * @return $this
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
        return $this;
    }

    /**
     * @return QueryExpression
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }


    public function isBasic()
    {
        return (!$this->field instanceof static) && (!$this->value instanceof static);
    }
}