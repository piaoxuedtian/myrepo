<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="<?php echo Yii::app()->charset?>">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Le styles -->
	<script src="<?php echo Yii::app()->homeUrl; ?>assets/js/jquery-1.10.0.min.js"></script>
	<link href="<?php echo Yii::app()->homeUrl; ?>assets/css/yii.css" rel="stylesheet">

	<link href="<?php echo Yii::app()->homeUrl; ?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<script src="<?php echo Yii::app()->homeUrl; ?>assets/bootstrap/js/bootstrap.min.js"></script>
	<title><?php echo CHtml::encode(Yii::app()->name); ?></title>
</head>

<body>
<!-- top menu -->
<?php $this->widget('bootstrap.widgets.TbNavbar', $topMenu); ?>
<div class="row-fluid" style="height:100%;">
	<!-- menu -->
	<div class="span2" id="container-menu" style="height:100%;">
		<?php $this->widget('bootstrap.widgets.CMenu',$menu); ?>
	</div>
	<!-- content -->
	<div class="span10">
		<iframe name="mainFrame" style="min-height:800px;width:100%;overflow:visible;" src="<?php echo Yii::app()->createUrl('site/welcome')?>" frameborder="0"></iframe>
	</div>
</div>
<script type="">
jQuery(function($){
	$('#nav li a.brand').click(function(){
		if($(this).parent().hasClass("active")){
			$(this).next('ul').hide();
			$(this).parent().removeClass("active");
		}else{
			$(this).next('ul').show();
			$(this).parent().addClass("active");
		}
	});
});
</script>
</body>
</html>