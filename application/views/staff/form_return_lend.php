<div class="content-wrapper">
  <section class="content">
    <div class="et-page-head">
      <div>
        <span class="et-eyebrow">RETURN</span>
        <h1>รับคืนครุภัณฑ์</h1>
        <p>ตรวจสอบรายการและเลือกสถานะของครุภัณฑ์หลังรับคืน</p>
      </div>
      <a class="btn btn-default" href="<?php echo site_url('staff/loans'); ?>"><i class="fa fa-fw fa-history"></i> กลับรายการกำลังยืม</a>
    </div>

    <form action="<?php echo site_url('staff/return_lend_db'); ?>" method="post">
        <?php echo et_csrf_field(); ?>
      <input type="hidden" name="ser_id" value="<?php echo (int)$rsedit->ser_id; ?>">
      <div class="et-card et-form-card">
        <div class="et-card-header"><h3>ข้อมูลการยืม</h3></div>
        <div class="et-return-summary">
          <div class="et-return-device">
            <?php if(et_media_exists('devices',$rsedit->d_img)): ?>
              <img src="<?php echo et_media_url('devices',$rsedit->d_img,''); ?>" alt="รูปครุภัณฑ์">
            <?php else: ?>
              <span class="et-device-placeholder"><i class="fa fa-laptop"></i></span>
            <?php endif; ?>
            <div>
              <span class="et-soft-label"><?php echo html_escape($rsedit->t_name); ?></span>
              <h3><?php echo html_escape($rsedit->d_name); ?></h3>
              <p><?php echo html_escape($rsedit->d_id); ?></p>
            </div>
          </div>
          <div class="et-summary-grid">
            <div><span>ผู้ยืม</span><strong><?php echo html_escape(trim($rsedit->m_fname.$rsedit->m_name.' '.$rsedit->m_lname)); ?></strong></div>
            <div><span>วันที่ยืม</span><strong><?php echo html_escape($rsedit->ser_date_lend ?: '-'); ?></strong></div>
            <div><span>วัตถุประสงค์</span><strong><?php echo html_escape($rsedit->ser_reason ?: '-'); ?></strong></div>
            <div><span>รายละเอียด</span><strong><?php echo html_escape($rsedit->d_detail ?: '-'); ?></strong></div>
          </div>
        </div>

        <div class="et-form-section">
          <label for="return-status">สถานะหลังรับคืน</label>
          <select id="return-status" name="ref_s_id" class="form-control" required>
            <?php foreach($querystatus as $st): ?>
              <option value="<?php echo (int)$st->s_id; ?>"><?php echo html_escape($st->s_name); ?></option>
            <?php endforeach; ?>
          </select>
          <span class="help-block">เลือก “พร้อมใช้งาน” หากปกติ หรือ “ชำรุด” หากต้องตรวจสอบ/ซ่อม</span>
          <?php echo form_error('ref_s_id','<span class="help-block text-danger">','</span>'); ?>
        </div>
        <div class="et-form-actions">
          <a class="btn btn-default" href="<?php echo site_url('staff/loans'); ?>">ยกเลิก</a>
          <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-save"></i> ยืนยันรับคืน</button>
        </div>
      </div>
    </form>
  </section>
</div>
