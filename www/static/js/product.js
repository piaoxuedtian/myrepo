/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(function($) {
	//是否需要预约
	$('#ProductSaleInfo_is_need_order_0').click(function(){
		$('#ProductSaleInfo_order_telephone').attr('readonly', 'readonly');
		$('#ProductSaleInfo_order_limit_number').attr('readonly', 'readonly');
		$('#ProductSaleInfo_advance_order_days').attr('readonly', 'readonly');
	});
	$('#ProductSaleInfo_is_need_order_1').click(function(){
		$('#ProductSaleInfo_order_telephone').removeAttr('readonly');
		$('#ProductSaleInfo_order_limit_number').removeAttr('readonly');
		$('#ProductSaleInfo_advance_order_days').removeAttr('readonly');
	});
	//是否独家协议
	$('#ProductSaleInfo_is_exclusive_agreements_0').click(function(){
		$('#ProductSaleInfo_exclusive_sale_days').attr('readonly', 'readonly');
	})
	$('#ProductSaleInfo_is_exclusive_agreements_1').click(function(){
		$('#ProductSaleInfo_exclusive_sale_days').removeAttr('readonly');
	})


	//产品分类：快递类和非快递类
	$('#ProductSaleInfo_product_type_0').click(function(){
		$('#service_product').hide();
		$('#wms_product').show();
	})
	$('#ProductSaleInfo_product_type_1').click(function(){
		$('#service_product').show();
		$('#wms_product').hide();
	})
	//产品分类：快递类如果选中就隐藏非快递类的输入项
	if($('#ProductSaleInfo_product_type_0').attr('checked') == 'checked'){
		$('#service_product').hide();
		$('#wms_product').show();
	}
	if($('#ProductSaleInfo_product_type_1').attr('checked') == 'checked'){
		$('#service_product').show();
		$('#wms_product').hide();
	}

	//如果默认选中是否独家,把独家售卖天数设置为只读
	if($('#ProductSaleInfo_is_exclusive_agreements_0').attr('checked') == 'checked'){
		$('#ProductSaleInfo_exclusive_sale_days').attr('readonly', true);
	}
	//是否需要预约如果默认选中否,隐藏预约电话、每天限制人数、提前预约天数
	if($('#ProductSaleInfo_is_need_order_0').attr('checked') == 'checked'){
		//预约电话
		$('#ProductSaleInfo_order_telephone').attr('readonly',true);
		//每天限制人数
		$('#ProductSaleInfo_order_limit_number').attr('readonly',true);
		//提前预约天数
		$('#ProductSaleInfo_advance_order_days').attr('readonly',true);
	}
	//如果默认选中不是用55通设备,隐藏台数和设备押金
	if($('#ProductSaleInfo_is_use_55tong_0').attr('checked') == 'checked'){
		//台数
		$('#ProductSaleInfo_five_tong_number').attr('readonly',true);
		//押金
		$('#ProductSaleInfo_equipment_deposit').attr('readonly',true);
	}
	//如果默认要用55通设备
	if($('#ProductSaleInfo_is_use_55tong_1').attr('checked') == 'checked'){
		//台数
		$('#ProductSaleInfo_five_tong_number').removeAttr('readonly');
		//押金
		$('#ProductSaleInfo_equipment_deposit').removeAttr('readonly');
	}

	//是用55通设备
	$('#ProductSaleInfo_is_use_55tong_1').live('click',function(){
		//台数
		$('#ProductSaleInfo_five_tong_number').removeAttr('readonly');
		//押金
		$('#ProductSaleInfo_equipment_deposit').removeAttr('readonly');
	});
	//不用55通设备
	$('#ProductSaleInfo_is_use_55tong_0').live('click',function(){
		//台数
		$('#ProductSaleInfo_five_tong_number').attr('readonly',true);
		//押金
		$('#ProductSaleInfo_equipment_deposit').attr('readonly',true);
	});


 $('#ProductSaleInfo_open_start,#ProductSaleInfo_open_end').change(function(){
  if($(this).attr('id').match('_open_start') == '_open_start' && parseInt($(this).next('select').val()) != 0 && parseInt($(this).val())>=parseInt($(this).next('select').val())){
   alert('营业开始时间必须小于结束时间');
   $(this).val(0);
  }else if(parseInt($(this).val())<=parseInt($(this).prev('select').val())){
   alert('营业结束时间必须大于开始时间');
   $(this).val(24);
  }
 });


	// 选择商户
	$('#choose-customer,#choose-products').click(function(){
		if($(this).attr('id') == 'choose-products'){
			var customer_id = $('#BaseProduct_customer_id').val();
			var bus_oppo_id = $('#BaseProduct_bus_oppo_id').val();
			var base_contract_id = $('#BaseProduct_base_contract_id').val();
			if(customer_id == '' || bus_oppo_id == '' || base_contract_id == ''){
				alert('请选择商户');
				return false;
			}
			var href = $(this).attr('href');
			href = href+'?bus_oppo_id='+bus_oppo_id;
		}
		$(this).colorbox({
			href:href,
			iframe:true,
			innerWidth:700,
			innerHeight:500
		});
	});

	$('#baseclear').live('click',function(){
		$('#sub_type').val(0);
		$('#basesave').show();
	});

	//产品分类的联动
	$('#BaseProduct_classification_one,#BaseProduct_classification_two,#BaseProduct_classification_three').change(function(){
		var current_id= $(this).attr('id');
		if(current_id == 'BaseProduct_classification_one'){
			var next = $('#BaseProduct_classification_two');
		}
		if(current_id == 'BaseProduct_classification_two'){
			var next = $('#BaseProduct_classification_three');
		}
		if(current_id == 'BaseProduct_classification_three'){
			return false;
		}
		$.getJSON('/baseproduct/ajaxgoodscategory',{
			cat_id:$(this).val(),
			v:new Date().getTime()
		},function(data){
			next.empty();
			next.append("<option value='empty'>-请选择-</option>");
			for(var k in data){
				next.append("<option value='" + k + "'>"+ data[k] + "</option>");
			}
		});
	});

	
	$('#comeback').live('click',function(){
		history.go(-1);
	});

	var product = new myProduct();
	$('#basesave').click(function(){
		$('#sub_type').val(1);
		//表示提交保存基础信息
		$('#is_submit').val(1);
		var res = product.validateBaseInfo();
		return res;
		product.saveBaseProduct();
	});

	if($('#ProductSaleInfo_upload_file').val() != ''){
		//显示上传的框
		$('.upload').show();
	}

	//对原价的监听-计算出结算单价
	$('#ProductSaleInfo_prime_cost').blur(function(){
		var clearing_form = $('#ProductSaleInfo_clearing_form').val();
		var fenzhang_form = $('#ProductSaleInfo_fenzhang_form').val();
		var prime_cost = $('#ProductSaleInfo_prime_cost').val();
		if(clearing_form == 1 && fenzhang_form && fenzhang_form != ''){
			$('#ProductSaleInfo_settlement_price').val(fenzhang_form * prime_cost);
		}
	});

	//改变分账方式
	$('#ProductSaleInfo_clearing_form').change(function(){
		var is_settle_accounts = $('#is_settle_accounts').val();
		var settle_accounts_type = $('#settle_accounts_type').val();
		var prod_sale_info_id =  $('#ProductSaleInfo_prod_sale_info_id').val();
		//当前选择的id
		var current_id = $(this).val();
		//合同需要结算
		if(is_settle_accounts == 1){
			//合同是分账方式，但是改成不是分账方式的话 需要上传附件
			if(settle_accounts_type == 1 && current_id == 0){
				alert('请上传变更结算方式的理由!');
				//显示上传的框
				$('.upload').show();
//				$('#ProductSaleInfo_settlement_price').removeAttr('readonly');
			}else{
//				$('#ProductSaleInfo_settlement_price').attr('readonly','readonly');
				$('.upload').hide();
			}
		}
		if(prod_sale_info_id　&& prod_sale_info_id != '' ){
			if(current_id != settle_accounts_type){
				alert('请上传变更结算方式的理由!');
				//显示上传的框
				$('.upload').show();
//				$('#ProductSaleInfo_settlement_price').removeAttr('readonly');
			}else{
				$('.upload').hide();
			}
		}
	});
	
	//提交审核按钮
	$('#salesubmit').click(function(){
		var re = validateProduct();
		if(re == true){
			product.submitSaleProduct();
		}
	});
});


function validateProduct(){
	var mypro = new myProduct();
	var res = mypro.validateSaleInfo();
	if(!res){
		return false;
	}
	var sub_type = $('#sub_type').val();
	if(sub_type == 2){
		return true;
	}else{
		alert('请先保存商品的基础信息');
		return false;
	}
}

function myProduct(){
	this.imgLoading = "<img src='/static/images/035.gif'/><span style='margin-top:-5px;'>数据加载中,请等候...</span>";
	this.ajaxTimeout = 60*1000;
}

myProduct.prototype.clearingFormValidate = function(){
	var is_settle_accounts = $('#is_settle_accounts').val();
	var settle_accounts_type = $('#settle_accounts_type').val();
	$('#ProductSaleInfo_clearing_form').change(function(){
		var current_id = $(this).val();
		//需要结算
		if(is_settle_accounts == 1){
			//合同是分账方式，但是改成不是分账方式的话 需要上传附件
			if(settle_accounts_type == 1 && current_id != 1){
				//显示上传的框
				$('.upload').show();
			}else{
				$('.upload').hide();
			}
		}
	});
}


myProduct.prototype.initCategory = function(parent_id,level){
	$.ajax({
		url:'/baseproduct/ajaxgoodscategory',
		data:{
			cat_id:parent_id,
			v:new Date().getTime()
		},
		async:false,
		dataType:"json",
		success:function(data){
			if(levle == 1){
				var cat = $('#BaseProduct_classification_two');
			}
			if(level == 2){
				var cat = $('#BaseProduct_classification_two');
			}
			if(level == 3){
				var cat = $('#BaseProduct_classification_three');
			}
			cat.empty();
			next.append("<option value=''>-请选择-</option>");
			for(var k in data){
				next.append("<option value='" + k + "'>"+ data[k] + "</option>");
			}
		}
		
	});
}

/**
 * 提交审核
 */
myProduct.prototype.submitSaleProduct = function(){
	var options = {
		type:'post',
		dataType:'json',
		beforeSubmit:function(){
			$('#basesave').val('正在保存。。。');
		},
		success:function(data){
			if(data.status == 'success'){
				//表示已经保存过基础信息了0：未保存，1提交保存，2已经保存成功
				$('#BaseProduct_base_products_id').val(data.base_products_id);
				alert('保存成功');
			}else{
				alert('保存失败!');
				$('#error_list').empty('');
				$('#error_list').attr('style','color:red');
				for (var i in data){
					if(data[i] != 'error'){
						$('#error_list').append('<li>'+data[i]+'</li>');
					}
				}
			}
		},
		error:function(error){
			alert('提交失败，请稍后再试');
		}
	};
	$('#product-form').ajaxForm(options);
	$('#product-form').ajaxSubmit(options);
}
/**
 * 保存商品的基础信息
 */
myProduct.prototype.saveBaseProduct = function(){
	var url = $('#product-form').attr('action');
	var customer_id = $('#BaseProduct_customer_id').val();
	var bus_oppo_id = $('#BaseProduct_bus_oppo_id').val();
	var base_contract_id = $('#BaseProduct_base_contract_id').val();
	var name = $('#BaseProduct_name').val();
	var base_products_id = $('#BaseProduct_base_products_id').val();
	var classification_one = $('#BaseProduct_classification_one').val();
	var classification_two = $('#BaseProduct_classification_two').val();
	var classification_three = $('#BaseProduct_classification_three').val();
	var product_type = $('#BaseProduct_product_type').val();
	var sale_type = $('#BaseProduct_sale_type').val();
	var sub_type = $('#sub_type').val();
	$.ajax({
		url:url,
		data:{
			customer_id:customer_id,
			bus_oppo_id:bus_oppo_id,
			base_contract_id:base_contract_id,
			name:name,
			base_products_id:base_products_id,
			classification_one:classification_one,
			classification_two:classification_two,
			classification_three:classification_three,
			product_type:product_type,
			sale_type:sale_type,
			sub_type:sub_type,
			type:'base_products',
			v:new Date().getTime()
		},
		type:'post',
		async:false,
		dataType:"json",
		success:function(data){
			if(data.status == 'success'){
				//表示已经保存过基础信息了0：未保存，1提交保存，2已经保存成功
				$('#BaseProduct_base_products_id').val(data.base_products_id);
				$('#sub_type').val(2);
				$('#basesave').hide();
				alert('保存成功');
			}else{
				alert('保存失败!');
				$('#error_list').empty('');
				$('#error_list').attr('style','color:red');
				for (var i in data){
					if(data[i] != 'error'){
						$('#error_list').append('<li>'+data[i]+'</li>');
					}
				}
			}
		},
		error:function(data){
			alert('保存失败，请稍后再试');
		}
	});
}

/**
 * 验证基本信息的必填项
 */
myProduct.prototype.validateBaseInfo = function(){
	var baseproduct_name = $('#BaseProduct_name').val();
	var classification_two = $('#BaseProduct_classification_two').val();
	if(baseproduct_name == ''){
		alert('请填写商品名称');
		return false;
	}
	if(classification_two == null || classification_two == ''){
		alert('请选择二级分类');
		return false;
	}
	return true;
}

/**
 * 验证销售信息
 */
myProduct.prototype.validateSaleInfo = function(){
	var prime_cost = $('#ProductSaleInfo_prime_cost').val();
	var settlement_price = $('#ProductSaleInfo_settlement_price').val();
	var sale_start_time = $('#ProductSaleInfo_sale_start_time').val();
	var sale_end_time = $('#ProductSaleInfo_sale_end_time').val();
	if(prime_cost == ''){
		alert('请填写原价');
		return false;
	}
	if(settlement_price == ''){
		alert('请填写结算单价');
		return false;
	}
	if(sale_start_time == ''){
		alert('请填写开始时间');
		return false;
	}
	if(sale_end_time == ''){
		alert('请填写结束时间');
		return false;
	}
	return true;
}
