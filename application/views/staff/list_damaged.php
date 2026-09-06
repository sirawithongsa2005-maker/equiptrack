<div class="content-wrapper"><section class="content"><div class="et-card">
  <div class="et-card-header"><div><h3>ครุภัณฑ์ชำรุด</h3><small class="et-card-subtitle">รายการครุภัณฑ์ที่มีสถานะชำรุดและรอตรวจสอบหรือซ่อมแซม</small></div></div>
  <div class="table-responsive"><table class="table et-table dataTable"><thead><tr><th>รูป</th><th>เลขครุภัณฑ์ / ชื่อ</th><th>ประเภท</th><th>รายละเอียด</th><th>หมายเหตุ</th><th>จัดการ</th></tr></thead><tbody>
  <?php if(empty($query)): ?><tr><td colspan="6" class="et-empty">ไม่มีครุภัณฑ์ชำรุด</td></tr><?php else: foreach($query as $row): ?>
  <tr><td><?php if(et_media_exists('devices',$row->d_img)): ?><img class="et-device-thumb" src="<?php echo html_escape(et_media_url('devices',$row->d_img,'')); ?>" alt="รูปครุภัณฑ์"><?php else: ?><span class="et-device-thumb et-device-thumb-placeholder"><i class="fa fa-cube"></i></span><?php endif; ?></td><td><strong><?php echo html_escape($row->d_name); ?></strong><small><?php echo html_escape($row->d_id); ?></small></td><td><?php echo html_escape($row->t_name); ?></td><td><?php echo html_escape($row->d_detail); ?></td><td><?php echo html_escape($row->d_remark ?: '-'); ?></td><td><a class="btn btn-warning btn-xs" href="<?php echo site_url('staff/edit_devices/'.$row->no); ?>"><i class="fa fa-pencil"></i> แก้ไขสถานะ</a></td></tr>
  <?php endforeach; endif; ?></tbody></table></div>
</div></section></div>
