<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo CHtml::encode(Yii::app()->name); ?></title>
</head>
<frameset rows="75,*" frameborder="0" framespacing="0">
  <frame src="<?php echo Yii::app()->homeUrl; ?>site/top" scrolling="no">
  <frameset cols="190,6,*" frameborder="0" framespacing="0">
    <frame src="<?php echo Yii::app()->homeUrl; ?>site/menu">  
    <frame src="<?php echo Yii::app()->homeUrl; ?>site/side">  
    <frame src="<?php echo Yii::app()->homeUrl; ?>site/default" name="mainFrame">
  </frameset>
</frameset><noframes></noframes>
</html>