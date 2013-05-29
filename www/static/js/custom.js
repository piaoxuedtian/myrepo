jQuery(function($){
	jQuery('a[rel="tooltip"]').tooltip();
	jQuery('a[rel="popover"]').popover();
	

	$('.upload .btn').click(function(){
		$('#upload-input').remove();
		$(this).parent().append('<input type="file" id="upload-input" name="upload_file" style="display:none;" />');
		$('#upload-input').click();
	});

	$('#upload-input').live('change',function(){
		var parent = $(this).parent();
		var btn = parent.children('.btn');
		var type = btn.attr('data-type')!='undefined'? btn.attr('data-type'):'all';
		var controller = btn.attr('data-controller')!=undefined? btn.attr('data-controller'):'other';
		var old_url = parent.children('.upload-val').val();
		$.ajaxFileUpload({
			url:'/contract/ajaxuploadfile?v='+new Date().getTime(),
			secureuri:false,
			data:{filename:'upload_file',type:type,controller:controller,old_url:old_url},
			fileElementId:'upload-input',
			dataType: 'json',
			success: function (data)
			{
				parent.children('.upload-text').html("");
				if(typeof(data.error) !=undefined)
				{
					if(data.error != '')
					{
						parent.children('.upload-msg').css('color','red').html(data.error);
					}
					else
					{
						parent.children('.upload-val').val(data.msg.url);
						parent.children('.upload-del').css('display','bolock');
						parent.children('.upload-msg').css('color','green').html( '上传成功!' );
					}
				}
				else
				{
						//$('#'+file_name+'_img_suc').hide();
				}
			},
			error: function (data, status, e)
			{
				alert(e);
			}
		});
	});

	//删除文件
	$('.upload-delete').click(function(){
		var parent = $(this).parent();
		if(parent.children('.upload-val').val()!=''){
			$.post('/contract/ajaxdeletefile',{file_url:parent.children('.upload-val').val()},function(data){
				if(data='1'){
					parent.children('.upload-msg').css('color','green').text('删除成功!');
					parent.children('.upload-val').val('');
				}else{
					alert(data);
				}
			})
		}else{
			alert('没有上传文件');
		}
	});
});