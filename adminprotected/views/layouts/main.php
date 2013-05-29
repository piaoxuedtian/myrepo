<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<!-- Le styles -->
	<link href="<?php echo Yii::app()->homeUrl; ?>assets/css/bootstrap-yii.css" rel="stylesheet">
	<script src="<?php echo Yii::app()->homeUrl; ?>assets/js/jquery-1.10.0.min.js"></script>

	<link href="<?php echo Yii::app()->homeUrl; ?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<script src="<?php echo Yii::app()->homeUrl; ?>assets/bootstrap/js/bootstrap.min.js"></script>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body>
<div class="container-fluid">
<?php
if(isset($this->breadcrumbs))
	$this->widget('bootstrap.widgets.TbBreadcrumbs', array(
		'links'=>$this->breadcrumbs,
	)); ?><!-- breadcrumbs -->
<?php echo $content; ?>
</div><!-- page -->
</body>
</html>