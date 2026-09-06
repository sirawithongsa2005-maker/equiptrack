<?php
require dirname(__DIR__).'/includes/bootstrap.php';
require_roles([1]);
$stats=[
 'devices'=>(int)scalar('SELECT COUNT(*) FROM tbl_devices'),
 'available'=>(int)scalar('SELECT COUNT(*) FROM tbl_devices WHERE ref_s_id=1'),
 'borrowed'=>(int)scalar('SELECT COUNT(*) FROM tbl_devices WHERE ref_s_id=2'),
 'damaged'=>(int)scalar('SELECT COUNT(*) FROM tbl_devices WHERE ref_s_id=3'),
 'members'=>(int)scalar('SELECT COUNT(*) FROM tbl_member WHERE ref_pid NOT IN (1,3)'),
 'pending'=>pending_count(),
];
$recent=fetch_all("SELECT s.ser_id,s.ser_status,s.ser_date_lend,s.ser_date_return,s.ser_request_date,m.m_name,m.m_lname,d.d_id,d.d_name FROM tbl_devices_service s JOIN tbl_member m ON m.m_id=s.ref_m_id JOIN tbl_devices d ON d.d_id=s.ref_d_id ORDER BY s.ser_id DESC LIMIT 8");
render_header('แดชบอร์ดผู้ดูแล','dashboard');
?>
<div class="page-intro"><div><h2>ภาพรวมระบบ</h2><p>ติดตามจำนวนครุภัณฑ์ ผู้ใช้งาน และกิจกรรมยืม–คืนล่าสุดจากจุดเดียว</p></div><div class="actions"><a class="btn btn-soft" href="<?=e(url('reports/index.php'))?>">ดูรายงาน</a><a class="btn btn-primary" href="<?=e(url('admin/user_form.php'))?>">+ เพิ่มผู้ใช้งาน</a></div></div>
<div class="grid grid-4">
  <div class="card stat-card"><span class="stat-icon">▣</span><div class="stat-info"><small>ครุภัณฑ์ทั้งหมด</small><strong><?=$stats['devices']?></strong><span>รายการในระบบ</span></div></div>
  <div class="card stat-card"><span class="stat-icon green">✓</span><div class="stat-info"><small>พร้อมใช้งาน</small><strong><?=$stats['available']?></strong><span>พร้อมให้ยืม</span></div></div>
  <div class="card stat-card"><span class="stat-icon orange">◷</span><div class="stat-info"><small>รออนุมัติ</small><strong><?=$stats['pending']?></strong><span>คำขอจากผู้ยืม</span></div></div>
  <div class="card stat-card"><span class="stat-icon red">⚠</span><div class="stat-info"><small>ชำรุด</small><strong><?=$stats['damaged']?></strong><span>รอตรวจสอบ/ซ่อม</span></div></div>
</div>
<div class="grid grid-3 mt-2">
  <div class="card span-2"><div class="card-header"><div><h3>กิจกรรมล่าสุด</h3><p>รายการยืม–คืนที่เกิดขึ้นล่าสุด</p></div></div><div class="card-body">
  <?php if(!$recent):?><div class="empty-state"><div class="empty-icon">▤</div><h3>ยังไม่มีประวัติ</h3><p>เมื่อเริ่มมีการยืม ระบบจะแสดงข้อมูลตรงนี้</p></div><?php else:?><div class="activity-list">
    <?php foreach($recent as $r): [$label,$color]=status_label($r['ser_status']);?>
      <div class="activity-item"><span class="activity-icon">▣</span><div class="activity-main"><strong><?=e($r['d_name'])?> <span class="muted">#<?=e($r['d_id'])?></span></strong><span><?=e(trim($r['m_name'].' '.$r['m_lname']))?></span></div><div class="activity-side"><span class="badge badge-<?=e($color)?>"><?=e($label)?></span><small><?=e(thai_date($r['ser_request_date'] ?: $r['ser_date_lend'],true))?></small></div></div>
    <?php endforeach;?></div><?php endif;?>
  </div></div>
  <div class="card"><div class="card-header"><div><h3>เมนูลัด</h3><p>ไปยังงานที่ใช้บ่อย</p></div></div><div class="card-body"><div class="quick-grid">
    <a class="quick-link" href="<?=e(url('admin/users.php'))?>"><b>ผู้ใช้งาน</b><span><?=$stats['members']?> ผู้ยืม</span></a>
    <a class="quick-link" href="<?=e(url('staff/devices.php'))?>"><b>ครุภัณฑ์</b><span><?=$stats['devices']?> รายการ</span></a>
    <a class="quick-link" href="<?=e(url('staff/requests.php'))?>"><b>คำขอยืม</b><span><?=$stats['pending']?> รออนุมัติ</span></a>
    <a class="quick-link" href="<?=e(url('reports/index.php'))?>"><b>รายงาน</b><span>ดูสถิติทั้งหมด</span></a>
  </div></div></div>
</div>
<?php render_footer(); ?>
