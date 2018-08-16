<section class="content-header">
  <h1>
    基本信息管理
    <small>Basic Info Manager</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo site_url(admin_url().'home');?>"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">基本信息管理</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">基本信息</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            
            	
            <?php echo validation_errors(); ?>	
  			<?php echo form_open(admin_url().'setting/update',' class="form-horizontal"'); ?>
              <div class="box-body">
              
               
              
              	<?php foreach($configs as $cfg): ?>
				<?php
                    if($cfg['cfg_attr'] == "text"):
                 ?>
                <div class="form-group">
                  <label for="<?php echo $cfg['cfg_en_title'];?>1" class="col-sm-2 control-label"><?php echo $cfg['cfg_cn_title'];?></label>

                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="<?php echo $cfg['cfg_en_title'];?>1" placeholder="" name="<?php echo $cfg['cfg_en_title'];?>" value="<?php echo $cfg['cfg_value'];?>">
                    <span class="help-block"><?php echo $cfg['cfg_attr_tips'];?></span>
                  </div>
                </div>
                <?php endif ?>
                <?php
                    if($cfg['cfg_attr'] == "textarea"):
                 ?>
                 <div class="form-group">
                  <label for="<?php echo $cfg['cfg_en_title'];?>1" class="col-sm-2 control-label"><?php echo $cfg['cfg_cn_title'];?></label>

                  <div class="col-sm-10">
                    <textarea class="form-control" name="<?php echo $cfg['cfg_en_title'];?>" rows="3" placeholder=""><?php echo $cfg['cfg_value'];?></textarea>
                    <span class="help-block"><?php echo $cfg['cfg_attr_tips'];?></span>
                  </div>
                </div>
        
                  <?php endif ?>
                
                  <?php endforeach;?>
              </div>
              <!-- /.box-body -->

              <div class="box-footer text-center">
                <button type="submit" class="btn btn-primary">确定提交</button>　<button type="button" class="btn btn-default" onClick="window.history.go(-1);">返回上一页</button>
              </div>
            </form>
          </div>
          <!-- /.box -->

        </div>
        <!--/.col (left) -->
      </div>
      <!-- /.row -->
    </section>
