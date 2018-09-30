<?php namespace Common\Query;

/**
 * Created by PhpStorm.
 * User: balol
 * Date: 5/6/2017
 * Time: 12:59 AM
 */
interface QueryTransformerInterface
{

    function setMapOperator($map_operator);


    function getMapOperator();


    function transform(QueryExpression $expression, \Closure $filter = null);


    function defaultFilter(&$field, &$operator, &$value);


    function setWhiteListFields($fields);


    function getWhiteListFields();


    function setWhiteListOperators($operators);


    function getWhiteListOperators();

}