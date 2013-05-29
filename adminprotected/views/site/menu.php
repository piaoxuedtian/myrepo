<style type="text/css">
/*side*/
body{margin:0;}
dl{list-style-type:none; margin:0;}
.side_tit{ background:url(<?php echo Yii::app()->homeUrl; ?>images/side_tit.gif) no-repeat; width:100%; height:35px; overflow:hidden;}
.side_tit h2{ height:35px; line-height:20px; font-size:12px; font-weight:400; margin-left:15px;}
#menu{cursor:pointer; width:190px; overflow:hidden;}
#menu a{ display:inline-block; color:#333; text-decoration: none;}
#menu dt{ background:#fff;font-size:14px; font-weight:600; margin:0; line-height:30px; text-decoration:none;}
#menu dt.open{ background:#F7F7F7;}
#menu dt.open span{background:url(<?php echo Yii::app()->homeUrl; ?>images/s_ico.gif) -7px 0px no-repeat;}
#menu dt span{background:url(<?php echo Yii::app()->homeUrl; ?>images/s_ico.gif) no-repeat; width:6px; height:8px; display:inline-block; margin:12px 11px 0 9px; cursor:hand;}
#menu dd{ line-height:22px; font-size:12px; margin:0; display:none;}
#menu dd a{padding:0px 40px 0px; display:block;}
#menu dd a:hover{color:#fff; background:#F08223; font-size:14px;}
#menu dd a.cur{ color:#fff; background:#F08223; font-size:14px;}
</style>
<script type="text/javascript" src="<?php echo Yii::app()->homeUrl; ?>js/jquery.js"></script>
<script type="text/javascript">
 $(function(){
	$("#menu dt").click(function(){
		if($(this).attr('class') == 'open')
		{ 
			$(this).next("dd").hide();
			$(this).removeClass('open');
		}
		else
		{
			$(this).next("dd").show();
			$(this).addClass('open')
		}
	});
	
	$("#menu dd a").click(function(){
		$("#menu dd a").removeClass('cur');
		$(this).addClass('cur')
	});
  }); 
</script>
  <div class="side_tit">
	  <h2 class="ml20">收起目录</h2>
  </div>
  <dl id="menu">
	<dt><span></span>用户管理</dt>
	<dd>
	  <a href="<?php echo Yii::app()->homeUrl; ?>user/index" target="mainFrame">用户列表</a>
	  <a href="<?php echo Yii::app()->homeUrl; ?>user/create" target="mainFrame">添加用户</a>
	</dd>
	<dt><span></span>文章管理</dt>
	<dd>
	  <a href="<?php echo Yii::app()->homeUrl; ?>article/index" target="mainFrame">文章列表</a>
	  <a href="<?php echo Yii::app()->homeUrl; ?>article/create" target="mainFrame">发布文章</a>
	  <a href="<?php echo Yii::app()->homeUrl; ?>category/index" target="mainFrame">文章分类</a>
	  <a href="<?php echo Yii::app()->homeUrl; ?>category/create" target="mainFrame">添加分类</a>
	</dd>
	<dt><span></span>订单管理</dt>
	<dd>
	  <a href="<?php echo Yii::app()->homeUrl; ?>order/index" target="mainFrame">订单列表</a>
	</dd>
	<dt><span></span>结算管理</dt>
	<dd>
	  <a href="<?php echo Yii::app()->homeUrl; ?>settle/index" target="mainFrame">结算列表</a>
	  <a href="<?php echo Yii::app()->homeUrl; ?>settle/create" target="mainFrame">结算提交</a>
   </dd>
	<dt><span></span>权限管理</dt>
	<dd>
	  <a href="<?php echo Yii::app()->homeUrl; ?>admin/index" target="mainFrame">管理员列表</a>
	  <a href="<?php echo Yii::app()->homeUrl; ?>admin/create" target="mainFrame">添加管理员</a>
	  <a href="<?php echo Yii::app()->homeUrl; ?>role/index" target="mainFrame">角色列表</a>
	  <a href="<?php echo Yii::app()->homeUrl; ?>priv/index" target="mainFrame">权限列表</a>
   </dd>
	<dt><span></span>系统管理</dt>
	<dd>
	  <a href="<?php echo Yii::app()->homeUrl; ?>navigate/index" target="mainFrame">导航管理</a>
	  <a href="<?php echo Yii::app()->homeUrl; ?>region/index" target="mainFrame">区域管理</a>
	  <a href="<?php echo Yii::app()->homeUrl; ?>focuspic/index" target="mainFrame">焦点大图</a>
   </dd>
	<dt><span></span>广告管理</dt>
	<dd>
	  <a href="<?php echo Yii::app()->homeUrl; ?>advert/index" target="mainFrame">广告位置</a>
	  <a href="<?php echo Yii::app()->homeUrl; ?>advert/index" target="mainFrame">广告列表</a>
	  <a href="<?php echo Yii::app()->homeUrl; ?>advert/index" target="mainFrame">友情连接</a>
   </dd>
  </dl>