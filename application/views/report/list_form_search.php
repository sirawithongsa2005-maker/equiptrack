<div class="content-wrapper"><section class="content">
  <div class="et-card et-form-card">
    <div class="et-card-header"><div><h3>ค้นหาประวัติตามช่วงวันที่</h3><small class="et-card-subtitle">เลือกวันที่เริ่มต้นและวันที่สิ้นสุด</small></div></div>
    <div class="et-card-body"><form action="<?php echo site_url('report/searchbydate_db'); ?>" method="post" class="et-form"><?php echo et_csrf_field(); ?>
      <div class="row"><div class="col-sm-5"><div class="form-group"><label for="ds">วันที่เริ่มต้น</label><input id="ds" type="date" name="ds" class="form-control" required value="<?php echo html_escape((string)set_value('ds')); ?>"></div></div><div class="col-sm-5"><div class="form-group"><label for="de">วันที่สิ้นสุด</label><input id="de" type="date" name="de" class="form-control" required value="<?php echo html_escape((string)set_value('de')); ?>"></div></div><div class="col-sm-2"><div class="form-group"><label>&nbsp;</label><button class="btn btn-primary btn-block" type="submit"><i class="fa fa-search"></i> ค้นหา</button></div></div></div>
    </form></div>
  </div>
  <div class="et-card"><div class="et-card-header"><h3>ผลการค้นหา</h3></div><div class="table-responsive"><table class="table et-table dataTable"><thead><tr><th>วันที่</th><th>ผู้ยืม</th><th>ครุภัณฑ์</th><th>เหตุผล</th><th>เจ้าหน้าที่</th><th>วันที่คืน</th></tr></thead><tbody>
  <?php if(empty($query)): ?><tr><td colspan="6" class="et-empty">ไม่พบข้อมูลในช่วงวันที่ที่เลือก</td></tr><?php else: foreach($query as $row): ?>
  <tr><td><?php echo html_escape($row->ser_datesave); ?></td><td><strong><?php echo html_escape(trim($row->m_fname.$row->m_name.' '.$row->m_lname)); ?></strong><small><?php echo html_escape($row->m_email); ?></small></td><td><strong><?php echo html_escape($row->d_name); ?></strong><small><?php echo html_escape($row->d_id); ?></small></td><td><?php echo nl2br(html_escape($row->ser_reason)); ?></td><td><?php echo html_escape($row->ser_staff_name_lend ?: '-'); ?></td><td><?php echo html_escape($row->ser_date_return ?: '-'); ?></td></tr>
  <?php endforeach; endif; ?></tbody></table></div></div>
</section></div>
