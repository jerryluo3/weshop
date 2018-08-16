<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>后台管理系统</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <base href="<?php echo base_url();?>" />
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="data/admin/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="data/admin/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="data/admin/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="data/admin/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="data/admin/plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    后台管理系统
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">请输入您的账号密码信息</p>

    <?php 
		echo validation_errors(); 
		echo form_open(admin_url().'login/login');
	?>
      <div class="form-group has-feedback">
        <input type="text" name="username" value="<?php echo isset($userlogininfo['user_name']) ? $userlogininfo['user_name'] : ''; ?>" class="form-control" placeholder="账号">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="password" value="<?php echo isset($userlogininfo['user_password']) ? $userlogininfo['user_password'] : ''; ?>" class="form-control" placeholder="密码">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox" name="remeber" id="remeber" <?php echo isset($userlogininfo['remeber']) && $userlogininfo['remeber'] == 1 ? 'checked="checked"' : ''; ?> value="1"> 记住账号
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">登录</button>
        </div>
        <!-- /.col -->
      </div>
    </form>


  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="data/admin/js/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="data/admin/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="data/admin/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
