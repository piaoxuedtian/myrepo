jQuery(function($) {
	// 选择商户
	$('#choose-customer').click(function(){
		$(this).colorbox({href:$(this).attr('href')+'&v='+ new Date().getTime(),iframe:true,innerWidth:650,innerHeight:500,fastIframe:true});
	});
	// 选择联系人
	$('#choose-contacter').click(function(){
		if($('#Contract_customer_id').val() == '' || $('#Contract_bus_oppo_id').val() == ''){
			
			alert('请先选择联商户');
		}else{
			$.colorbox({href:$(this).attr('href')+'?Contacter[bus_oppo_id]='+$('#Contract_bus_oppo_id').val()+'&v='+ new Date().getTime(),iframe:true,innerWidth:650,innerHeight:500});
		}
		return false;
	});
	// 增加协议时选择合同
	$('#choose-contract').click(function(){
		$(this).colorbox({href:$(this).attr('href')+'?v='+ new Date().getTime(),iframe:true,innerWidth:650,innerHeight:500});
	})
	// 显示、隐藏子合同table
	$(".checkContractType").each(function(){
		if($(this).attr('checked') == 'checked'){
			$('#content'+ $(this).val()).show();
		}else{
			$('#content'+ $(this).val()).hide();
		}
	});
	$('.checkContractType').click(function(){
		if($(this).attr('checked') == 'checked'){
			$('#content'+ $(this).val()).show();
		}else{
			$('#content'+ $(this).val()).hide();
		}
	});
	
	$("#Contract_cust_acc_info_id").change(function(){
		$.getJSON("/contract/AjaxGetAccoutInfoById", {id:$(this).val(),v:new Date().getTime()},function(data){
			$('#Contract_cust_acc_info_id').val(data.cust_acc_info_id);
			$('#Contract_cust_acc_name').text(data.name);
			$('#Contract_cust_acc_type').val(data.type);
			$('#Contract_cust_acc_province').text(data.province);
			$('#Contract_cust_acc_city').text(data.city);
			$('#Contract_cust_acc_opening_bank_name').text(data.opening_bank_name);
			$('#Contract_cust_acc_sub_opening_bank').text(data.sub_opening_bank);
			$('#Contract_cust_acc_accout').text(data.accout);
		});
	});
	
	$('#add-cccout-info').click(function(){
		$('#CustomerBankAccount_bus_oppo_id').val($('#Contract_bus_oppo_id').val());
		$('#CustomerBankAccount_customer_id').val($('#Contract_customer_id').val());

		$('#CustomerBankAccount_name').val('');
		//$('#CustomerBankAccount_province').val('');
		//$('#CustomerBankAccount_city').val('');
		$('#CustomerBankAccount_opening_bank_name').val('');
		$('#CustomerBankAccount_sub_opening_bank').val('');
		$('#CustomerBankAccount_sub_code').val('');
		$('#CustomerBankAccount_bank_code').val('');
		$('#CustomerBankAccount_accout').val('');
		if($('#Contract_customer_id').val() == '' || $('#Contract_bus_oppo_id').val() == ''){
			alert('请先选择商户');
		}else{
			//var query = 'customer_id='+$('#Contract_customer_id').val()+'&bus_oppo_id='+$('#Contract_bus_oppo_id').val();
			
			$('#myModal').modal({show:true});
		}
	});
	
	// 选择银行名称时，自动将银行代码添入隐藏表单
	$("#CustomerBankAccount_opening_bank_name").change(function(){
		if ($(this).selected().val() != '')
		{
			$("#CustomerBankAccount_bank_code").val($(this).selected().val());
		}
	});
	$('#submit-accout-info').click(function(){
		$.post("/customerbankaccount/create", $("#customer-accout-info-form").serialize(),function(data){
			if(data == '0'){
				alert('添加收款账户失败');
			}else if(data == '-1'){
				alert('请输入正确的账号信息');
			}else{
				// 收款账号增加一个下拉选项
				$("#Contract_cust_acc_info_id").prepend("<option value='"+data.cust_acc_info_id+"' selected='selected'>"+$('#CustomerBankAccount_name').val()+"</option>");
				$('#Contract_cust_acc_info_id').val(data.cust_acc_info_id);
				$('#Contract_cust_acc_name').text($('#CustomerBankAccount_name').val());
				$('#Contract_cust_acc_type').val($('#CustomerBankAccount_type').val());
				$('#Contract_cust_acc_province').text(data.province);
				$('#Contract_cust_acc_city').text(data.city);
				$('#Contract_cust_acc_opening_bank_name').text(data.opening_bank_name);
				$('#Contract_cust_acc_sub_opening_bank').text(data.sub_opening_bank);
				$('#Contract_cust_acc_accout').text($('#CustomerBankAccount_accout').val());
				$('#myModal').modal('hide');
			}
		},'json');
		return false;
	});
	
	$('#Contract_is_settle_accounts_0,#Contract_is_settle_accounts_1').change(function(){
		if($(this).val() == 1){
			$('#Contract_settle_accounts_type').removeAttr('disabled');
			
			if($('#Contract_settle_accounts_type').val()==1){
				$('#Contract_account_rate').removeAttr('disabled');
			}else{
				$('#Contract_account_rate').attr('disabled', 'disabled');
			}
		}else if($(this).val() == 0){
			$('#Contract_settle_accounts_type').attr('disabled', 'disabled');			
			$('#Contract_account_rate').attr('disabled', 'disabled');			
		}
	});
	
	$('#Contract_settle_accounts_type').change(function(){
		if($('#Contract_is_settle_accounts_0').attr('ckecked') == 'checked'){
			$('#Contract_account_rate').attr('disabled', 'disabled');   
		}else{
			if($(this).val() == 0){
				$('#Contract_account_rate').attr('disabled', 'disabled');
			}else{
				$('#Contract_account_rate').removeAttr('disabled');				
			}
		}
	});
	
	$('.opening-time').change(function(){
		if($(this).attr('id').match('_open_start') == '_open_start' && parseInt($(this).val())>=parseInt($(this).next('select').val())){
			alert('营业开始时间必须小于结束时间');
			$(this).val(0);
		}else if(parseInt($(this).val())<=parseInt($(this).prev('select').val())){
			alert('营业结束时间必须大于开始时间');
			$(this).val(24);
		}
	});
	
	$('#contentList_1_province,#contentList_2_province,#contentList_3_province,#contentList_4_province').change(function(){
		var next = $(this).next('select');
		$.getJSON('/contract/AjaxGetCitysByProvinceId',{province:$(this).val(),v:new Date().getTime()},function(data){
			next.empty();
			for(var k in data){
				next.append("<option value='" + k + "'>"+ data[k] + "</option>");
			}
		});
	});
});

	$('#Contract_is_settle_accounts_0,#Contract_is_settle_accounts_1').each(function(){
		if($(this).attr('checked') == 'checked'){
			if($(this).val() == 0){
				$('#Contract_settle_accounts_type').attr('disabled', 'disabled');
				$('#Contract_account_rate').attr('disabled', 'disabled');
			}else if($(this).val() == 1 && $('#Contract_settle_accounts_type').val()==0){
				$('#Contract_account_rate').attr('disabled', 'disabled');
			}
		}
	});

	
function addAdsPositon(){
	var positionIndex = $('#serviceList div').length;
	var _html = '<div><select style="width:100px;" name="contentList[8][serviceList]['+positionIndex+'][add_ad_1]">\n\
				<option value="1">网站首页</option>\n\
				<option value="2">频道首页</option>\n\
				<option value="3">套餐1</option>\n\
				<option value="4">套餐2</option>\n\
				<option value="5">套餐3</option>\n\
			</select><select style="width:100px;" name="contentList[8][serviceList]['+positionIndex+'][add_ad_2]">\n\
				<option value="1">顶通</option>\n\
				<option value="2">中通</option>\n\
				<option value="3">右侧</option>\n\
			</select>\n\
			<select style="width:100px;" name="contentList[8][serviceList]['+positionIndex+'][add_ad_3]">\n\
				<option value="1">1号位</option>\n\
				<option value="2">2号位</option>\n\
				<option value="3">3号位</option>\n\
				<option value="4">4号位</option>\n\
				<option value="5">5号位</option>\n\
			</select>&nbsp;&nbsp;上线时长<span class="required">*</span>:\n\
			<input name="contentList[8][serviceList]['+positionIndex+'][duration]" type="text">\n'
			+(positionIndex>0?'<input type="button" class="btn" value="删除" onclick="deleteAdsPosition(this)"></div>':'');
	$("#serviceList").append(_html);
}
function deleteAdsPosition(obj){
	$(obj).parent().remove();
}