<section class="content-header">
  <h1>
    管理首页
    <small>Manager Home Page</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo site_url(admin_url().'home');?>"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">管理首页</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
      <!-- Info boxes -->
      <div class="row">
      	<!-- /.col -->
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">内容</span>
              <span class="info-box-number"><a href="<?php echo site_url(admin_url().'content/mod/add');?>">添加内容</a>　<a href="<?php echo site_url(admin_url().'content/index');?>">管理内容</a></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        
        
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="ion ion-ios-cart-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">留言</span>
              <span class="info-box-number"><a href="<?php echo site_url(admin_url().'feedback/index');?>">管理留言</a></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        
        
        <!-- /.col -->
        
        
        
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="glyphicon glyphicon-fire"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">互动</span>
              <span class="info-box-number"><a href="<?php echo site_url(admin_url().'message/index');?>">管理互动</a></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->


      </div>
      <!-- /.row -->

      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <div class="col-md-12">

          <!-- /.row -->

          <!-- TABLE: LATEST ORDERS -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">最新内容</h3>

              <div class="box-tools pull-right">
                <a href="<?php echo site_url(admin_url().'content/index');?>" class="btn btn-sm btn-default btn-flat">查看更多</a>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>标题</th>
                    <th>时间</th>
                    <th>状态</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  	foreach($content_list as $clist):
				  ?>
                  <tr>
                    <td><?php echo $clist['id'];?></a></td>
                    <td><?php echo $clist['title'];?></td>
                    <td><?php echo date('Y-m-d',$clist['addtime']);?></td>
                    <td><?php echo $clist['state'] == 1 ? '已审' : '待审';?></td>
                  </tr>
                  <?php
                  	endforeach;
				  ?>
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        
        <!-- /.col -->
      </div>
      <!-- /.row -->
    <div>
</div></section>
