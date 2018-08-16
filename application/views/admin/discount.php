<section class="content-header">
  <h1>
    优惠券管理
    <small>Products List</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo site_url(admin_url().'home');?>"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">优惠券管理</li>
  </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
	<div class="box">
            <div class="box-header row">
            
              <div class="pull-left col-sm-6">
                <a href="javascript:;" onclick="openModel('<?php echo site_url(admin_url().'discount/mod/add');?>', '编辑优惠券信息');" class="btn btn-sm btn-info btn-flat"><i class="fa fa-plus-circle" title="添加优惠券"></i> 添加优惠券</a>
                
              </div>
              <div class="pull-right col-sm-6">
              		<div class="row">
                        <div class="col-sm-6">
                            <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-search"></i></span>
                              <input type="text" name="keys" id="keys" placeholder="请输入搜索关键字" value="<?php echo isset($title) ? $title : '';?>" class="form-control">
                            </div>
                            
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                              <select class="form-control" id="cid" id="cid">
                                 <option value="0">类型</option>
                                 <?php
									echo $catesoption;
								?>
                                </select>
                            </div>
                            
                        </div>
                        <div class="col-xm-2">
                            <button type="button" class="btn btn-primary btn-flat" onClick="discountSeach();">查找</button>
                        </div>
                    </div>
              </div>
            </div>
            <div class="box-body no-padding">
              <table class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th><input type="checkbox" name="ids" id="ids" value="" class="my_checkbox" onclick="selectall('checkbox')" /></th>
                  <th>ID</th>
                  <th>图片</th>
                  <th>名称</th>
                  <th>类型</th>
                  <th>数量</th>
                  <th>电话</th>
                  <th>有效期</th>
                  <th>发布时间</th>
                  <th>状态</th>
                  <th>推荐</th>
                  <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php
                	foreach($discount_list as $list):
				?>
                <tr>
                  <td><input type="checkbox" name="checkbox" value="<?php echo $list['id'];?>" class="my_checkbox" /></td>
                  <td><?php echo $list['id'];?></td>
                  <td><img src="<?php echo !empty($list['picture']) ? base_url($list['picture']) : base_url('data/'.admin_url().'images/nopic.jpg');?>" width="30" height="30" /></td>
                  <td><?php echo $list['title'];?></td>
                  <td><?php echo $list['cat_name'];?></td>
                  <td><?php echo $list['nums'];?></td>
                  <td><?php echo $list['phone'];?></td>
                  <td><?php echo $list['endtime'] > 0 ? date('Y-m-d',$list['endtime']) : '长期';?></td>
                  <td><?php echo date("Y-m-d",$list['addtime']);?></td>
                  <td><?php echo $list['state'] == 1 ? '已审核' : '待审核';?></td>
                  <td><?php echo $list['iscommend'] == 1 ? '是' : '否';?></td>
                  <td><a href="javascript:;" onclick="openModel('<?php echo admin_url();?>discount/mod/<?php echo $list['id'];?>', '编辑优惠券信息');"><i class="fa fa-edit"></i></a>　<a href="javascript:;" onClick="delmsg('<?php echo admin_url();?>discount/del/<?php echo $list['id'];?>', this, <?php echo $list['id'];?>);"><i class="fa fa-trash"></i></a></td>
                </tr>
                <?php
                	endforeach;
				?>
              </tbody>
              </table>
            </div>
            <div class="box-footer clearfix">              
              <?php echo $page_list;?>
            </div>
            <!-- /.box-body -->
          </div>
</section>
<script>

function discountSeach(){
	var keys = $("#keys").val();
	var cid = $("#cid").val();
	if(keys == '' && cid == ''){
		 return false;	
	}
	var kstr = '';
		kstr = keys+'-'+cid;
	
	go('<?php echo admin_url()."discount/search/"?>'+kstr+'');
	
}

function resetNums(id,stock){
	var str = '<input type="number" class="form-control" style="width:70px;" id="input_'+id+'" onblur="updateNums('+stock+','+id+')" value="'+stock+'">';
	$('#nums_'+id).html(str);	
}

function updateNums(stock,id){
	var v = $("#input_"+id).val();
	if(Number(v) == Number(stock)) return false;
	if( Number(v) > 0){
		var params = 'nums='+v+'&id='+id;
		var url = "ajax_admin/discountNumsMod";
		
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
					$('#nums_'+id).html(v);
				}else{
					$('#nums_'+id).html(stock);	
				}
			}
		});
	}else{
		return false;	
	}
}

$(".myradio input").click(function(e){ 
	var state = e.delegateTarget.defaultValue; 
	//修改状态
	var pid = $(this).parents(".myradio").attr('attrid');
	var params = 'pid='+pid+'&state='+state;
	var url = "ajax_admin/updatediscountState";
	
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
