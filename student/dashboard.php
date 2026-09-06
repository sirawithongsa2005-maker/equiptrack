<?php
require dirname(__DIR__).'/includes/bootstrap.php';
require_borrower(); $u=current_user();
$stats=[
 'available'=>(int)scalar("SELECT COUNT(*) FROM tbl_devices d WHERE d.ref_s_id=1 AND NOT EXISTS(SELECT 1 FROM tbl_devices_service s WHERE s.ref_d_id=d.d_id AND s.ser_status='pending')"),
 'pending'=>(int)scalar("SELECT COUNT(*) FROM tbl_devices_service WHERE ref_m_id=? AND ser_status='pending'",[$u['m_id']]),
 'active'=>(int)scalar("SELECT COUNT(*) FROM tbl_devices_service WHERE ref_m_id=? AND ser_status IN ('approved','borrowed')",[$u['m_id']]),
 'returned'=>(int)scalar("SELECT COUNT(*) FROM tbl_devices_service WHERE ref_m_id=? AND ser_status='returned'",[$u['m_id']])
];
$recent=fetch_all("SELECT s.*,d.d_name,d.d_id,d.d_img FROM tbl_devices_service s JOIN tbl_devices d ON d.d_id=s.ref_d_id WHERE s.ref_m_id=? ORDER BY s.ser_id DESC LIMIT 6",[$u['m_id']]);
render_header('ภาพรวมของฉัน','dashboard');
?>
<div class="page-intro"><div><h2>สวัสดี <?=e($u['m_name'])?></h2><p>ตรวจสอบสถานะคำขอยืมและเลือกครุภัณฑ์ที่พร้อมใช้งานได้จากหน้านี้</p></div><div class="actions"><a class="btn btn-primary" href="<?=e(url('student/borrow.php'))?>">+ ยืมครุภัณฑ์</a></div></div>
<div class="grid grid-4"><div class="card stat-card"><span class="stat-icon green">✓</span><div class="stat-info"><small>พร้อมให้ยืม</small><strong><?=$stats['available']?></strong><span>ครุภัณฑ์</span></div></div><div class="card stat-card"><span class="stat-icon orange">◷</span><div class="stat-info"><small>รออนุมัติ</small><strong><?=$stats['pending']?></strong><span>คำขอของฉัน</span></div></div><div class="card stat-card"><span class="stat-icon">↗</span><div class="stat-info"><small>กำลังยืม</small><strong><?=$stats['active']?></strong><span>รายการ</span></div></div><div class="card stat-card"><span class="stat-icon green">↙</span><div class="stat-info"><small>คืนแล้ว</small><strong><?=$stats['returned']?></strong><span>ประวัติ</span></div></div></div>
<div class="card mt-2"><div class="card-header"><div><h3>รายการล่าสุดของฉัน</h3><p>ติดตามสถานะคำขอและการยืม</p></div><a class="btn btn-soft btn-sm" href="<?=e(url('student/requests.php'))?>">ดูทั้งหมด</a></div><div class="card-body"><?php if(!$recent):?><div class="empty-state"><div class="empty-icon">▤</div><h3>ยังไม่มีรายการ</h3><p>เริ่มต้นด้วยการเลือกครุภัณฑ์ที่ต้องการยืม</p></div><?php else:?><div class="activity-list"><?php foreach($recent as $r):[$lab,$color]=status_label($r['ser_status']);?><div class="activity-item"><span class="activity-icon">▣</span><div class="activity-main"><strong><?=e($r['d_name'])?> <span class="muted">#<?=e($r['d_id'])?></span></strong><span><?=e($r['ser_reason'])?></span></div><div class="activity-side"><span class="badge badge-<?=e($color)?>"><?=e($lab)?></span><small><?=e(thai_date($r['ser_request_date']?:$r['ser_datesave'],true))?></small></div></div><?php endforeach;?></div><?php endif;?></div></div>
<?php render_footer();?>
