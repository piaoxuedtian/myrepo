<?php

/**
 * CArrayDataProvider 数据过滤
 */
class FilterArrayForm extends CFormModel {

    public $filters = array();

    /**
     * Override magic getter for filters
     */
    public function __get($name) {
        if (!array_key_exists($name, $this->filters))
            $this->filters[$name] = null;
        return $this->filters[$name];
    }

    /**
     * Filter input array by key value pairs
     * @param array $data rawData
     * @return array filtered data array
     */
    public function filter(array $data) {
        foreach ($data as $i => $row) {
            foreach ($this->filters as $key => $value) {
                // unset if filter is set, but doesn't match
                if (array_key_exists($key, $row) && (is_numeric($value) || !empty($value))) {
                    if (stripos($row[$key], $value) === false) {
                        unset($data[$i]);
                    }
                }
            }
        }

        return $data;
    }

}