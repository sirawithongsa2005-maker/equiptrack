<div class="content-wrapper"><section class="content"><div class="et-card">
  <div class="et-card-header"><div><h3>ประเภทครุภัณฑ์</h3><small class="et-card-subtitle">กำหนดหมวดหมู่สำหรับจัดระเบียบครุภัณฑ์</small></div><a class="btn btn-success" href="<?php echo site_url('staff/add_type'); ?>"><i class="fa fa-plus-circle"></i> เพิ่มประเภท</a></div>
  <div class="table-responsive"><table class="table et-table dataTable"><thead><tr><th>#</th><th>ชื่อประเภท</th><th>จัดการ</th></tr></thead><tbody>
  <?php if(empty($query)): ?><tr><td colspan="3" class="et-empty">ยังไม่มีประเภทครุภัณฑ์</td></tr><?php else: foreach($query as $rs): ?>
  <tr><td><?php echo (int)$rs->t_id; ?></td><td><strong><?php echo html_escape($rs->t_name); ?></strong></td><td><div class="et-action-group"><a class="btn btn-warning btn-xs" href="<?php echo site_url('staff/edit_type/'.$rs->t_id); ?>"><i class="fa fa-pencil"></i> แก้ไข</a><form method="post" action="<?php echo site_url('staff/del_type/'.$rs->t_id); ?>" class="et-inline-action" onsubmit="return confirm('ยืนยันการลบประเภทนี้?');"><?php echo et_csrf_field(); ?><button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> ลบ</button></form></div></td></tr>
  <?php endforeach; endif; ?></tbody></table></div>
</div></section></div>
