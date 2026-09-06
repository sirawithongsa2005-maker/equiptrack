<div class="content-wrapper">
  <section class="content">
    <div class="et-page-head">
      <div>
        <span class="et-eyebrow">DIRECT BORROW</span>
        <h1>บันทึกการยืมโดยเจ้าหน้าที่</h1>
        <p>ค้นหานักศึกษาก่อน แล้วเลือกครุภัณฑ์ที่ต้องการยืมได้หลายรายการในครั้งเดียว</p>
      </div>
      <a class="btn btn-default" href="<?php echo site_url('staff/loans'); ?>"><i class="fa fa-fw fa-history"></i> รายการกำลังยืม</a>
    </div>

    <div class="et-card et-form-card">
      <div class="et-card-header"><h3>1. ค้นหานักศึกษา</h3></div>
      <div class="et-card-body">
        <form action="<?php echo site_url('staff/add_lend4'); ?>" method="post" class="et-inline-search">
            <?php echo et_csrf_field(); ?>
          <div class="form-group">
            <label for="student-search">รหัสผู้ใช้ หรือชื่อ</label>
            <div class="et-search-row">
              <input id="student-search" type="text" name="m_id" value="<?php echo html_escape((string)set_value('m_id')); ?>" required class="form-control" placeholder="เช่น 3 หรือ นักศึกษา">
              <button type="submit" name="s" value="q" class="btn btn-primary"><i class="fa fa-fw fa-eye"></i> ค้นหา</button>
            </div>
          </div>
        </form>

        <?php if (isset($query) && $query): ?>
          <?php if (in_array((int)$query->ref_pid,array(1,3),TRUE)): ?>
            <div class="alert alert-warning">บัญชีที่ค้นหาเป็นบัญชีผู้ดูแล/เจ้าหน้าที่ ไม่สามารถยืมในรายการนี้ได้ กรุณาค้นหาใหม่</div>
          <?php else: ?>
            <div class="et-selected-person">
              <img src="<?php echo html_escape(et_media_url('uploads',$query->m_img,'dist/img/avatar5.png')); ?>" alt="รูปนักศึกษา">
              <div><span>นักศึกษาที่เลือก</span><strong><?php echo html_escape(trim($query->m_fname.$query->m_name.' '.$query->m_lname)); ?></strong><small><?php echo html_escape($query->m_email); ?></small></div>
            </div>
          <?php endif; ?>
        <?php elseif (isset($query)): ?>
          <div class="alert alert-warning">ไม่พบข้อมูลนักศึกษาตามคำค้นหา</div>
        <?php endif; ?>
      </div>
    </div>

    <?php if (isset($query) && $query && !in_array((int)$query->ref_pid,array(1,3),TRUE)): ?>
      <form action="<?php echo site_url('staff/add_lend4_db'); ?>" method="post">
          <?php echo et_csrf_field(); ?>
        <input type="hidden" name="ref_m_id" value="<?php echo (int)$query->m_id; ?>">
        <div class="et-card">
          <div class="et-card-header"><h3>2. เลือกครุภัณฑ์</h3><span class="et-card-subtitle">แสดงเฉพาะรายการที่พร้อมใช้งาน</span></div>
          <div class="table-responsive">
            <table class="table et-table dataTable">
              <thead><tr><th class="et-check-cell">เลือก</th><th>รูป</th><th>ครุภัณฑ์</th><th>ประเภท</th><th>รายละเอียด</th></tr></thead>
              <tbody>
              <?php if (empty($ld)): ?>
                <tr><td colspan="5" class="et-empty">ไม่มีครุภัณฑ์ที่พร้อมให้ยืมในขณะนี้</td></tr>
              <?php else: foreach ($ld as $rs): ?>
                <tr>
                  <td class="et-check-cell"><input type="checkbox" name="device[]" value="<?php echo html_escape($rs->d_id); ?>"></td>
                  <td><?php if(et_media_exists('devices',$rs->d_img)): ?><img class="et-device-thumb" src="<?php echo html_escape(et_media_url('devices',$rs->d_img,'')); ?>" alt="รูปครุภัณฑ์"><?php else: ?><span class="et-soft-label">ไม่มีรูป</span><?php endif; ?></td>
                  <td><strong><?php echo html_escape($rs->d_name); ?></strong><small><?php echo html_escape($rs->d_id); ?></small></td>
                  <td><span class="et-soft-label"><?php echo html_escape($rs->t_name); ?></span></td>
                  <td><?php echo nl2br(html_escape($rs->d_detail)); ?><?php if($rs->d_remark): ?><small>หมายเหตุ: <?php echo html_escape($rs->d_remark); ?></small><?php endif; ?></td>
                </tr>
              <?php endforeach; endif; ?>
              </tbody>
            </table>
          </div>
          <div class="et-form-section">
            <div class="form-group">
              <label for="ser_reason">วัตถุประสงค์การยืม</label>
              <textarea id="ser_reason" name="ser_reason" class="form-control" rows="3" required placeholder="ระบุเหตุผลหรือวัตถุประสงค์การยืม"><?php echo html_escape((string)set_value('ser_reason')); ?></textarea>
              <?php echo form_error('device[]','<span class="help-block text-danger">','</span>'); ?>
              <?php echo form_error('ser_reason','<span class="help-block text-danger">','</span>'); ?>
            </div>
          </div>
          <div class="et-form-actions">
            <a class="btn btn-default" href="<?php echo site_url('staff'); ?>">ยกเลิก</a>
            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-save"></i> บันทึกการยืม</button>
          </div>
        </div>
      </form>
    <?php endif; ?>
  </section>
</div>
