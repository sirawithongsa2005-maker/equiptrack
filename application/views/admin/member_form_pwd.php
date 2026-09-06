<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        ฟอร์มแก้ไขรหัสผ่าน
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
                            <form role="form" action="<?php echo  site_url('member/editpwd'); ?>" method="post" class="form-horizontal">
                                <?php echo et_csrf_field(); ?>
                                <div class="box-body">
                                    <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                            ชื่อตำแหน่ง
                                        </div>
                                        <div class="col-sm-3">
                                            <select name="ref_pid" class="form-control" required disabled>
                                                <option value="<?php echo html_escape($rsedit->ref_pid); ?>">
                                                    -<?php echo html_escape($rsedit->pname); ?>-
                                                </option>
                                                <option value="">เลือกข้อมูล</option>
                                                <?php foreach ($rspo as $rs) { ?>
                                                <option value="<?php echo html_escape($rs->pid); ?>">-<?php echo html_escape($rs->pname); ?>-</option>
                                                <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                            Username
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" name="m_username" class="form-control" required value="<?php echo html_escape($rsedit->m_username); ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                            คำนำหน้า
                                        </div>
                                        <div class="col-sm-2">
                                            <select name="m_fname" class="form-control" required disabled>
                                                <option value="<?php echo html_escape($rsedit->m_fname); ?>">-<?php echo html_escape($rsedit->m_fname); ?>-</option>
                                                <option value="">เลือกข้อมูล</option>
                                                <option value="นาย">นาย</option>
                                                <option value="นางสาว">นางสาว</option>
                                                <option value="นาง">นาง</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                            ชื่อ
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" name="m_name" class="form-control" required value="<?php echo html_escape($rsedit->m_name); ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                            นามสกุล
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" name="m_lname" class="form-control" required value="<?php echo html_escape($rsedit->m_lname); ?>" disabled>
                                            <input type="hidden" name="m_id" class="form-control" required value="<?php echo html_escape($rsedit->m_id); ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                            รหัสผ่านใหม่
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="password" name="m_password" class="form-control" required minlength="4" placeholder="รหัสผ่านใหม่อย่างน้อย 4 ตัว" autocomplete="new-password">
                                            <span class="fr"><?php  echo form_error('m_password'); ?></span>
                                        </div>
                                    </div>


                                     <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                            ยืนยันรหัสผ่าน
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="password" name="m_password2" class="form-control" required minlength="4" placeholder="ยืนยันรหัสผ่านใหม่" autocomplete="new-password">
                                            <span class="fr"><?php  echo form_error('m_password2'); ?></span>
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