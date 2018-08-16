<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>提示信息</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <base href="<?php echo base_url();?>" />
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo base_url('data/'.admin_url().'css/bootstrap.min.css');?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url('data/'.admin_url().'css/font-awesome.min.css');?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url('data/'.admin_url().'css/ionicons.min.css');?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('data/'.admin_url().'css/AdminLTE.min.css');?>">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url('data/'.admin_url().'css/skins/_all-skins.min.css');?>">
  

  <!--[if lt IE 9]>
  <script src="<?php echo base_url('data/'.admin_url().'js/html5shiv.min.js');?>"></script>
  <script src="<?php echo base_url('data/'.admin_url().'js/respond.min.js');?>"></script>
  <![endif]-->
  
  <style>
    .example-modal .modal {
      position: relative;
      top: auto;
      bottom: auto;
      right: auto;
      left: auto;
      display: block;
      z-index: 1;
    }

    .example-modal .modal {
      background: transparent !important;
    }
  </style>
  
</head>
<body class="hold-transition skin-blue sidebar-mini">
<meta http-equiv="refresh" content="<?php echo $refreshTime?>;url=<?php echo site_url($url);?>"/> 
<div class="example-modal">
        <div class="modal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">提示信息</h4>
              </div>
              <div class="modal-body">
                <p><?php echo $Tips;?></p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal" onClick="location.href='<?php echo site_url($url);?>'"><?php echo $refreshTime?>秒后系统将自动跳转</button>
                <button type="button" class="btn btn-primary" onClick="location.href='<?php echo site_url($url);?>'">确定</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
      </div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url('data/'.admin_url().'js/jquery-min.js');?>"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo base_url('data/'.admin_url().'js/bootstrap.min.js');?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('data/'.admin_url().'js/adminlte.min.js');?>"></script>

</body>
</html>
