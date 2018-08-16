<?php
	$this->load->view('pages/header');
?>
<link rel="stylesheet" href="data/asset/css/forum.css">


<div class="forum_block" style="margin-top:0;">
	<div class="forum_login">
        	<div class="forum_login_block">
                <div class="login_header"><img src="data/asset/images/login_block_header.png" /></div>
                <div class="login_main">
                	<div class="login_main_row">
                         <label for="title">账  号</label>
                         <input type="text" class="forum_txt" id="login_user" name="login_user" value="" placeholder="">
                    </div>
                    <div class="login_main_row">
                        <label for="title">密  码</label>
                         <input type="password" class="forum_txt" id="login_pass" name="login_pass" value="" placeholder="">
                    </div>
                </ul>
                </div>
                <div class="login_footer"><input type="button" class="forum_submit" onClick="userLogin();" name="submit" value="立即登录" /></div>
            </div>
    </div>
</div>

<?php
	$this->load->view('pages/footer');
?>