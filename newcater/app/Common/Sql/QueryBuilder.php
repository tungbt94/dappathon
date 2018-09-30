<?php

namespace Common\Sql;


/**
 * Created by PhpStorm.
 * User: admin
 * Date: 3/28/2017
 * Time: 10:54 AM
 */
use Phalcon\Paginator\Adapter\QueryBuilder as PaginationQueryBuilder;

class QueryBuilder extends PaginationQueryBuilder
{


    /**
     * page->total_pages = totalPages,
     * page->total_items = rowcount,
     * page->limit = this->_limitRows;
     */
    protected $_items;
    protected $_total;
    protected $_total_pages;
    protected $_current;
    protected $_fetched = false;

    public static function from($builder, $page = null, $limit = null, $unlimited = false)
    {
        $param['builder'] = $builder;
        $param['page'] = $page == null ? 1 : intval($page);
        $param['limit'] = $limit === null
            ? PaginatedResult::DEFAULT_LIMIT
            : ((intval($limit) > PaginatedResult::MAX_LIMIT && !$unlimited)
                ? PaginatedResult::MAX_LIMIT
                : intval($limit));

        return new static($param);
    }


    public function isFetched()
    {
        return $this->_fetched;
    }

    public function renew()
    {
        $this->_fetched = false;
    }

    /**
     * @return array
     */
    public function getData()
    {
        $this->_fetch();
        return $this->_items;
    }


    protected function _fetch()
    {
        if ($this->_fetched) {
            return;
        }

        $fetch_results = $this->getPaginate();
        $this->_total = $fetch_results->total_items;
        $this->_items = $fetch_results->items->toArray();
        $this->_total_pages = $fetch_results->total_pages;
        $this->_current = $fetch_results->current;
        if ($this->_current <= 0 || $this->_current > $this->_total_pages) $this->_current = 1;
        $this->_fetched = true;
    }

    public function getTotalItems()
    {
        $this->_fetch();
        return $this->_total;
    }

    public function getTotalPages()
    {
        $this->_fetch();
        return $this->_total_pages;
    }


    /**
     * @return PaginatedResult
     */
    public function getResult()
    {
        $this->_fetch();
        return new PaginatedResult([
            'data' => $this->_items,
            'current' => $this->_current,
            'limit' => $this->_limitRows,
            'total' => $this->_total,
            'total_pages' => $this->_total_pages
        ]);
    }
}