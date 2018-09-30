<?php namespace Common\Query;

/**
 * Created by PhpStorm.
 * User: balol
 * Date: 5/6/2017
 * Time: 12:14 AM
 */
class QueryOperator
{
    const PRIORITY = [
        '+' => 2,
        '-' => 2,
        '*' => 3,
        '/' => 1,
        '.' => 5, // AND
        ',' => 4, // OR
        '>' => 5,
        '<' => 5,
        '~' => 5,
        '=' => 5,
    ];

}