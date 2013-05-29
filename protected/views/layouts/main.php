<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="<?php echo $this->keywords; ?>" />
    <meta name="description" content="<?php echo $this->description; ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->homeUrl; ?>css/base.css" />
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
</head>
<body>
<div id="wrap">
<?php $this->renderPartial('../common/header', array(
	//'data'=>$model,
)); ?>
    
<?php echo $content; ?>

<?php $this->renderPartial('../common/footer', array(
	//'data'=>$model,
)); ?>

</div>
</body>
</html>