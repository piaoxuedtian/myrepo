<?php
class CListView extends CWidget
{
	public $itemView;  // template name
	public $columns;   // 列
	public $dataProvider;
	public $cssFile;
	public $ajaxUpdate;
	public $updateSelector;
	public $ajaxVar='ajax';  //
	public $ajaxUrl;
	public $baseScriptUrl; // base js file path
	public $pager=array('class'=>'CLinkPager');
	public $summaryText;  // 记录总数显示格式
	public $emptyText;	// 数据为空时显示内容
	public $operates = array('view', 'update', 'delete'); // 操作列表
	public $batch_opes = array('batch_del' => '删除'); // 批量操作
	
	private $_data = array(); // 整个物件显示的数据
	
	/**
	 * Initializes the list view.
	 * This method will initialize required property values and instantiate {@link columns} objects.
	 */
	public function init()
	{
		if($this->itemView === NULL)
		{
			$this->itemView = '../common/_list';
		}
		if($this->dataProvider === NULL)
			throw new CException(Yii::t('zii','The "dataProvider" property cannot be empty.'));
	}

	/**
	 * Renders the view.
	 * This is the main entry of the whole view rendering.
	 * Child classes should mainly override {@link renderContent} method.
	 */
	public function run()
	{
		$this->registerClientScript();

		$this->renderContent();

		if(($count=$this->dataProvider->getItemCount())>0)
		{
			$owner=$this->getOwner();
			$render=$owner instanceof CController ? 'renderPartial' : 'render';
			$owner->$render($this->itemView, $this->_data);
		}
	}

	/**
	 * Renders the main content of the view.
	 * The content is divided into sections, such as summary, items, pager.
	 * Each section is rendered by a method named as "renderXyz", where "Xyz" is the section name.
	 * The rendering results will replace the corresponding placeholders in {@link template}.
	 */
	public function renderContent()
	{
		$this->renderItems();
		$this->renderPager();
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

	/**
	 * Renders the data item list.
	 */
	public function renderItems()
	{
		$data=$this->dataProvider->getData();
		if(($n=count($data))>0)
		{
			$this->_data['operates']=$this->operates;
			$this->_data['batch_opes']=$this->batch_opes;
			$this->_data['data']=$data;
			$this->_data['columns']=$this->columns;
		}
		else
		{
			$this->renderEmptyText();
		}
	}
	
	/**
	 * Renders the empty message when there is no data.
	 */
	public function renderEmptyText()
	{
		$emptyText=$this->emptyText===null ? Yii::t('zii','No results found.') : $this->emptyText;
		echo CHtml::tag('span', array('class'=>'empty'), $emptyText);
	}

	/**
	 * Renders the pager.
	 */
	public function renderPager()
	{
		if(($count=$this->dataProvider->getItemCount())<=0)
			return;

		$pagination=$this->dataProvider->getPagination();
		$total=$this->dataProvider->getTotalItemCount();
		$start=$pagination->currentPage*$pagination->pageSize+1;
		$end=$start+$count-1;
		if($end>$total)
		{
			$end=$total;
			$start=$end-$count+1;
		}
		$this->_data['pages'] = array(
			'start'=>$start,
			'end'=>$end,
			'count'=>$total,
			'page'=>$pagination->currentPage+1,
			'pages'=>$pagination->pageCount,
		);		
		
		$pager=array();
		if(is_string($this->pager))
		{
			$class=$this->pager;
		}
		else if(is_array($this->pager))
		{
			$pager=$this->pager;
		}
		$pager['pages']=$this->dataProvider->getPagination();
		
		if($pager['pages']->getPageCount()>1)
		{
			$this->_data['pager'] = $pager;
		}
	}
}