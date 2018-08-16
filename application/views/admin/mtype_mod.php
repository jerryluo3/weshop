<!-- Main content -->
<section class="content container-fluid">
	<form role="form">
          <div class="box-body">
            <div class="form-group">
              <label for="title">用户组名称</label>
              <input type="text" class="form-control" id="title" name="title" value="<?php echo isset($list['title']) ? $list['title'] : '';?>" placeholder="请输入用户组名称 ">
            </div>
            <div class="form-group">
              <label for="InputRank">排序ID</label>
              <input type="text" class="form-control" id="rank" name="rank" value="<?php echo isset($list['rank']) ? $list['rank'] : '';?>" placeholder="请输入排序ID">
            </div>
            <div class="form-group">
              <label for="power">权限</label>
            </div>
            <div class="form-group">
                  <div class="checkbox">
                  	<label><strong class="power_title">管理员：</strong></label>
                    <label><input type="checkbox">添加</label>　
                    <label><input type="checkbox">修改</label>　
                    <label><input type="checkbox">删除</label>
                  </div>
                  <div class="checkbox">
                  	<label><strong class="power_title">管理员类型：</strong></label>
                    <label><input type="checkbox">添加</label>　
                    <label><input type="checkbox">修改</label>　
                    <label><input type="checkbox">删除</label>
                  </div>
                  <div class="checkbox">
                  	<label><strong class="power_title">会员类型：</strong></label>
                    <label><input type="checkbox">重置密码</label>　
                    <label><input type="checkbox">删除</label>
                  </div>
                  <div class="checkbox">
                  	<label><strong class="power_title">公众号：</strong></label>
                    <label><input type="checkbox">信息配置</label>　
                    <label><input type="checkbox">自定义菜单</label>
                    <label><input type="checkbox">文章管理</label>
                  </div>
                </div>
          </div>

          <div class="box-footer">
            <button type="submit" class="btn btn-primary">确定提交</button>
          </div>
    </form>
</section>