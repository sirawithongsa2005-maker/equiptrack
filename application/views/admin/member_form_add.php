<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        เพิ่มข้อมูลผู้ใช้งาน
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
                            <form role="form" action="<?php echo  site_url('member/adddata'); ?>" method="post" class="form-horizontal" enctype="multipart/form-data">
                                <?php echo et_csrf_field(); ?>
                                <div class="box-body">
                                    <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                            ชื่อประเภทผู้ใช้
                                        </div>
                                        <div class="col-sm-2">
                                            <select name="ref_pid" class="form-control" required>
                                                <option value="">เลือกข้อมูล</option>
                                                 <?php foreach ($rspo as $rs) { ?>
                                                <option value="<?php echo html_escape($rs->pid); ?>"><?php echo html_escape($rs->pname); ?></option>
                                            <?php }?>
                                            </select>
                                            <span class="fr"><?php  echo form_error('ref_pid'); ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                           ชื่อผู้ใช้งาน
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" name="m_username" class="form-control" required placeholder="ชื่อผู้ใช้งาน" value="<?php echo html_escape((string)set_value('m_username')); ?>" minlength="4">
                                            <span class="fr"><?php  echo form_error('m_username'); ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                            รหัสผ่าน
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="password" name="m_password" class="form-control" required placeholder="รหัสผ่าน ขั้นต่ำ 4 ตัว"  minlength="4" autocomplete="new-password">
                                            <span class="fr"><?php  echo form_error('m_password'); ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                            คำนำหน้า
                                        </div>
                                        <div class="col-sm-2">
                                            <select name="m_fname" class="form-control" required>
                                                <option value="">เลือกข้อมูล</option>
                                                <option value="นาย">นาย</option>
                                                <option value="นางสาว">นางสาว</option>
                                                <option value="นาง">นาง</option>
                                            </select>
                                            <span class="fr"><?php  echo form_error('m_fname'); ?></span>
                                        </div>
                                    </div>

                                     <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                            ชื่อ
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" name="m_name" class="form-control" required placeholder="ชื่อ" value="<?php echo html_escape((string)set_value('m_name')); ?>" minlength="2">
                                            <span class="fr"><?php  echo form_error('m_name'); ?></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                            นามสกุล
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" name="m_lname" class="form-control" required placeholder="นามสกุล" value="<?php echo html_escape((string)set_value('m_lname')); ?>" minlength="2">
                                            <span class="fr"><?php  echo form_error('m_lname'); ?></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                           ระดับชั้นเเละรหัสนักศึกษา
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" name="m_email" class="form-control" required placeholder="ระดับชั้นเเละรหัสนักศึกษา" value="<?php echo html_escape((string)set_value('m_email')); ?>" minlength="5">
                                            <span class="fr"><?php  echo form_error('m_email'); ?></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                            เบอร์โทร
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" name="m_phone" class="form-control" required placeholder="เบอร์โทร 9–20 หลัก" minlength="9" maxlength="20" value="<?php echo html_escape((string)set_value('m_phone')); ?>">
                                            <span class="fr"><?php  echo form_error('m_phone'); ?></span>
                                        </div>
                                    </div>

                                      <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                          รูปภาพ
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="file" name="m_img" class="form-control" accept="image/*">
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                            
                                        </div>
                                        <div class="col-sm-3">
                                            <button class="btn btn-primary" type="submit">
                                            <i class="fa fa-fw fa-save"></i> บันทึกข้อมูล</button>
                                            <a class="btn btn-danger" href="<?php echo  site_url('member'); ?>" role="button"><i class="fa fa-fw fa-close"></i> ยกเลิก</a>
                                            
                                            
                                        </div>
                                    </div>
                                    
                                    </div><!-- /.box-body -->
                                </form>
                            </div>
                        </div> </div> </div>
                        </section><!-- /.content -->
                        </div><!-- /.content-wrapper -->