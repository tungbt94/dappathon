<?php namespace Model;

use Phalcon\Mvc\Model\Manager;

/**
 * Class BaseModel
 * @package Model
 *
 * @method static static findFirstById($id)
 */
abstract class BaseModel extends \Phalcon\Mvc\Model
{

    const STATUS_INACTIVE = 1; // Không hoạt động
    const STATUS_ACTIVE = 2; // Hoạt động
    const STATUS_LOCK = 3; // Khóa

    const STATUS_PENDING = 0; // Chờ
    const STATUS_APPROVE = 1; // Đã duyệt
    const STATUS_REJECT = 2; // Từ chối


    /**
     * map_object
     *
     * @param $arr mixed
     *
     * @return static
     */
    public function map_object($arr)
    {
        if (is_object($arr)) {
            $arr = (array)$arr;
        }
        foreach ($arr as $key => $val) {
            if (property_exists($this, $key)) {
                $this->{$key} = $val;
            }
        }
        return $this;
    }


    static function newInstance($data)
    {
        $instance = new static();
        $instance->assign($data);
        return $instance;
    }


    public function getStatusText()
    {
        if ($this->status == BaseModel::STATUS_PENDING) return "Pending";
        else if ($this->status == BaseModel::STATUS_APPROVE) return "Approve";
        else if ($this->status == BaseModel::STATUS_REJECT) return "Closed";
    }


    public function getStatusClass()
    {
        if ($this->status == BaseModel::STATUS_PENDING) return "warning";
        else if ($this->status == BaseModel::STATUS_APPROVE) return "success";
        else if ($this->status == BaseModel::STATUS_INACTIVE) return "dark";
    }


    static function builder($alias = null)
    {
        /** @var Manager $modelManager */
        $modelManager = provider('modelsManager');

        $builder = $modelManager->createBuilder();

        if ($alias) {
            $builder->from([$alias => static::class]);

        } else {
            $builder->from(static::class);
        }

        return $builder;
    }


    /**
     * @inheritdoc
     */
    function update($data = null, $whiteList = null)
    {
        is_string($whiteList) && $whiteList = explode(',', str_replace(' ', '', $whiteList));
        $skipAttributes = array_diff($this->getModelsMetaData()->getAttributes($this), $whiteList) ? : [];

        $this->skipAttributesOnUpdate($skipAttributes);
        parent::update($data);
    }


    /**
     * Get model attributes
     *
     * @return array
     */
    public static function getAttributes()
    {
        $instance = new static();
        $metaData = $instance->getModelsMetaData();
        return $metaData->getAttributes($instance);
    }


    static function getFullTextSearchFields()
    {
        return '';
    }

    public function getAvatar()
    {
        global $config;
        return $config->media->host . $this->avatar;
    }

    /**
     * @param array|string $ignore
     *
     * @return array
     */
    public static function getFieldProperties($ignore = '')
    {

        $props = array_keys(get_class_vars(static::class));
        $props = array_filter($props, function ($item) {
            return $item[0] === '_' ? null : $item;
        });

        is_string($ignore) && $ignore = explode(',', str_replace(' ', '', $ignore));
        $ignore != null && $props = array_diff($props, $ignore);

        return $props;
    }
}