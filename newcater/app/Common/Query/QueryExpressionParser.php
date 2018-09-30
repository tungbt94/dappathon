<?php namespace Common\Query;

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 3/30/2017
 * Time: 8:59 AM
 */
class QueryExpressionParser
{

    const JOINS = "().,";

    /**
     * @var string[]
     */
    protected $_join;

    /**
     * @var QueryExpression[]
     */
    protected $_conditions;


    protected function __construct($query)
    {
        $this->_query = ltrim($query, '.,');
        $this->_join = [];
        $this->_conditions = [];
        $this->_parse();

    }

    /**
     * @throws \InvalidArgumentException
     */
    protected function _parse()
    {
        $parenthesis = 0;
        $word = '';
        $i_total = 0;
        $quote_open = false;

        for ($i = 0, $l = strlen($this->_query); $i < $l; ++$i) {
            $c = $this->_query[$i];

            if ($c === ' ') {
                $quote_open && $word .= $c;
                continue;
            }

            if ($c === '\'') {
                $quote_open = !$quote_open;
                continue;
            }

            $c === '(' && $parenthesis++;
            $c === ')' && $parenthesis--;
            if ($parenthesis < 0) {
                throw new \InvalidArgumentException("Query syntax error at: " . substr($this->_query, $i));
            }

            if (strpos(static::JOINS, $c) !== false && $quote_open === false) {
                $cond = '';
                $word && $cond = new QueryExpression($word);
                $cond && $this->_conditions[$i_total++] = $cond;
                $word = '';
                $this->_join[$i_total++] = $c;
            } else {
                $word .= $c;
            }
        }
        $word && $this->_conditions[$i_total] = new QueryExpression($word);
    }

    /**
     * @param      $query
     *
     * @return QueryExpressionParser
     */
    static function from($query)
    {
        $zis = new QueryExpressionParser($query);
        return $zis;
    }

    /**
     * @return QueryExpression
     */
    public function getTree()
    {
        // =========== Encode ========== //
        $total = $this->_conditions + $this->_join;
        ksort($total);

        if ($total == null) {
            return null;
        }

//        $parent = new QueryExpression();
        $stack = [];
        $output = [];
        $parent = null;

        foreach ($total as $v) {

            if ($v instanceof QueryExpression) {
                $output[] = $v;

            } elseif ($v == '(') {
                $stack[] = $v;

            } elseif ($v == ')') {
//                $last = $output[count($output) - 1];
                for ($stack = array_values($stack), $l = count($stack), $i = $l - 1; $i >= 0; --$i) {
                    $item = $stack[$i];
                    if ($item == '(') {
                        unset($stack[$i]);
                        break;
                    }
                    $output[] = $item;
                    unset($stack[$i]);
                }

            } else {
                $last_op = end($stack);
                if (isset(QueryOperator::PRIORITY[$last_op]) && isset(QueryOperator::PRIORITY[$v]) && QueryOperator::PRIORITY[$last_op] >= QueryOperator::PRIORITY[$v]) {
                    $last_op = array_pop($stack);
                    $output[] = $last_op;
                }
                $stack[] = $v;
            }
        }
        $output = array_merge($output, array_reverse($stack));

        // =========== Decode ========== //
        $stack = [];
        foreach ($output as $o) {

            if ($o instanceof QueryExpression) {
                $stack[] = $o;

            } else {
                $left = array_pop($stack);
                $right = array_pop($stack);
                $new_expression = new QueryExpression();
                $new_expression->setField($left)->setValue($right)->setOperator($o);
                $stack[] = $new_expression;
            }
        }

        return $stack[0];
    }

    /**
     * @return string[]
     */
    public function getJoin()
    {
        return $this->_join;
    }

    /**
     * @return QueryExpression[]
     */
    public function getConditions()
    {
        return $this->_conditions;
    }


}
