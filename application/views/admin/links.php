<section class="content-header">
  <h1>
    链接列表
    <small>Manager Group List</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo site_url(admin_url().'home');?>"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">链接列表</li>
  </ol>
</section>

<!-- Main content -->
<section class="content container-fluid">
	<div class="box">
            <div class="box-header row">
            
              <div class="pull-left col-sm-8">
                <a href="javascript:;" onclick="openModel('<?php echo site_url(admin_url().'links/mod/add');?>', '编辑链接信息');" class="btn btn-sm btn-info btn-flat"><i class="fa fa-plus-circle" title="添加链接"></i> 添加链接</a>
                
              </div>
              <div class="pull-right col-sm-4">
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
              <table class="table table-striped table-bordered">
                 <tbody>
                    <tr>
                      <th><input type="checkbox" onclick="setC()" id="deletec" name="deletec" ></th>
                      <th>编号</th>
                      <th>图片</th>
                      <th>标题</th>
                      <th>分类</th>
                      <th>链接地址</th>
                      <th>开始时间</th>
                      <th>结束时间</th>
                      <th>是否审核</th>
                      <th>操作</th>
                    </tr>
                    <?php
						foreach($links_list as $list):
					?>
                    <tr>
                      <td><input type="checkbox"  name="id[]" value="<?php echo $list['links_id']; ?>" ></td>
                      <td><?php echo $list['links_id'];?></td>
                      <td><?php if(!empty($list['links_picture'])){ ?>
                        <img src="<?php echo base_url($list['links_picture']);?>" height="50" />
                        <?php } ?></td>
                      <td><?php echo $list['links_title'];?></td>
                      <td><?php echo $list['cat_name'];?></td>
                      <td><?php echo $list['links_url'];?></td>
                      <td><?php echo $list['links_starttime'] > 0 ? date("Y-m-d",$list['links_starttime']) : '-';?></td>
                      <td><?php echo $list['links_endtime'] > 0 ? date("Y-m-d",$list['links_endtime']) : '-';?></td>
                      <td><?php echo $list['links_state'] == 1 ? '<font color=green>已审核</font>' : '<font color=red>未审核</font>'?></td>
                      <td><a href="javascript:;" onclick="openModel('<?php echo admin_url();?>links/mod/<?php echo $list['links_id'];?>', '编辑链接类别信息');"><i class="fa fa-edit"></i></a>　<a href="javascript:;" onClick="delmsg('<?php echo admin_url();?>links/del/<?php echo $list['links_id'];?>', this, <?php echo $list['links_id'];?>);"><i class="fa fa-trash"></i></a></td>
                    </tr>
                    <?php endforeach ?>
                  </tbody>
              </table>
            </div>
            <div class="box-footer clearfix">              
              <?php echo $page_list;?>
            </div>
            <!-- /.box-body -->
          </div>
</section>
