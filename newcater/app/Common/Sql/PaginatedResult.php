<?php

namespace Common\Sql;


class PaginatedResult
{

    const DEFAULT_LIMIT = 50;
    const MAX_LIMIT = 300;


    public $data;
    public $current = 0; // items
    public $limit = 0; // curent_page
    public $total = 0; // limit
    public $total_pages = 0; // total items

    /**
     * PaginatedResult constructor.
     *
     * @param array $res
     */
    function __construct(array $res)
    {
        $this->data = $res['data'];

        $this->current = $res['current'];

        $this->limit = $res['limit'];

        $this->total = $res['total'];

        /**
         *   Total_pages
         */
        $this->total_pages = $res['total_pages'];
    }


    public function getPageInfo()
    {
        return [
            'current'     => $this->current,
            'limit'       => $this->limit,
            'total'       => $this->total,
            'total_pages' => $this->total_pages,
        ];
    }


    public function toArray()
    {
        return [
            'data'   => $this->data,
            'paging' => $this->getPageInfo()
        ];
    }
}
