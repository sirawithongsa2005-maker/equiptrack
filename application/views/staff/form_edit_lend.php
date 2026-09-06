<div class="content-wrapper"><section class="content">
  <div class="et-page-head"><div><span class="et-eyebrow">BORROW</span><h1>แก้ไขวัตถุประสงค์การยืม</h1><p>แก้ไขเฉพาะรายละเอียดของรายการยืม โดยไม่เปลี่ยนครุภัณฑ์หรือผู้ยืม</p></div><a class="btn btn-default" href="<?php echo site_url('staff/loans'); ?>"><i class="fa fa-history"></i> กลับรายการกำลังยืม</a></div>
  <form action="<?php echo site_url('staff/edit_lend_db'); ?>" method="post"><?php echo et_csrf_field(); ?><input type="hidden" name="ser_id" value="<?php echo (int)$rsedit->ser_id; ?>">
    <div class="et-card et-form-card">
      <div class="et-return-summary"><div class="et-return-device">
        <?php if(et_media_exists('devices',$rsedit->d_img)): ?><img src="<?php echo et_media_url('devices',$rsedit->d_img,''); ?>" alt="รูปครุภัณฑ์"><?php else: ?><span class="et-device-placeholder"><i class="fa fa-laptop"></i></span><?php endif; ?>
        <div><span class="et-soft-label"><?php echo html_escape($rsedit->t_name); ?></span><h3><?php echo html_escape($rsedit->d_name); ?></h3><p><?php echo html_escape($rsedit->d_id); ?></p></div>
      </div><div class="et-summary-grid"><div><span>ผู้ยืม</span><strong><?php echo html_escape(trim($rsedit->m_fname.$rsedit->m_name.' '.$rsedit->m_lname)); ?></strong></div><div><span>วันที่ยืม</span><strong><?php echo html_escape($rsedit->ser_date_lend ?: $rsedit->ser_datesave); ?></strong></div><div><span>รายละเอียด</span><strong><?php echo html_escape($rsedit->d_detail ?: '-'); ?></strong></div><div><span>หมายเหตุ</span><strong><?php echo html_escape($rsedit->d_remark ?: '-'); ?></strong></div></div></div>
      <div class="et-form-section"><label for="ser_reason">วัตถุประสงค์การยืม</label><textarea id="ser_reason" name="ser_reason" class="form-control" rows="4" required><?php echo html_escape($rsedit->ser_reason); ?></textarea><?php echo form_error('ser_reason','<span class="help-block text-danger">','</span>'); ?></div>
      <div class="et-form-actions"><a class="btn btn-default" href="<?php echo site_url('staff/loans'); ?>">ยกเลิก</a><button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> บันทึก</button></div>
    </div>
  </form>
</section></div>
