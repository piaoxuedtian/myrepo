<?php

Yii::import('zii.widgets.grid.CGridView');

class CGridViewExt extends CGridView {

    public $cssFile = false;
    public $pager = array(
        'class' => 'rbac.extensions.CLinkPagerExt',
        'htmlOptions' => array(
            'class' => 'rbac-yiiPager',
        ),
    );
    public $loadingCssClass = 'rbac-grid-view-loading';
    public $htmlOptions = array(
        'class' => 'rbac-grid-view',
    );

//    public $itemsCssClass = 'table table-striped table-bordered table-condensed';

    public function init() {
        parent::init();
        $selectRow = <<<EOT
$(function () {
    var checked = this.checked;
    $("#{$this->id} table tr td").each(function () {
        $(this).mousedown(function () {
            $("input:checkbox").attr('checked', 'checked');
        });
    });
});
EOT;
//        echo CHtml::script($selectRow);
    }

    public function renderBatchDelete() {
        if (count($this->dataProvider->getData())) {
            $deleteUrl = Yii::app()->controller->createUrl("delete");

            $name = $this->id . '_c0';
            if (substr($name, -2) !== '[]')
                $name.='[]';
//                $this->checkBoxHtmlOptions['name'] = $name;
            $name = strtr($name, array('[' => "\\[", ']' => "\\]"));

            $content = '<span style="padding: 7px 5px; margin-left: 1px;"><input id="' . $this->id . '_check_all" type="checkbox" /></span><a class="grid-view-embed-btn btn-del m-t-1 clear" id="batch-delete-all-selected-items" href="javascript: void(0)">批量删除</a>';
            echo CHtml::tag('div', array('class' => 'action-buttons-bar'), $content, 'div');
            $cball = <<<CBALL
$('#{$this->id}_check_all').live('click',function() {
	var checked=this.checked;
	$("input[name='{$name}']").each(function() {this.checked=checked;});
});
CBALL;

            $scriptText = <<<EOT
$(function() {
    {$cball}
    $("#batch-delete-all-selected-items").live("click", function() {
        ids = $.fn.yiiGridView.getChecked("{$this->id}", "{$this->id}_c0[]");
        if (ids == "") {
            alert("请选择您要删除的数据!");
            return false;
        } else {
            if (!confirm("您确定要删除所有选择的数据吗？")) return false;
            var th = this;
            var afterDelete = function() {};
            $.fn.yiiGridView.update("{$this->id}", {
                type:"POST",
                url:"{$deleteUrl}",
                data: "&id=" + ids,
                success:function(data) {
                    $.fn.yiiGridView.update("{$this->id}");
                    afterDelete(th, true, data);
                },
                error:function() {
                    return afterDelete(th, false);
                }
            });
            return false;
        }
    });
});
EOT;
            echo CHtml::script($scriptText);
        }
    }

}