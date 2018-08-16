<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo base_url('data/'.admin_url().'/images/mlogo.png');?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $_SESSION['user_name'];?></p>
          <!-- Status -->
          <a href="javascript:;"><i class="fa fa-circle text-success"></i> 在线</a>
        </div>
      </div>

      <!-- search form (Optional) -->
      <form action="javascript:;" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
              <button type="button" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
        </div>
      </form>
      <!-- /.search form -->

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
      
      	<li class="treeview">
          <a href="javascirpt:;" class="tabMenu"><i class="fa fa-paper-plane-o"></i> <span>内容</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="javascript:;" onClick="go('<?php echo site_url(admin_url().'content/index');?>');"><i class="fa fa-genderless"></i>内容管理</a></li>
            <li><a href="javascript:;" onClick="go('<?php echo site_url(admin_url().'category/index');?>');"><i class="fa fa-genderless"></i>栏目管理</a></li>
          </ul>
        </li>
        
        <li class="treeview">
          <a href="javascirpt:;" class="tabMenu"><i class="fa fa-link"></i> <span>链接</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="javascript:;" onClick="go('<?php echo site_url(admin_url().'links/index');?>');"><i class="fa fa-genderless"></i>链接管理</a></li>
            <li><a href="javascript:;" onClick="go('<?php echo site_url(admin_url().'Linkscates/index');?>');"><i class="fa fa-genderless"></i>类别管理</a></li>
          </ul>
        </li>
        
        
        <li class="treeview">
          <a href="javascirpt:;" class="tabMenu"><i class="fa fa-cubes"></i> <span>广告</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="javascript:;" onClick="go('<?php echo site_url(admin_url().'ads/index');?>');"><i class="fa fa-genderless"></i>广告管理</a></li>
            <li><a href="javascript:;" onClick="go('<?php echo site_url(admin_url().'adscates/index');?>');"><i class="fa fa-genderless"></i>类别管理</a></li>
          </ul>
        </li>
        

        <li class="treeview">
          <a href="javascirpt:;" class="tabMenu"><i class="fa fa-user"></i> <span>管理员</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="javascript:;" onClick="go('<?php echo site_url(admin_url().'manager/index');?>');"><i class="fa fa-genderless"></i>管理员管理</a></li>
            <li><a href="javascript:;" onClick="go('<?php echo site_url(admin_url().'mtype/index');?>');"><i class="fa fa-genderless"></i>管理员类型</a></li>
          </ul>
        </li>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>