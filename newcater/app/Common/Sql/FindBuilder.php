<?php

namespace Common\Sql;

use Common\Query\MySQLQueryTransformer;
use Common\Query\QueryExpressionParser;
use Common\Util\Arrays;
use Common\Util\Pql;
use Common\Validation\Filter;
use Phalcon\Mvc\Model\Query\BuilderInterface;
use Phalcon\Mvc\Model\Resultset;

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 5/6/2017
 * Time: 11:16 AM
 *
 * <code>
 *
 *   $result = FindBuilder::newInstance(Film::builder())
 *      ->applyFields('name, is_hot, tag')
 *      ->join(FilmCategory::class, 'id = film_id', null, 'filmCategory')
 *      ->leftJoinfilmCategory(Category::class, 'category_id = id', '*', 'category')
 *      ->getPaginatedResult(1, 20);
 *
 * </code>
 *
 */
class FindBuilder
{

    /**
     * @var \Phalcon\Mvc\Model\Query\Builder
     */
    protected $builder;

    /**
     * @var string
     */
    protected $alias_pointer;


    /**
     * @var Filter
     */
    protected $api_filter;


    /**
     * @var string[]
     */
    protected $columns;

    /**
     * @var string[]
     */
    protected $ignores;


    /**
     * @var string[]
     */
    protected $custom_columns;


    /**
     * @var string[]
     */
    protected $decorated_model_maps;


    /**
     * @var array
     */
    protected $cache;


    /**
     * @var string
     */
    protected $genesis_pointer;


    /**
     * FindBuilder constructor.
     *
     * @param $builder BuilderInterface
     */
    function __construct($builder)
    {
        $this->builder = $builder;
        $this->api_filter = new Filter();
        $this->columns = [];
        $this->custom_columns = [];

        $base = $builder->getFrom();

        if (is_string($base)) {
            $model = $base;
            $alias = $base;

        } elseif (is_array($base)) {
            $model = array_values($base)[0];
            $alias = array_keys($base)[0];
        }

        $this->columns[$alias]['fields'] = '*';
        $this->columns[$alias]['model'] = $model;
        $this->columns[$alias]['alias'] = $alias;

        $this->genesis_pointer = $alias;
    }


    /**
     * @param $builder BuilderInterface
     *
     * @return static
     */
    static function newInstance($builder)
    {
        $instance = new static($builder);
        return $instance;
    }


    function from($model)
    {
        $this->builder->from($model);
        $this->addColumns($model, '*');
        return $this;
    }


    /**
     * @param       $model_or_columns
     * @param array $columns
     *
     * @return $this
     */
    function addColumns($model_or_columns, $columns = null)
    {
        if ($columns == null) { // $model as column
            $this->custom_columns[] = $model_or_columns;
            return $this;
        }

        $this->columns[$model_or_columns]['fields'] = $columns;
        return $this;
    }


    /**
     * @param      $ref_model
     * @param null $options
     * @param null $fields
     *
     * @param null $alias
     *
     * @return $this
     * @throws \Common\Exception\Exception
     */
    function innerJoin($ref_model, $options, $fields = null, $alias = null)
    {
        return $this->applyJoin('INNER_JOIN', $ref_model, $options, $fields, $alias);
    }

    protected function applyJoin($type, $ref_model, $options, $fields = null, $alias = null)
    {
        $leftAlias = $this->_chosenModel()['alias'];

        $this->columns[$alias]['fields'] = $fields;
        $this->columns[$alias]['model'] = $ref_model;
        $this->columns[$alias]['alias'] = $alias ?: $ref_model;

        $options && $options = $this->_appendModelInJoin($options, $leftAlias, $alias ? $alias : $ref_model);

        if ($options) {
            switch ($type) {
                case "INNER_JOIN":
                    $options && $this->builder->innerJoin("[$ref_model]", $options, $alias ? $alias : null);
                    break;

                case "LEFT_JOIN":
                    $options && $this->builder->leftJoin("[$ref_model]", $options, $alias ? $alias : null);
                    break;

                default:
                    $options && $this->builder->join("[$ref_model]", $options, $alias ? $alias : null);
                    break;
            }

        } else {
            throw new \Common\Exception\Exception("Invalid conditions");
        }

        return $this;
    }

    /**
     * @return mixed|null|string
     */
    protected function _chosenModel()
    {
        if ($this->alias_pointer == null) {

            $fromBuilder = $this->builder->getFrom();

            if (is_array($fromBuilder)) {
                $this->alias_pointer = array_keys($fromBuilder)[0];

            } else {
                $this->alias_pointer = $fromBuilder;
            }
        }

        return $this->columns[$this->alias_pointer];
    }

    /**
     * @param      $query
     * @param      $left_alias
     * @param      $right_alias
     *
     * @param boo
     *
     * @return string
     */
    protected function _appendModelInJoin($query, $left_alias, $right_alias)
    {
        $query = trim($query);
        $tmp_query = preg_split('/\s+/', $query);

        $append = "";

        if (count($tmp_query) === 3) {
            return "[$left_alias].$tmp_query[0] $tmp_query[1] [$right_alias].$tmp_query[2]" . $append;
        } else {
            return str_replace(['L.', 'R.'], ["[$left_alias].", "[$right_alias]."], $query) . $append;
        }
    }

    function groupBy($params)
    {
        $this->builder->groupBy($params);
        return $this;
    }

    /**
     * @param      $ref_model
     * @param null $options
     * @param null $fields
     *
     * @param null $alias
     *
     * @param boo
     *
     * @return $this
     */
    function leftJoin($ref_model, $options, $fields = null, $alias = null)
    {
        return $this->applyJoin('LEFT_JOIN', $ref_model, $options, $fields, $alias);
    }

    /**
     * @param              $ref_model
     * @param              $options
     * @param string|array $fields set as '*' to get all fields
     *
     * @param null $alias
     *
     * @param boo
     *
     * @return $this
     */
    function join($ref_model, $options, $fields = null, $alias = null)
    {
        return $this->applyJoin('JOIN', $ref_model, $options, $fields, $alias);
    }

    /**
     * Set field for current models
     *
     * @param $fields
     *
     * @return $this
     */
    function applyFields($fields)
    {
        $model = $this->_chosenModel()['model'];

        if (method_exists($model, 'getAttributes')) {

            $all_fields = call_user_func([$model, 'getAttributes']);

            $fields = $this->api_filter->sanitize($fields, Filter::FILTER_CONVERT_FIELDS, true);
            $fields = array_intersect($all_fields, $fields);
            $fields == null && $fields = $all_fields;

        } else {
            $fields = $this->api_filter->sanitize($fields, Filter::FILTER_CONVERT_FIELDS, true);

        }

        $alias = $this->_chosenModel()['alias'];
        $this->columns[$alias]['fields'] = $fields;
        return $this;
    }

    /**
     * @param      $q
     *
     * @param null $alias
     *
     * @return $this
     */
    public function applyQuery($q, $alias = null)
    {
        if ($q) {
            $query_expressions = QueryExpressionParser::from($q)->getTree();
            if ($query_expressions) {

                $alias = $alias ?: $this->_chosenModel()['alias'];
                $model = $this->columns[$alias]['model'];
                $fulltext_search_fields = call_user_func([$model, 'getFullTextSearchFields']);

                $sql_conditions = MySQLQueryTransformer::newInstance()
                    ->setModel($alias)
                    ->setFullTextSearchFields($fulltext_search_fields)
                    ->transform($query_expressions);
                $sql_conditions && $this->builder->andWhere($sql_conditions);
            }
        }
        return $this;
    }

    function __call($name, $arguments)
    {
        foreach (['leftjoin', 'innerjoin', 'join', 'applyFields'] as $prefix) {
            if (strpos(strtolower($name), $prefix) === 0) {

                $alias = substr($name, strlen($prefix));
                $this->alias_pointer = $alias;

                call_user_func_array([$this, $prefix], $arguments);
                $this->alias_pointer = $this->genesis_pointer;
                return $this;
            }
        }
    }


    public function decorateField($alias, $field)
    {
        return "__{$alias}__$field";
    }

    public function andWhere($conditions, $bindParams = null, $bindTypes = null)
    {
        $this->builder->andWhere($conditions, $bindParams, $bindTypes);
        return $this;
    }

    public function orWhere($conditions, $bindParams = null, $bindTypes = null)
    {
        $this->builder->orWhere($conditions, $bindParams, $bindTypes);
        return $this;
    }

    public function where($conditions, $bindParams = null, $bindTypes = null)
    {
        $this->builder->where($conditions, $bindParams, $bindTypes);
        return $this;
    }

    /**
     * @param      $page
     * @param      $max
     *
     * @param bool $flat
     *
     * @param bool $unlimited
     *
     * @return PaginatedResult
     */
    public function getPaginatedResult($page, $max, $flat = true, $unlimited = false)
    {
        $this->prepareBuilder();
        $paginated_res = QueryBuilder::from($this->builder, $page, $max, $unlimited)->getResult();
        $paginated_res->data = Arrays::flatTable(
            $paginated_res->data,
            array_flip($this->decorated_model_maps),
            $flat ? [$this->_chosenModel()['alias']] : null
        );
        return $paginated_res;
    }

    /**
     *
     */
    protected function prepareBuilder()
    {
        $this->redecorateModelMap();
        $select_columns = [];
        foreach ($this->columns as $alias => $info) {
            $fields = $info['fields'];
            $model = $info['model'];

            if ($fields == '*') {
                if (method_exists($model, 'getAttributes')) {
                    $model_fields = call_user_func([$model, 'getAttributes']);
                }
            } else {

                $model_fields = $fields;
            }
            if (isset($model_fields)) {
                $model_fields = $this->api_filter->sanitize($model_fields, Filter::FILTER_CONVERT_FIELDS, true);

                // Ignore fields
                if ($ignore_fields = $this->ignores[$model]) {
                    $model_fields = array_diff($model_fields, $ignore_fields);
                }

                $prefix = $this->decorated_model_maps[$alias];
                foreach ($model_fields as $field) {
                    $select_columns[] = "[$alias].$field AS $prefix$field";
                }
            }
        }
        $select_columns = array_merge($select_columns, $this->custom_columns);
        if ($select_columns == null) {
            $model = $this->_chosenModel()['model'];
            $alias = $this->_chosenModel()['alias'];

            $select_columns = call_user_func([$model, 'getAttributes']);
            $select_columns = Pql::columnsFrom($alias, $select_columns);
        }

        $this->builder->columns($select_columns);
    }

    protected function redecorateModelMap()
    {
        $this->decorated_model_maps = [];
        foreach ($this->columns as $alias => $info) {
            $this->decorated_model_maps[$alias] = "__{$alias}__";
        }
    }


    public static function getQueryFrom($q, $model)
    {
        \Assert\Assertion::notEmpty($q, '$q bị sai');

        $query_expressions = QueryExpressionParser::from($q)->getTree();
        if ($query_expressions) {
            $fulltext_search_fields = call_user_func([$model, 'getFullTextSearchFields']);
            $sql_conditions = MySQLQueryTransformer::newInstance()
                ->setModel($model)
                ->setFullTextSearchFields($fulltext_search_fields)
                ->transform($query_expressions);
            return $sql_conditions;
        }
    }

    /**
     * @return int
     */
    public function count()
    {
        $this->builder->columns("COUNT (*) as total");
        $res = $this->builder->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_ARRAYS)->toArray()[0]['total'];
        return (int)$res;
    }

    /**
     * @param bool $flat
     * @param null $cumulative
     *
     * @return array
     */
    public function getFirst($flat = true, $cumulative = null)
    {
        $this->builder->limit(1);
        $res = $this->getResult($flat, $cumulative);
        if (is_array($res)) {
            return $res[0];
        }
        return null;
    }

    /**
     *
     * @param bool $flat
     * @param string $cumulative define the the key to cumulative
     *
     * @return array
     */
    public function getResult($flat = true, $cumulative = null)
    {
        $this->prepareBuilder();
        $this->builder->limit(PaginatedResult::DEFAULT_LIMIT);

        $query = $this->builder->getQuery();
        $this->cache && $query->cache($this->cache);

        $res = $query->execute()->setHydrateMode(Resultset::HYDRATE_ARRAYS)->toArray();

        $res = Arrays::flatTable(
            $res,
            array_flip($this->decorated_model_maps),
            $flat ? [$this->_chosenModel()['alias']] : null
        );

        $cumulative && $res = Arrays::arrayCumulative($res, $cumulative);

        return $res;
    }

    /**
     * @return string
     */
    function rawSql()
    {
        $this->prepareBuilder();
        $intermediate = $this->builder->getQuery()->parse();

        /** @var \Phalcon\Db\Dialect $dialect */
        $dialect = provider('db')->getDialect();
        $sql = $dialect->select($intermediate);
        return $sql;
    }


    function applyOrder($order, $alias = null)
    {
        $order = $order ?: '-id';
        if ($order) {
            $table = $alias ? $this->columns[$alias] : $this->_chosenModel();
            $model = $table['model'];

            if (is_string($order)) {
                strpos($order, '-') === 0 && $desc = true && $order = substr($order, 1);
                property_exists($model, $order) && $this->builder->orderBy("[$model].$order" . (isset($desc) ? " DESC" : ""));

            } elseif (is_array($order)) {
                property_exists($model, $order['field']) && $this->builder->orderBy("FIELD([$model].{$order['field']}, {$order['data']})");
            }

        }
        return $this;
    }


    function ignoreFields($model_or_fields, $fields = null)
    {
        if ($fields == null) {
            $model = $this->_chosenModel();
            $fields = $model_or_fields;
        } else {
            $model = $model_or_fields;
        }
        $fields = $this->api_filter->sanitize($fields, Filter::FILTER_CONVERT_FIELDS, true);
        $this->ignores[$model] = $fields;
        return $this;
    }


    /**
     * @return string[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @return array
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @param array $cache
     *
     * @return $this;
     */
    public function setCache($cache)
    {
        $this->cache = $cache;
        return $this;
    }

}