<div class="content-wrapper"><section class="content">
  <div class="et-card">
    <div class="et-card-header">
      <div><h3>ทะเบียนครุภัณฑ์</h3><small class="et-card-subtitle">จัดการข้อมูล รูปภาพ ประเภท และสถานะของครุภัณฑ์</small></div>
      <a class="btn btn-success" href="<?php echo site_url('staff/add_devices'); ?>"><i class="fa fa-plus-circle"></i> เพิ่มครุภัณฑ์</a>
    </div>
    <div class="table-responsive"><table class="table et-table dataTable">
      <thead><tr><th>รูป</th><th>เลขครุภัณฑ์ / ชื่อ</th><th>ประเภท</th><th>รายละเอียด</th><th>สถานะ</th><th>จัดการ</th></tr></thead>
      <tbody>
      <?php if(empty($query)): ?><tr><td colspan="6" class="et-empty">ยังไม่มีข้อมูลครุภัณฑ์</td></tr>
      <?php else: foreach($query as $rs): ?>
        <tr>
          <td><?php if(et_media_exists('devices',$rs->d_img)): ?><img class="et-device-thumb" src="<?php echo html_escape(et_media_url('devices',$rs->d_img,'')); ?>" alt="รูปครุภัณฑ์"><?php else: ?><span class="et-device-thumb et-device-thumb-placeholder"><i class="fa fa-cube"></i></span><?php endif; ?></td>
          <td><strong><?php echo html_escape($rs->d_name); ?></strong><small><?php echo html_escape($rs->d_id); ?></small></td>
          <td><?php echo html_escape($rs->t_name); ?></td>
          <td><?php echo html_escape($rs->d_detail); ?><?php if($rs->d_remark): ?><small>หมายเหตุ: <?php echo html_escape($rs->d_remark); ?></small><?php endif; ?></td>
          <td><span class="et-soft-label"><?php echo html_escape($rs->s_name); ?></span></td>
          <td><div class="et-action-group"><a href="<?php echo site_url('staff/edit_devices/'.$rs->no); ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i> แก้ไข</a><form method="post" action="<?php echo site_url('staff/del_devices/'.$rs->no); ?>" class="et-inline-action" onsubmit="return confirm('ยืนยันการลบครุภัณฑ์นี้?');"><?php echo et_csrf_field(); ?><button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> ลบ</button></form></div></td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table></div>
  </div>
</section></div>
