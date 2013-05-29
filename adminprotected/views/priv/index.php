<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->homeUrl; ?>css/pager.css" />
<script type='text/javascript' src="<?php echo Yii::app()->homeUrl; ?>js/jquery.js"></script>
<script type='text/javascript' src="<?php echo Yii::app()->homeUrl; ?>js/list.js"></script>
<?php
$this->breadcrumbs=array(
	'Admins'=>array('index'),
	'Manage',
);
?>
<?php
$this->widget('CSearchWidget', array(
	'droplists' => array(
		'role' => array(
			'0' => '请选择角色',
			'username' => '用户名',
			'password' => '密码',
			'salt' => '',
			'email' => '邮箱',
			'profile' => '个人简介',
		),
	),
));
$this->widget('CListView', array(
  'dataProvider'=>$dataProvider,
  'columns' => array(
	  'id' => 'id号',
	  'pname' => '权限字段',
	  'pid' => '父id',
  ),
)); ?>
