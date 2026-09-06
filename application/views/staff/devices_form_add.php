<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        เพิ่มข้อมูลครุภัณฑ์
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <!-- Your Page Content Here -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <!-- <h3 class="box-title"> +ข่าวใหม่ </h3> -->
                            </div><!-- /.box-header -->
                            <!-- form start -->
                            <form role="form" action="<?php echo  site_url('staff/add_devices_db'); ?>" method="post" class="form-horizontal" enctype="multipart/form-data">
                                <?php echo et_csrf_field(); ?>
                                <div class="box-body">
                                    <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                         ประเภท
                                        </div>
                                        <div class="col-sm-3">
                                            <select name="ref_t_id" class="form-control" required>
                                                <option value="">เลือกข้อมูล</option>
                                                 <?php foreach ($query as $rs) { ?>
                                                <option value="<?php echo html_escape($rs->t_id); ?>"><?php echo html_escape($rs->t_name); ?></option>
                                            <?php }?>  
                                            </select>
                                            <span class="fr"><?php  echo form_error('ref_t_id'); ?></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                         สถานะ
                                        </div>
                                        <div class="col-sm-3">
                                            <select name="ref_s_id" class="form-control" required>
                                                <option value="">เลือกข้อมูล</option>
                                                 <?php foreach ($querystatus as $rsst) { ?>
                                                <option value="<?php echo html_escape($rsst->s_id); ?>"><?php echo html_escape($rsst->s_name); ?></option>
                                            <?php }?>  
                                            </select>
                                            <span class="fr"><?php  echo form_error('ref_s_id'); ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                            เลขทะเบียน
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" name="d_id" class="form-control" required placeholder="เลขทะเบียน" value="<?php echo html_escape((string)set_value('d_id')); ?>" minlength="2">
                                            <span class="fr"><?php  echo form_error('d_id'); ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                            ชื่อครุภัณฑ์
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" name="d_name" class="form-control" required placeholder="ชื่อครุภัณฑ์"  minlength="2" value="<?php echo html_escape((string)set_value('d_name')); ?>">
                                            <span class="fr"><?php  echo form_error('d_name'); ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                            รายละเอียด
                                        </div>
                                        <div class="col-sm-4">
                                            <textarea name="d_detail" required class="form-control"></textarea>
                                            <span class="fr"><?php  echo form_error('d_detail'); ?></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                            หมายเหตุ
                                        </div>
                                        <div class="col-sm-4">
                                            <textarea name="d_remark"  class="form-control"></textarea>
                                        
                                        </div>
                                    </div>

                        

                                      <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                            รูปภาพ
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="file" name="d_img" class="form-control" accept="image/jpeg,image/png,image/gif"> 
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                            
                                        </div>
                                        <div class="col-sm-3">
                                            <button class="btn btn-primary" type="submit">
                                            <i class="fa fa-fw fa-save"></i> บันทึกข้อมูล</button>
                                            <a class="btn btn-danger" href="<?php echo  site_url('staff/devices'); ?>" role="button"><i class="fa fa-fw fa-close"></i> ยกเลิก</a>
                                            
                                            
                                        </div>
                                    </div>
                                    
                                    </div><!-- /.box-body -->
                                </form>
                            </div>
                        </div> </div> </div>
                        </section><!-- /.content -->
                        </div><!-- /.content-wrapper -->