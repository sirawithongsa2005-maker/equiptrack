<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        แก้ไขข้อมูลสมาชิก
        </h1>
        <!-- -->
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
                            <form role="form" action="<?php echo site_url('staff/editdata'); ?>" method="post" class="form-horizontal" enctype="multipart/form-data">
                                <?php echo et_csrf_field(); ?>
                                <div class="box-body">
                                    <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                            ชื่อตำแหน่ง
                                        </div>
                                        <div class="col-sm-3">
                                            <select name="ref_pid" class="form-control" readonly>
                                                <option value="<?php echo html_escape($rsedit->ref_pid); ?>">
                                                    <?php echo html_escape($rsedit->pname); ?>
                                                </option>
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
                                            <select name="m_fname" class="form-control" required>
                                                <option value="<?php echo html_escape($rsedit->m_fname); ?>"><?php echo html_escape($rsedit->m_fname); ?></option>
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
                                        <div class="col-sm-6">
                                            <input type="text" name="m_name" class="form-control" required value="<?php echo html_escape($rsedit->m_name); ?>">
                                            <?php if(form_error('m_name')==''){
                                            }else{ ?>
                                            <span class="fr">
                                            คุณได้พิมพ์ : 
                                            <?php echo html_escape((string)set_value('m_name')); ?>
                                            <br>
                                            ข้อผิดพลาด : 
                                            <?php  echo form_error('m_name'); ?>
                                        <?php } ?>
                                        </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                            นามสกุล
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" name="m_lname" class="form-control" required value="<?php echo html_escape($rsedit->m_lname); ?>">
                                            <input type="hidden" name="m_id" class="form-control" required value="<?php echo html_escape($rsedit->m_id); ?>">
                                            <?php if(form_error('m_lname')==''){
                                            }else{ ?>
                                            <span class="fr">
                                            คุณได้พิมพ์ : 
                                            <?php echo html_escape((string)set_value('m_lname')); ?>
                                            <br>
                                            ข้อผิดพลาด : 
                                            <?php  echo form_error('m_lname'); ?>
                                        </span>
                                    <?php } ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                            email
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" name="m_email" class="form-control" required value="<?php echo html_escape($rsedit->m_email); ?>">
                                             <?php if(form_error('m_email')==''){
                                            }else{ ?>
                                             <span class="fr">
                                            คุณได้พิมพ์ : 
                                            <?php echo html_escape((string)set_value('m_email')); ?>
                                            <br>
                                            ข้อผิดพลาด : 
                                            <?php  echo form_error('m_email'); ?>
                                        </span>
                                    <?php } ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                            phone
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" name="m_phone" class="form-control" required value="<?php echo html_escape($rsedit->m_phone); ?>">
                                             <?php if(form_error('m_phone')==''){
                                            }else{ ?>
                                            <span class="fr">
                                            คุณได้พิมพ์ : 
                                            <?php echo html_escape((string)set_value('m_phone')); ?>
                                            <br>
                                            ข้อผิดพลาด : 
                                            <?php  echo form_error('m_phone'); ?>
                                        </span>
                                    <?php } ?>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                            รูปภาพ
                                        </div>
                                        <div class="col-sm-4">
                                            <br>
                                            ภาพเก่า <br><br>
                                            <img src="<?php echo base_url('uploads/'.$rsedit->m_img);?>" width="300px">
                                            <br><br>
                                            เลือกไฟล์ใหม่<br>
                                            <input type="file" name="m_img" class="form-control" accept="image/*">
                                        </div>
                                    </div>
                                    
                                    
                                    
                                    <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                            
                                        </div>
                                        <div class="col-sm-3">
                                            <button class="btn btn-primary" type="submit">
                                            <i class="fa fa-fw fa-save"></i> บันทึกข้อมูล</button>
                                            <a class="btn btn-danger" href="<?php echo  site_url('staff'); ?>" role="button"><i class="fa fa-fw fa-close"></i> ยกเลิก</a>
                                            
                                            
                                        </div>
                                    </div>
                                    
                                    </div><!-- /.box-body -->
                                </form>
                            </div>
                        </div> </div> </div>
                        </section><!-- /.content -->
                        </div><!-- /.content-wrapper -->