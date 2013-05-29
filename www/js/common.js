// JavaScript Document
$(function(){
  // 头部搜索框
  $("#keywords").focus(function(){
	  if($(this).val() == '菊花台')
	  {
		  $(this).val('');
	  }
  });
  $("#keywords").blur(function(){
	  if($(this).val() == '')
	  {
		  $(this).val('菊花台');
	  }
  });
  
  // 搜索下拉框
  $(".type_text").click(function(){
	  $('.type_select').css('display','block');
  });
  $('.type_select li').click(function(){
	  $('.type_select').css('display','none');
	  $('.type_text').html($(this).html());
	  $('#type').val($(this).attr('typeid'));
  });
});

function addBookmark(a,b)
{
    try{ window.external.AddFavorite(a,b); } catch(e){ (window.sidebar)?window.sidebar.addPanel(b,a,""):alert('请使用按键 Ctrl+d，收藏本站'); }
} 
