<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        แก้ไขภาพประจำตัวสมาชิก
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
                            <form role="form" action="<?php echo  site_url('member/editdata_img'); ?>" method="post" class="form-horizontal" enctype="multipart/form-data">
                                <?php echo et_csrf_field(); ?>
                                <div class="box-body">
                                    
                                   
                                    <div class="form-group">
                                        <div class="col-sm-2 control-label">
                                            คำนำหน้า
                                        </div>
                                        <div class="col-sm-2">
                                            <select name="m_fname" class="form-control" required disabled>
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
                                            <input type="text" name="m_name" class="form-control" required value="<?php echo html_escape($rsedit->m_name); ?>" disabled>
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
                                            <input type="text" name="m_lname" class="form-control" required value="<?php echo html_escape($rsedit->m_lname); ?>" disabled>
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
                                            รูปภาพ
                                        </div>
                                        <div class="col-sm-4">
                                            <br>
                                            ภาพเก่า <br><br>
                                            <img src="<?php echo base_url('uploads/'.$rsedit->m_img);?>" width="300px">
                                            <br><br>
                                            เลือกไฟล์ใหม่<br>
                                            <input type="file" name="m_img" class="form-control" accept="image/*" required>
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