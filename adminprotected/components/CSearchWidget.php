<?php
class CSearchWidget extends CWidget
{
	public $itemView;  // template name
	public $ajaxUpdate;
	public $updateSelector;
	public $ajaxVar='ajax';  //
	public $ajaxUrl;
	public $baseScriptUrl; // base js file path
	public $keywords = '请输入关键字'; // 搜索关键字
	public $droplists = array(); // 所有下拉列表
	
	private $_data = array(); // 整个物件显示的数据
	
	/**
	 * Initializes the list view.
	 * This method will initialize required property values and instantiate {@link columns} objects.
	 */
	public function init()
	{
		if($this->itemView === NULL)
		{
			$this->itemView = '../common/_search';
		}
	}

	/**
	 * Renders the view.
	 * This is the main entry of the whole view rendering.
	 * Child classes should mainly override {@link renderContent} method.
	 */
	public function run()
	{
		$this->registerClientScript();

		$this->_data['droplists']=$this->droplists;

		$owner=$this->getOwner();
		$render=$owner instanceof CController ? 'renderPartial' : 'render';
		$owner->$render($this->itemView, $this->_data);
	}

	/**
	 * Registers necessary client scripts.
	 */
	public function registerClientScript()
	{
		$id=$this->getId();

		if($this->ajaxUpdate===false)
			$ajaxUpdate=array();
		else
			$ajaxUpdate=array_unique(preg_split('/\s*,\s*/',$this->ajaxUpdate.','.$id,-1,PREG_SPLIT_NO_EMPTY));
		$options=array(
			'ajaxUpdate'=>$ajaxUpdate,
			'ajaxVar'=>$this->ajaxVar,
		);
		if($this->ajaxUrl!==null)
			$options['url']=CHtml::normalizeUrl($this->ajaxUrl);

		$options=CJavaScript::encode($options);
		$cs=Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');
		$cs->registerCoreScript('bbq');
		$cs->registerScriptFile($this->baseScriptUrl.'/jquery.yiilistview.js',CClientScript::POS_END);
		$cs->registerScript(__CLASS__.'#'.$id,"jQuery('#$id').yiiListView($options);");
	}
}
