<div class="content-wrapper et-content-wrapper">
<section class="content et-dashboard">
  <div class="et-dashboard-toolbar">
    <a href="<?php echo site_url('admin/devices'); ?>" class="btn btn-primary et-toolbar-action"><i class="fa fa-laptop"></i><span>ดูครุภัณฑ์</span></a>
  </div>

  <div class="et-stat-grid">
    <?php $cards=array(
      array('ครุภัณฑ์ทั้งหมด',$stats['devices'],'fa-cubes','lavender'),
      array('พร้อมใช้งาน',$stats['available'],'fa-check-circle','mint'),
      array('กำลังยืม',$stats['borrowed'],'fa-exchange','sky'),
      array('ชำรุด',$stats['damaged'],'fa-wrench','peach'),
      array('นักศึกษา',$stats['members'],'fa-users','rose'),
      array('รออนุมัติ',$stats['pending'],'fa-history','lavender')
    ); foreach($cards as $c): ?>
      <div class="et-stat-card <?php echo $c[3]; ?>">
        <div class="et-stat-icon"><i class="fa <?php echo $c[2]; ?>"></i></div>
        <div class="et-stat-copy"><span><?php echo html_escape($c[0]); ?></span><strong><?php echo number_format($c[1]); ?></strong></div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="et-dashboard-layout">
    <div class="et-card et-activity-card">
      <div class="et-card-header">
        <h3>รายการยืมล่าสุด</h3>
        <a href="<?php echo site_url('report'); ?>">ดูทั้งหมด</a>
      </div>
      <div class="table-responsive">
        <table class="table et-table">
          <thead><tr><th>เลขที่</th><th>ครุภัณฑ์</th><th>ผู้ยืม</th><th>วันที่ยืม</th><th>สถานะ</th></tr></thead>
          <tbody>
          <?php if(empty($recent)): ?>
            <tr><td colspan="5" class="et-empty">ยังไม่มีรายการยืม</td></tr>
          <?php else: foreach($recent as $r): ?>
            <tr>
              <td>#<?php echo str_pad((int)$r->ser_id,4,'0',STR_PAD_LEFT); ?></td>
              <td><strong><?php echo html_escape($r->d_name); ?></strong><small><?php echo html_escape($r->d_id); ?></small></td>
              <td><?php echo html_escape(trim($r->m_name.' '.$r->m_lname)); ?></td>
              <td><?php echo html_escape($r->ser_date_lend); ?></td>
              <td><?php if(empty($r->ser_date_return)): ?><span class="et-badge et-badge-sky">กำลังยืม</span><?php else: ?><span class="et-badge et-badge-mint">คืนแล้ว</span><?php endif; ?></td>
            </tr>
          <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="et-card et-actions-card">
      <div class="et-card-header"><h3>เมนูด่วน</h3></div>
      <div class="et-quick-grid">
        <a href="<?php echo site_url('admin/devices'); ?>"><span class="et-quick-icon"><i class="fa fa-laptop"></i></span><span>ครุภัณฑ์</span></a>
        <a href="<?php echo site_url('member'); ?>"><span class="et-quick-icon"><i class="fa fa-users"></i></span><span>ผู้ใช้งาน</span></a>
        <a href="<?php echo site_url('position'); ?>"><span class="et-quick-icon"><i class="fa fa-sitemap"></i></span><span>ประเภทผู้ใช้งาน</span></a>
        <a href="<?php echo site_url('report'); ?>"><span class="et-quick-icon"><i class="fa fa-bar-chart"></i></span><span>รายงาน</span></a>
      </div>
    </div>
  </div>
</section>
</div>
