<?php
require dirname(__DIR__).'/includes/bootstrap.php';
require_roles([3]);
$stats=[
 'devices'=>(int)scalar('SELECT COUNT(*) FROM tbl_devices'),
 'available'=>(int)scalar('SELECT COUNT(*) FROM tbl_devices WHERE ref_s_id=1'),
 'active'=>active_loan_count(), 'pending'=>pending_count(), 'damaged'=>(int)scalar('SELECT COUNT(*) FROM tbl_devices WHERE ref_s_id=3')
];
$pendingRows=fetch_all("SELECT s.ser_id,s.ser_request_date,m.m_name,m.m_lname,d.d_id,d.d_name FROM tbl_devices_service s JOIN tbl_member m ON m.m_id=s.ref_m_id JOIN tbl_devices d ON d.d_id=s.ref_d_id WHERE s.ser_status='pending' ORDER BY s.ser_request_date ASC LIMIT 6");
render_header('แดชบอร์ดเจ้าหน้าที่','dashboard');
?>
<div class="page-intro"><div><h2>งานยืม–คืนวันนี้</h2><p>ดูคำขอที่รออนุมัติ ครื่องที่กำลังถูกยืม และสถานะครุภัณฑ์แบบรวดเร็ว</p></div><div class="actions"><a class="btn btn-soft" href="<?=e(url('staff/devices.php'))?>">จัดการครุภัณฑ์</a><a class="btn btn-primary" href="<?=e(url('staff/direct_loan.php'))?>">+ บันทึกยืม</a></div></div>
<div class="grid grid-4">
  <div class="card stat-card"><span class="stat-icon orange">◷</span><div class="stat-info"><small>รออนุมัติ</small><strong><?=$stats['pending']?></strong><span>คำขอ</span></div></div>
  <div class="card stat-card"><span class="stat-icon">↗</span><div class="stat-info"><small>กำลังยืม</small><strong><?=$stats['active']?></strong><span>รายการ</span></div></div>
  <div class="card stat-card"><span class="stat-icon green">✓</span><div class="stat-info"><small>พร้อมใช้งาน</small><strong><?=$stats['available']?></strong><span>รายการ</span></div></div>
  <div class="card stat-card"><span class="stat-icon red">⚠</span><div class="stat-info"><small>ชำรุด</small><strong><?=$stats['damaged']?></strong><span>รายการ</span></div></div>
</div>
<div class="grid grid-3 mt-2"><div class="card span-2"><div class="card-header"><div><h3>คำขอที่รอดำเนินการ</h3><p>เรียงจากคำขอเก่าสุด</p></div><a class="btn btn-soft btn-sm" href="<?=e(url('staff/requests.php'))?>">ดูทั้งหมด</a></div><div class="card-body">
<?php if(!$pendingRows):?><div class="empty-state"><div class="empty-icon">✓</div><h3>ไม่มีคำขอค้าง</h3><p>ตอนนี้จัดการคำขอครบแล้ว</p></div><?php else:?><div class="activity-list"><?php foreach($pendingRows as $r):?><div class="activity-item"><span class="activity-icon">◷</span><div class="activity-main"><strong><?=e($r['d_name'])?> <span class="muted">#<?=e($r['d_id'])?></span></strong><span><?=e(trim($r['m_name'].' '.$r['m_lname']))?></span></div><div class="activity-side"><span class="badge badge-warning">รออนุมัติ</span><small><?=e(thai_date($r['ser_request_date'],true))?></small></div></div><?php endforeach;?></div><?php endif;?>
</div></div><div class="card"><div class="card-header"><div><h3>เมนูลัด</h3><p>จัดการงานประจำ</p></div></div><div class="card-body"><div class="quick-grid"><a class="quick-link" href="<?=e(url('staff/requests.php'))?>"><b>อนุมัติคำขอ</b><span><?=$stats['pending']?> รายการ</span></a><a class="quick-link" href="<?=e(url('staff/loans.php'))?>"><b>รับคืน</b><span><?=$stats['active']?> กำลังยืม</span></a><a class="quick-link" href="<?=e(url('staff/devices.php'))?>"><b>ครุภัณฑ์</b><span><?=$stats['devices']?> รายการ</span></a><a class="quick-link" href="<?=e(url('reports/index.php'))?>"><b>รายงาน</b><span>สรุปการใช้งาน</span></a></div></div></div></div>
<?php render_footer();?>
