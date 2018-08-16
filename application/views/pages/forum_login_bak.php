<?php
	$this->load->view('pages/header');
?>
<link rel="stylesheet" href="data/asset/css/forum.css">

<div class="forum_block">
	<div class="forum_add_list" style="padding:100px 300px;">
    	<ul>
        	<li>
            	 <label for="title">账号<b style="color:#f00;">*</b></label>
                 <input type="text" class="forum_txt" id="login_user" name="login_user" value="" placeholder="">
            </li>
            <li>
            	<label for="title">密码<b style="color:#f00;">*</b></label>
                 <input type="password" class="forum_txt" id="login_pass" name="login_pass" value="" placeholder="">
            </li>
            <li><input type="button" class="forum_submit" style="width:93%;" onClick="userLogin();" name="submit" value="立即登录" /></li>
        </ul>
    </div>
</div>

<?php
	$this->load->view('pages/footer');
?>