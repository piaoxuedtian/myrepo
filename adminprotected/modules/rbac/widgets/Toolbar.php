<?php

/**
 * 工具栏
 */
class Toolbar extends CWidget {

    public $outerTag = 'ul';
    public $outerHtmlOptions = array();
    public $innerTag = 'li';
    public $innerHtmlOptions = array();
    public $items = array();

    public function run() {
        echo CHtml::openTag($this->outerTag, $this->outerHtmlOptions);
        foreach ($this->items as $item) {
            if (isset($item['visiable']) && $item['visiable'] === false) {
                continue;
            }
            $linkHtmlOptions = (isset($item['htmlOptions'])) ? $item['htmlOptions'] : array();
            if ($item['url'] == '#') {
                $item['url'] = 'javascript: void(0);';
                $this->innerHtmlOptions['class'] = 'search-button';
            }
            echo CHtml::openTag($this->innerTag, array_merge($this->innerHtmlOptions, $linkHtmlOptions));
            echo CHtml::link("<em>{$item['label']}</em>", $item['url'], $linkHtmlOptions);
            echo CHtml::closeTag($this->innerTag);
        }
        echo CHtml::closeTag($this->outerTag);
        Yii::app()->clientScript->registerScript('search-button-toggle', '$(".search-button").click(function(){$(".search-button").toggleClass("active");return false;});');
    }

}