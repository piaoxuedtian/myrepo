<?php

/**
 * CButtonColumn Ext
 */
class CButtonColumnExt extends CButtonColumn {

    public $urlArgs = array();

    public function init() {
        $buttonImageUrl = ((isset(Yii::app()->controller->module)) ? Yii::app()->controller->module->assetsUrl : (Yii::app()->baseUrl . '/static')) . '/images/';
        $this->viewButtonImageUrl = $buttonImageUrl . 'view.png';
        $this->updateButtonImageUrl = $buttonImageUrl . 'update.png';
        $this->deleteButtonImageUrl = $buttonImageUrl . 'delete.png';
        $urlArgs = '';
        if (!empty($this->urlArgs) && is_array($this->urlArgs)) {
            foreach ($this->urlArgs as $key => $value) {
                $urlArgs .= "\"$key\" => \"$value\",";
            }
        }
        $urlArgs .= '"parent" => $data["name"]';
        $this->updateButtonUrl = 'Yii::app()->controller->createUrl("update",array(' . $urlArgs . '))';
        $this->viewButtonUrl = 'Yii::app()->controller->createUrl("view",array(' . $urlArgs . '))';
        $this->deleteButtonUrl = 'Yii::app()->controller->createUrl("delete",array(' . $urlArgs . '))';
        $this->header = '操作';
        parent::init();
    }

}