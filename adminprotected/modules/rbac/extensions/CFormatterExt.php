<?php

class CFormatterExt extends CFormatter {

    /**
     * 格式化类型
     * @param integer $value
     * @return string
     */
    public function formatTypeName($value) {
        switch ($value) {
            case 0:
                return '动作';
                break;
            case 1:
                return '任务';
                break;
            case 2:
                return '角色';
                break;
            default:
                return null;
                break;
        }
    }

}