<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>后台管理系统</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <base href="<?php echo base_url();?>" />
  <link href="data/<?php echo admin_url();?>css/weui.css" rel="stylesheet" type="text/css" />
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="data/<?php echo admin_url();?>css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="data/<?php echo admin_url();?>css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="data/<?php echo admin_url();?>css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="data/<?php echo admin_url();?>css/dataTables.bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="data/<?php echo admin_url();?>css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="data/<?php echo admin_url();?>plugins/iCheck/all.css">
  
  <link rel="stylesheet" href="data/<?php echo admin_url();?>css/skins/_all-skins.min.css">
  
  <link rel="stylesheet" href="data/<?php echo admin_url();?>css/bootstrap-datepicker.min.css">
  
  <link rel="stylesheet" href="data/<?php echo admin_url();?>css/bootstrap-datetimepicker.min.css">
  
  <link rel="stylesheet" href="data/<?php echo admin_url();?>css/common.css?v=20171108">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <?php
  	$this->load->view(admin_url().'header');
  ?>
  <!-- Left side column. contains the logo and sidebar -->
  <?php
  	$this->load->view(admin_url().'left');
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="main">
    
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <?php
  	$this->load->view(admin_url().'footer');
  ?>

</div>
<button type="button" class="btn btn-default" id="modal_btn" data-toggle="modal" data-target="#modal-default" style="display:none">Launch Default Modal</button>
<div class="modal fade" id="modal-default">
  <div class="modal-dialog" style="width:800px;">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modal_title"></h4>
      </div>
      <div class="modal-body" id="modal_body">
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- jQuery 3 -->
<script src="<?php echo base_url('data/'.admin_url().'js/jquery.min.js');?>"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url('data/'.admin_url().'js/bootstrap.min.js');?>"></script>

<script src="<?php echo base_url('data/'.admin_url().'js/daterangepicker.js');?>"></script>

<script src="<?php echo base_url('data/'.admin_url().'js/bootstrap-datepicker.js');?>"></script>

<script src="<?php echo base_url('data/'.admin_url().'js/bootstrap-datetimepicker.js');?>"></script>

<script src="<?php echo base_url('data/'.admin_url().'js/jquery.dataTables.min.js');?>"></script>

<script src="<?php echo base_url('data/'.admin_url().'js/dataTables.bootstrap.min.js');?>"></script>

<!-- iCheck -->
<script src="<?php echo base_url('data/'.admin_url().'plugins/iCheck/icheck.min.js');?>"></script>

<script src="<?php echo base_url('data/'.admin_url().'js/chart.js');?>"></script>

<script src="<?php echo base_url('data/'.admin_url().'js/adminlte.min.js');?>"></script>


<script src="<?php echo base_url('data/'.admin_url().'js/common.js?v=20171110');?>"></script>
<script>
	go('<?php echo site_url(admin_url().'setting/index');?>');
</script>
</body>
</html>
