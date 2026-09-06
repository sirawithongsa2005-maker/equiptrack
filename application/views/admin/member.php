<div class="content-wrapper"><section class="content">
  <div class="et-card">
    <div class="et-card-header">
      <div><h3>จัดการผู้ใช้งาน</h3><small class="et-card-subtitle">เพิ่ม แก้ไข และกำหนดสิทธิ์ผู้ใช้งานในระบบ</small></div>
      <a class="btn btn-success" href="<?php echo site_url('member/adding'); ?>"><i class="fa fa-plus-circle"></i> เพิ่มผู้ใช้งาน</a>
    </div>
    <div class="table-responsive">
      <table class="table et-table dataTable">
        <thead><tr><th>#</th><th>ผู้ใช้งาน</th><th>ประเภท</th><th>ข้อมูลติดต่อ/นักศึกษา</th><th>จัดการ</th></tr></thead>
        <tbody>
        <?php if(empty($query)): ?><tr><td colspan="5" class="et-empty">ยังไม่มีข้อมูลผู้ใช้งาน</td></tr>
        <?php else: foreach($query as $rs): ?>
          <tr>
            <td><?php echo (int)$rs->m_id; ?></td>
            <td><div class="et-person-cell">
              <img src="<?php echo html_escape(et_media_url('uploads',$rs->m_img,'dist/img/avatar5.png')); ?>" alt="">
              <div><strong><?php echo html_escape(trim($rs->m_fname.$rs->m_name.' '.$rs->m_lname)); ?></strong><small>@<?php echo html_escape($rs->m_username); ?></small></div>
            </div></td>
            <td><span class="et-soft-label"><?php echo html_escape($rs->pname); ?></span></td>
            <td><strong><?php echo html_escape($rs->m_email ?: '-'); ?></strong><small><?php echo html_escape($rs->m_phone ?: '-'); ?></small></td>
            <td><div class="et-action-group">
              <a href="<?php echo site_url('member/edit/'.$rs->m_id); ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i> แก้ไข</a>
              <a href="<?php echo site_url('member/edit_img/'.$rs->m_id); ?>" class="btn btn-default btn-xs"><i class="fa fa-image"></i> รูป</a>
              <a href="<?php echo site_url('member/pwd/'.$rs->m_id); ?>" class="btn btn-info btn-xs"><i class="fa fa-key"></i> รหัสผ่าน</a>
              <form method="post" action="<?php echo site_url('member/del/'.$rs->m_id); ?>" class="et-inline-action" onsubmit="return confirm('ยืนยันการลบผู้ใช้งานนี้?');"><?php echo et_csrf_field(); ?><button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> ลบ</button></form>
            </div></td>
          </tr>
        <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section></div>
