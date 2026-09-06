<?php
require dirname(__DIR__).'/includes/bootstrap.php'; require_staff_or_admin();
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf(); if(post('action')==='delete'){
  $no=int_post('no');$d=fetch_one('SELECT * FROM tbl_devices WHERE no=?',[$no]);
  if(!$d)flash('danger','ไม่พบครุภัณฑ์');
  elseif((int)scalar('SELECT COUNT(*) FROM tbl_devices_service WHERE ref_d_id=?',[$d['d_id']])>0)flash('danger','ลบไม่ได้ เนื่องจากครุภัณฑ์นี้มีประวัติยืม–คืน');
  else{try{execute_sql('DELETE FROM tbl_devices WHERE no=?',[$no]);$f=basename((string)$d['d_img']);if($f&&is_file(ROOT_PATH.'/uploads/devices/'.$f))@unlink(ROOT_PATH.'/uploads/devices/'.$f);flash('success','ลบครุภัณฑ์เรียบร้อย');}catch(Throwable $e){flash('danger','ลบครุภัณฑ์ไม่สำเร็จ');}}
  redirect('staff/devices.php');
 }
}
$rows=fetch_all('SELECT d.*,t.t_name,s.s_name FROM tbl_devices d JOIN tbl_devices_type t ON t.t_id=d.ref_t_id JOIN tbl_devices_status s ON s.s_id=d.ref_s_id ORDER BY d.no DESC');
render_header('รายการครุภัณฑ์','devices');
?>
<div class="page-intro"><div><h2>ครุภัณฑ์ทั้งหมด</h2><p>รูปอุปกรณ์ทุกไฟล์จัดเก็บใน <b>uploads/devices/</b> และใช้ path เดียวกันทั้ง Mac และ Windows</p></div><a class="btn btn-primary" href="<?=e(url('staff/device_form.php'))?>">+ เพิ่มครุภัณฑ์</a></div>
<div class="card"><div class="table-tools"><div class="table-search"><input type="search" placeholder="ค้นหารหัส, ชื่อ, ประเภท…" data-table-search="#deviceTable"></div><span class="table-meta">ทั้งหมด <?=count($rows)?> รายการ</span></div><div class="table-wrap"><table class="data-table" id="deviceTable"><thead><tr><th>ครุภัณฑ์</th><th>ประเภท</th><th>สถานะ</th><th>รายละเอียด</th><th>หมายเหตุ</th><th>จัดการ</th></tr></thead><tbody>
<?php if(!$rows):?><tr data-empty><td colspan="6"><div class="empty-state"><div class="empty-icon">▣</div><h3>ยังไม่มีครุภัณฑ์</h3><p>กด “เพิ่มครุภัณฑ์” เพื่อเริ่มต้น</p></div></td></tr><?php else:foreach($rows as $r):$img=device_image($r);$cls=(int)$r['ref_s_id']===1?'available':((int)$r['ref_s_id']===2?'borrowed':'damaged');?>
<tr><td><div class="device-cell"><?php if($img):?><img class="device-thumb" src="<?=e($img)?>" alt="<?=e($r['d_name'])?>"><?php else:?><span class="device-placeholder">ET</span><?php endif;?><span><b class="cell-title"><?=e($r['d_name'])?></b><small class="cell-sub">รหัส <?=e($r['d_id'])?></small></span></div></td><td><?=e($r['t_name'])?></td><td><span class="status-dot <?=$cls?>"><?=e($r['s_name'])?></span></td><td><?=e($r['d_detail']?:'-')?></td><td><?=e($r['d_remark']?:'-')?></td><td><div class="btn-group"><a class="btn btn-soft btn-sm" href="<?=e(url('staff/device_form.php?no='.(int)$r['no']))?>">แก้ไข</a><form method="post" data-confirm="ลบ <?=e($r['d_name'])?> ออกจากระบบหรือไม่?"><?=csrf_field()?><input type="hidden" name="action" value="delete"><input type="hidden" name="no" value="<?=e($r['no'])?>"><button class="btn btn-danger-soft btn-sm" type="submit">ลบ</button></form></div></td></tr>
<?php endforeach;endif;?></tbody></table></div></div>
<?php render_footer();?>
