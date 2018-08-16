<section class="content-header">
  <h1>
    类别管理
    <small>Products List</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo site_url(admin_url().'home');?>"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">类别管理</li>
  </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
	<div class="box">
            <div class="box-header row">
            
              <div class="pull-left col-sm-6">
                <a href="javascript:;" onclick="openModel('<?php echo site_url(admin_url().'productscategory/mod/add');?>', '编辑类别信息');" class="btn btn-sm btn-info btn-flat"><i class="fa fa-plus-circle" title="添加类别"></i> 添加类别</a>
                
              </div>
              <div class="pull-right col-sm-6">
              		<div class="row">
                        <div class="col-sm-10">
                            <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-search"></i></span>
                              <input type="text" name="keys" id="keys" placeholder="请输入搜索关键字" value="<?php echo isset($title) ? $title : '';?>" class="form-control">
                            </div>
                            
                        </div>
                        
                        <div class="col-xm-2">
                            <button type="button" class="btn btn-primary btn-flat" onClick="productSeach();">查找</button>
                        </div>
                    </div>
              </div>
            </div>
            <div class="box-body no-padding">
              <table class="table table-striped">
                <thead>
                <tr>
                  	<th>栏目名称</th>
                    <th>上级栏目</th>
                    <th>栏目图片</th>
                    <th>子栏目ID</th>
                    <th>排序ID</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php
                	echo $cates;
				?>
              </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
</section>

<script>

function  productSeach(){
	var keys = $("#keys").val();
	var storeid = $("#storeid").val();
	if(keys == '' && storeid == ''){
		 return false;	
	}
	var kstr = '';
		kstr = keys+'-'+storeid;
	
	go('<?php echo admin_url()."products/search/"?>'+kstr+'');
	
}

function resetStocks(id,stock){
	var str = '<input type="number" class="form-control" style="width:70px;" id="input_'+id+'" onblur="updateStocks('+stock+','+id+')" value="'+stock+'">';
	$('#stocks_'+id).html(str);	
}

function updateStocks(stock,pid){
	var v = $("#input_"+pid).val();
	if(Number(v) == Number(stock)) return false;
	if( Number(v) > 0){
		var params = 'stocks='+v+'&pid='+pid;
		var url = "ajax_admin/stocksMod";
		
		loadingshow('数据更新中...');
		$.ajax({
			type: "post",
			url: url,
			dataType: "json",
			data: params,
			success: function(msg){
				//
				loadinghide();
				if(msg.status == 200){
					loadingshow('更新成功');
					loadinghide();
					$('#stocks_'+pid).html(v);
				}else{
					loadingshow('更新失败');
					loadinghide();
					$('#stocks_'+pid).html(stock);	
				}
			}
		});
	}else{
		return false;	
	}
}

function exportQR(){
	var aa = document.getElementsByName("checkbox");
	var ss = "";
	for (var i = 0; i < aa.length; i++) {
		if (aa[i].checked) {
			ss = ss == '' ? aa[i].value : ss+'_'+aa[i].value;
		}
	}
	if(ss == ''){
		alert('请选择商品');
		return false;	
	}
	location.href = '<?php echo base_url(admin_url()."products/downloadQR/");?>'+'/'+ss;
}

function refreshQR(){
	
	var aa = document.getElementsByName("checkbox");
	var ss = "";
	
	for (var i = 0; i < aa.length; i++) {
		if (aa[i].checked) {
			ss = ss == '' ? aa[i].value : ss+'_'+aa[i].value;
		}
	}
	if(ss == ''){
		alert('请选择商品');
		return false;	
	}
	popBox('<?php echo base_url(admin_url());?>/products/refreshQR/'+ss,'刷新二维码',500,300);
}

$(".myradio input").click(function(e){ 
	var state = e.delegateTarget.defaultValue; 
	//修改状态
	var pid = $(this).parents(".myradio").attr('attrid');
	var params = 'pid='+pid+'&state='+state;
	var url = "ajax_admin/updateProductState";
	
	loadingshow('数据更新中...');
	$.ajax({
		type: "post",
		url: url,
		dataType: "json",
		data: params,
		success: function(msg){
			//
			loadinghide();
			if(msg.status == 200){
				loadingshow('更新成功');
				loadinghide();
			}else{
				loadingshow('更新失败');
				loadinghide();
				return false;
			}
		}
	});
	
	var myradio = $(".myradio"); 
	var iclose = $(this).parents(".myradio").find('.close'); 
	// console.log(iclose); 
	var iopen = $(this).parents(".myradio").find('.open'); 
	// console.log(state); 
	$(this).parents(".myradio").find(':radio').removeAttr('checked');
	$(this).parent('label').addClass('disabled'); 
	$(this).parent('label').siblings('label').find(':radio').attr('checked',true); 
	if (state == 1) { 
		$(this).parents(".myradio").removeClass('mropen').addClass('mrclose'); 
		sopen(); 
	} else { 
		$(this).parents(".myradio").removeClass('mrclose').addClass('mropen'); 
		sclose(); 
		 
	}
	
	 
	function sopen(){ 
		iopen.animate({left:"50px"},100); 
		setTimeout(function(){ 
			iopen.hide(); 
			iclose.show(); 
			iopen.css('left',0); 
			$(".myradio label").removeClass('disabled'); 
		 },300); 
	} 

	function sclose(){ 
		iclose.animate({left:"0px"},100); 
		setTimeout(function(){ 
			iopen.show(); 
			iclose.hide(); 			
			iclose.css('left','50px'); 
			$(".myradio label").removeClass('disabled'); 
		 },300); 
	} 
})


</script>
