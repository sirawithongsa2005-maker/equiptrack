<div class="content-wrapper"><section class="content">
  <div class="et-card">
    <div class="et-card-header">
      <div><h3>ประเภทผู้ใช้งาน</h3><small class="et-card-subtitle">ประเภทหลักของระบบถูกล็อกเพื่อรักษาสิทธิ์ Admin / Staff / ผู้ยืม</small></div>
      <a class="btn btn-success" href="<?php echo site_url('position/adding'); ?>"><i class="fa fa-plus-circle"></i> เพิ่มประเภท</a>
    </div>
    <div class="table-responsive"><table class="table et-table dataTable">
      <thead><tr><th>#</th><th>ชื่อประเภทผู้ใช้งาน</th><th>ประเภท</th><th>จัดการ</th></tr></thead>
      <tbody>
      <?php if(empty($query)): ?><tr><td colspan="4" class="et-empty">ยังไม่มีประเภทผู้ใช้งาน</td></tr>
      <?php else: foreach($query as $rs): $locked=in_array((int)$rs->pid,array(1,3,4),TRUE); ?>
        <tr>
          <td><?php echo (int)$rs->pid; ?></td>
          <td><strong><?php echo html_escape($rs->pname); ?></strong></td>
          <td><?php echo $locked?'<span class="et-soft-label">ประเภทหลัก</span>':'<span class="et-soft-label">ผู้ยืมกำหนดเอง</span>'; ?></td>
          <td><div class="et-action-group">
            <?php if(!$locked): ?>
              <a href="<?php echo site_url('position/edit/'.$rs->pid); ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i> แก้ไข</a>
              <form method="post" action="<?php echo site_url('position/del/'.$rs->pid); ?>" class="et-inline-action" onsubmit="return confirm('ยืนยันการลบประเภทนี้?');"><?php echo et_csrf_field(); ?><button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> ลบ</button></form>
            <?php else: ?><span class="text-muted">ล็อก</span><?php endif; ?>
          </div></td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table></div>
  </div>
</section></div>
