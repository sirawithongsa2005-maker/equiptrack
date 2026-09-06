<?php
require dirname(__DIR__).'/includes/bootstrap.php'; require_roles([1]);
$errors=[];$editId=get_int('edit');
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();$action=post('action');$id=int_post('id');$name=post('pname');
 if($action==='save'){
   if($name==='')$errors[]='กรุณากรอกชื่อประเภทผู้ใช้งาน';
   elseif((int)scalar('SELECT COUNT(*) FROM tbl_position WHERE pname=? AND pid<>?',[$name,$id])>0)$errors[]='มีประเภทผู้ใช้งานชื่อนี้แล้ว';
   elseif($id && in_array($id,[1,3,4],true))$errors[]='ประเภทหลักของระบบไม่อนุญาตให้แก้ไข';
   else{try{if($id)execute_sql('UPDATE tbl_position SET pname=? WHERE pid=?',[$name,$id]);else execute_sql('INSERT INTO tbl_position(pname) VALUES(?)',[$name]);flash('success',$id?'แก้ไขประเภทผู้ใช้งานเรียบร้อย':'เพิ่มประเภทผู้ใช้งานเรียบร้อย');redirect('admin/positions.php');}catch(Throwable $e){$errors[]='บันทึกข้อมูลไม่สำเร็จ';}}
 }elseif($action==='delete'){
   if(in_array($id,[1,3,4],true))flash('danger','ประเภทหลักของระบบไม่สามารถลบได้');
   elseif((int)scalar('SELECT COUNT(*) FROM tbl_member WHERE ref_pid=?',[$id])>0)flash('danger','ลบไม่ได้ เนื่องจากยังมีผู้ใช้งานอยู่ในประเภทนี้');
   else{execute_sql('DELETE FROM tbl_position WHERE pid=?',[$id]);flash('success','ลบประเภทผู้ใช้งานเรียบร้อย');}redirect('admin/positions.php');
 }
}
$rows=fetch_all('SELECT p.*,COUNT(m.m_id) total_users FROM tbl_position p LEFT JOIN tbl_member m ON m.ref_pid=p.pid GROUP BY p.pid,p.pname ORDER BY p.pid');
$edit=$editId?fetch_one('SELECT * FROM tbl_position WHERE pid=?',[$editId]):null;
render_header('ประเภทผู้ใช้งาน','positions');
?>
<div class="page-intro"><div><h2>ประเภทและสิทธิ์ผู้ใช้งาน</h2><p>ประเภท ID 1, 3 และ 4 เป็นสิทธิ์หลักของระบบ ส่วนประเภทที่เพิ่มใหม่จะใช้งานในฐานะผู้ยืม</p></div></div>
<?php if($errors):?><div class="inline-alert danger"><b>!</b><span><?=implode('<br>',array_map('e',$errors))?></span></div><?php endif;?>
<div class="grid grid-3"><div class="card span-2"><div class="card-header"><div><h3>รายการประเภท</h3><p>จำนวนสมาชิกในแต่ละกลุ่ม</p></div></div><div class="table-wrap"><table class="data-table"><thead><tr><th>ID</th><th>ชื่อประเภท</th><th>ผู้ใช้งาน</th><th>สถานะ</th><th>จัดการ</th></tr></thead><tbody><?php foreach($rows as $r):$locked=in_array((int)$r['pid'],[1,3,4],true);?><tr><td>#<?=e($r['pid'])?></td><td><b><?=e($r['pname'])?></b></td><td><?=e($r['total_users'])?> บัญชี</td><td><span class="badge <?=$locked?'badge-primary':'badge-muted'?>"><?=$locked?'ประเภทหลัก':'กำหนดเอง'?></span></td><td><div class="btn-group"><?php if(!$locked):?><a class="btn btn-soft btn-sm" href="?edit=<?=e($r['pid'])?>">แก้ไข</a><form method="post" data-confirm="ลบประเภทผู้ใช้งานนี้หรือไม่?"><?=csrf_field()?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=e($r['pid'])?>"><button class="btn btn-danger-soft btn-sm">ลบ</button></form><?php else:?><span class="muted">ล็อก</span><?php endif;?></div></td></tr><?php endforeach;?></tbody></table></div></div>
<div class="card"><div class="card-header"><div><h3><?=$edit?'แก้ไขประเภท':'เพิ่มประเภทใหม่'?></h3><p>เช่น อาจารย์, บุคลากร</p></div></div><div class="card-body"><form method="post"><?=csrf_field()?><input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?=e($edit['pid']??0)?>"><div class="form-group"><label>ชื่อประเภท <span class="required">*</span></label><input class="form-control" name="pname" maxlength="100" required value="<?=e($_POST['pname']??($edit['pname']??''))?>"></div><div class="form-actions mt-2"><?php if($edit):?><a class="btn btn-soft" href="<?=e(url('admin/positions.php'))?>">ยกเลิก</a><?php endif;?><button class="btn btn-primary" type="submit">บันทึก</button></div></form></div></div></div>
<?php render_footer();?>
