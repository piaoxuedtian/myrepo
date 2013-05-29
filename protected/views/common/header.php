<div class="header_wrap">
    <div class="header">
        <a href="<?php echo Yii::app()->homeUrl; ?>/" bk id="tuan_logo" class="logo"><img style="margin:20px 0 0;" src="images/logo.png" alt="yueyoo乐友之家" /></a>
        <!--<div class="change_city" >
            <strong id="open-citys"><span>北京</span><a  href="#" class="change_btn"></a></strong>
            <div class="tips-pop" id="tips_pop" style="display:none;">
                <p></p>
                <a href="#" title="关闭" class="close" id="tips_close">关闭</a>
                <em></em>
            </div>
        </div>
        -->

        <div class="search_bar">
            <div class="search_fields">
                <fieldset>
                    <legend>搜索</legend>
                    <form method="get" action="<?php echo Yii::app()->homeUrl; ?>/index.php" id="tsearch">
                        <input  class="txt" type="text" id="keywords" name="kw" value="菊花台" />
                        <div class="type_text">歌名</div>
                        <div class="type_select">
                            <ul>
                                <li typeid='0'>歌名</li>
                                <li typeid='1'>歌手</li>
                            </ul>
                        </div>
                        <input type="hidden" value="0" id="type" name="type"/>
                        <input class="search" type="submit" value="搜索" /><span class="shadow"></span>
                        <input type="hidden" value="bei_jing" id="city" name="city"/><input type="hidden" value="search" name="do"/>                    </form>
                </fieldset>
            </div>
            <div class="hot_words" bk id="tuan_hotword">
              <a  href='<?php echo Yii::app()->homeUrl; ?>/?do=dazhaxie2012'   target="_blank" > <font color='#fb1e1e'>高山流水 </font></a>
              <a  href='<?php echo Yii::app()->homeUrl; ?>/?do=category&clazz=102'   target="_blank" >二泉音乐 </a>
              <a  href='<?php echo Yii::app()->homeUrl; ?>/?do=category&clazz=203'   target="_blank" > <font color='black'>阳春白雪 </font></a>
              <a  href='<?php echo Yii::app()->homeUrl; ?>/?do=category&clazz=212'   target="_blank" > <font color='#1781ee'>枉凝眉 </font></a>
              <a  href='<?php echo Yii::app()->homeUrl; ?>/?do=category&clazz=113'   target="_blank" > <font color='black'>心语 </font></a>
              <a  href='<?php echo Yii::app()->homeUrl; ?>/?do=category&clazz=204'   target="_blank" > <font color='black'>水中花 </font></a>
              <a  href='<?php echo Yii::app()->homeUrl; ?>/?do=category&clazz=4010405'   target="_blank" > <font color='#1ea814'>十面埋伏 </font></a>
              <a  href='<?php echo Yii::app()->homeUrl; ?>/?do=category&clazz=4010111'   target="_blank" > <font color='black'>西游记 </font></a>
            </div>
        </div>
        <div class="nav" bk id="tuan_nav">
          <ul class="clearfix">
            <li><a  href="<?php echo Yii::app()->homeUrl; ?>/"><span>首页</span></a></li>
            <li><a  href="<?php echo Yii::app()->homeUrl; ?>/bei_jing/c_1.html"><span>二胡</span></a></li>
            <li><a  href="<?php echo Yii::app()->homeUrl; ?>/bei_jing/c_2.html"><span>笛箫</span></a></li>
            <li><a  href="/?do=movie" ><span>钢琴</span><sup class="new"></sup></a></li>
            <li><a  href="<?php echo Yii::app()->homeUrl; ?>/bei_jing/c_7.html"><span>吉他</span></a></li>
            <li><a  href="<?php echo Yii::app()->homeUrl; ?>/bei_jing/c_4.html"><span>古筝</span></a></li>
            <li><a class="current" href="<?php echo Yii::app()->homeUrl; ?>/bei_jing/c_3.html"><span>葫芦丝</span></a></li>
            <li><a  href="<?php echo Yii::app()->homeUrl; ?>/bei_jing/c_4.html"><span>电子琴</span></a></li>
            <li><a  href="<?php echo Yii::app()->homeUrl; ?>/bei_jing/c_6.html"><span>其他</span></a></li>
          </ul>
        </div>
        <!--
        <div class="my_rss2">
            <a href="/?do=favorite">我喜欢的团购</a>
        </div>
        -->
        <div class="topbar" bk id="tuan_topbar">
                        <div style="display:none;" id="logined" class="reg">
                <span class="logined">您好，<strong id="userinfo"></strong>
                    <a href="http://i.yueyoo.cn" target="_blank">账号设置</a>
                    <a class="exit" href="/?do=logout" id="logout">退出</a>
                </span>
            </div>
            <div style="display:none;" id="logink" class="reg">
                <b>还没有yueyoo账号?</b><a class="reg_btn" href="http://i.yueyoo.cn/reg?src=tuan&destUrl=http%3A%2F%2Fwww.yueyoo.cn">注册</a><span>|</span>
                <a href="" id="userlogin">登录</a>
            </div>
                        <div class="quick_menu" bk id="quick_menu">
                <ul>
                    <li class="mytuan">
                    <a href="<?php echo Yii::app()->homeUrl; ?>/user">个人中心<span class="icon"></span></a>
                    <div class="menu_wrap">
                        <div class="menu_bd">
                            <a href="<?php echo Yii::app()->homeUrl; ?>/?do=listdeal" class="buy_record"><span></span>我的购买记录</a><a href="<?php echo Yii::app()->homeUrl; ?>/?do=bcapplylist" class="indemnity"><span></span>我的赔付管理</a><a href="<?php echo Yii::app()->homeUrl; ?>/?do=couponlist" class="coupon"><span></span>我的优惠券</a><a href="<?php echo Yii::app()->homeUrl; ?>/?do=listcare" class="remind"><span></span>我的团购提醒</a><a href="<?php echo Yii::app()->homeUrl; ?>/?do=awardlist" class="reward"><span></span>我的中奖纪录</a>
                        </div>
                    </div>
                    </li>
                    <!--<li class="map"><a href="<?php echo Yii::app()->homeUrl; ?>/bei_jing/?do=tuanmapv2" target="_blank">团购地图<sup class="new"></sup></a></li>-->
                    <li class="bbs"><a href="<?php echo Yii::app()->homeUrl; ?>/static/subject/mobile.html" target="_blank">手机版</a></li>
                    <li><a href="#" title="加为收藏" onclick="addBookmark('<?php echo Yii::app()->homeUrl; ?>','乐友网')" class="collect">加入收藏</a></li>
                    <li class="weibo" id="tuan_weibo">
                    
                    <script>
                        // Because it is blocking the page, so the last rendering, by judong 2012-02-27
                        (function($){
                            if(!$)return;
                            $(function(){
                                $('#tuan_weibo').html(' <iframe width="63" height="24" frameborder="0" allowtransparency="true" marginwidth="0" marginheight="0" scrolling="no" frameborder="No" border="0" src=" http://widget.weibo.com/relationship/followbutton.php?width=63&height=24&uid=1983941535&style=1&btn=red&dpc=1"></iframe>');
                            });
                        })(jQuery);
                    </script>
                    
                    </li>
                </ul>
            </div>
        </div>

        <div class="quick_nav">
            <a href="" target="_blank">教程</a>
            <a href="" target="_blank">视频</a>
            <a href="" target="_blank">乐器商城</a>
            <a href="" target="_blank">培训机构</a>
        </div>
          
        <div class="subject_entrance"><a href="<?php echo Yii::app()->homeUrl; ?>/?do=dazhaxie2012" target="_blank"><img src="" alt="" /></a></div>
        <div class="s_subject_entrance"><a href="<?php echo Yii::app()->homeUrl; ?>/?do=dazhaxie2012" target="_blank"><img src="" alt="" /></a></div>
 
        <div class="ac_entrance"><a href="http://e.weibo.com/1983941535/z02C5yyKC" target="_blank">挣积分,送</a></div>
</div>