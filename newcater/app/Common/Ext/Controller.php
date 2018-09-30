<?php

namespace Common\Ext;

use Common\Util\Arrays;

/**
 * Created by PhpStorm.
 * User: leth
 * Date: 4/23/18
 * Time: 10:01 PM
 *
 * @property \Phalcon\Http\Request request
 */
trait Controller
{

    /**
     * @param null $list_data_form
     *
     * @return array|mixed
     */
    public function getPost($list_data_form = null)
    {
        if ($list_data_form == null) {
            return $this->request->getPost();
        }

        $data = [];
        if (is_array($list_data_form) && !Arrays::isSeq($list_data_form)) {

            foreach ($list_data_form as $key => $type) {
                if ($type == null) $data[$key] = $this->request->get($key);
                else $data[$key] = $this->request->get($key, $type);
            }

        } else {
            $listKey = is_array($list_data_form)
                ? $list_data_form
                : explode(",", str_replace(" ", "", $list_data_form));
            foreach ($listKey as $type) {
                $data[$type] = $this->request->get($type, "string");
            }
        }

        return $data;
    }

}