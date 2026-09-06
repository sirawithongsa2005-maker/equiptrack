<?php
require dirname(__DIR__).'/includes/bootstrap.php';
require_roles([1]);
$id=get_int('id');
$row=$id?fetch_one('SELECT * FROM tbl_member WHERE m_id=?',[$id]):null;
if($id && !$row) render_error('ไม่พบผู้ใช้งาน','รายการที่ต้องการแก้ไขไม่มีอยู่ในระบบ');
$positions=fetch_all('SELECT * FROM tbl_position ORDER BY pid ASC');
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
  verify_csrf();
  $id=int_post('m_id'); $row=$id?fetch_one('SELECT * FROM tbl_member WHERE m_id=?',[$id]):null;
  $username=post('m_username'); $password=(string)($_POST['m_password']??''); $refPid=int_post('ref_pid');
  $fname=post('m_fname');$name=post('m_name');$lname=post('m_lname');$phone=post('m_phone');$email=post('m_email');
  if($username===''||text_len($username)>50)$errors[]='กรุณากรอก Username ไม่เกิน 50 ตัวอักษร';
  if($name==='')$errors[]='กรุณากรอกชื่อ';
  if(!$id && strlen($password)<4)$errors[]='รหัสผ่านต้องมีอย่างน้อย 4 ตัวอักษร';
  if($password!=='' && strlen($password)<4)$errors[]='รหัสผ่านต้องมีอย่างน้อย 4 ตัวอักษร';
  if(!$refPid || !(int)scalar('SELECT COUNT(*) FROM tbl_position WHERE pid=?',[$refPid]))$errors[]='กรุณาเลือกประเภทผู้ใช้งาน';
  if((int)scalar('SELECT COUNT(*) FROM tbl_member WHERE m_username=? AND m_id<>?',[$username,$id])>0)$errors[]='Username นี้ถูกใช้งานแล้ว';
  if($id===(int)current_user()['m_id'] && $refPid!==(int)current_user()['ref_pid'])$errors[]='ไม่สามารถเปลี่ยนสิทธิ์ของบัญชีที่กำลังใช้งานอยู่ได้';
  if($row && (int)$row['ref_pid']===1 && $refPid!==1 && (int)scalar('SELECT COUNT(*) FROM tbl_member WHERE ref_pid=1')<=1)$errors[]='ต้องมีบัญชีผู้ดูแลระบบอย่างน้อย 1 บัญชี';
  if(!$errors){
    try{
      $oldImg=$row['m_img']??null; $img=upload_image('m_img','members',$oldImg);
      if($id){
        $sql='UPDATE tbl_member SET ref_pid=?,m_username=?,m_fname=?,m_name=?,m_lname=?,m_phone=?,m_email=?,m_img=?'.($password!==''?',m_password=?':'').' WHERE m_id=?';
        $params=[$refPid,$username,$fname,$name,$lname,$phone,$email,$img]; if($password!=='')$params[]=password_hash($password,PASSWORD_DEFAULT);$params[]=$id; execute_sql($sql,$params);
        flash('success','แก้ไขข้อมูลผู้ใช้งานเรียบร้อย');
      }else{
        execute_sql('INSERT INTO tbl_member(ref_pid,m_username,m_password,m_fname,m_name,m_lname,m_phone,m_email,m_img) VALUES(?,?,?,?,?,?,?,?,?)',[$refPid,$username,password_hash($password,PASSWORD_DEFAULT),$fname,$name,$lname,$phone,$email,$img]);
        flash('success','เพิ่มผู้ใช้งานใหม่เรียบร้อย');
      }
      redirect('admin/users.php');
    }catch(Throwable $e){$errors[]=$e->getMessage();}
  }
}
$v=function($key,$default='')use($row){return $_SERVER['REQUEST_METHOD']==='POST'?($_POST[$key]??$default):($row[$key]??$default);};
render_header($id?'แก้ไขผู้ใช้งาน':'เพิ่มผู้ใช้งาน','users');
?>
<div class="page-intro"><div><h2><?=$id?'แก้ไขข้อมูลบัญชี':'สร้างบัญชีใหม่'?></h2><p>ข้อมูลทุกช่องจัดเก็บในฐานข้อมูลเดียวและสามารถแก้ไขภายหลังได้</p></div><a class="btn btn-soft" href="<?=e(url('admin/users.php'))?>">← กลับ</a></div>
<?php if($errors):?><div class="inline-alert danger"><b>!</b><span><?=implode('<br>',array_map('e',$errors))?></span></div><?php endif;?>
<form method="post" enctype="multipart/form-data"><input type="hidden" name="m_id" value="<?=e($id)?>"><?=csrf_field()?>
<div class="grid grid-3"><div class="card span-2"><div class="card-header"><div><h3>ข้อมูลผู้ใช้งาน</h3><p>จัดวางแบบชิดซ้าย อ่านง่าย และเป็นระเบียบ</p></div></div><div class="card-body"><div class="form-grid">
<div class="form-group"><label>ประเภทผู้ใช้งาน <span class="required">*</span></label><select class="form-control" name="ref_pid" required><option value="">เลือกประเภท</option><?php foreach($positions as $p):?><option value="<?=e($p['pid'])?>" <?=((int)$v('ref_pid')===(int)$p['pid']?'selected':'')?>><?=e($p['pname'])?></option><?php endforeach;?></select></div>
<div class="form-group"><label>คำนำหน้า</label><input class="form-control" name="m_fname" maxlength="30" value="<?=e($v('m_fname'))?>" placeholder="เช่น นาย / นางสาว"></div>
<div class="form-group"><label>ชื่อ <span class="required">*</span></label><input class="form-control" name="m_name" maxlength="100" required value="<?=e($v('m_name'))?>"></div>
<div class="form-group"><label>นามสกุล</label><input class="form-control" name="m_lname" maxlength="100" value="<?=e($v('m_lname'))?>"></div>
<div class="form-group"><label>เบอร์โทร</label><input class="form-control" name="m_phone" maxlength="20" inputmode="tel" value="<?=e($v('m_phone'))?>"></div>
<div class="form-group"><label>อีเมล / รหัสนักศึกษา</label><input class="form-control" name="m_email" maxlength="120" value="<?=e($v('m_email'))?>"></div>
</div></div></div>
<div class="card"><div class="card-header"><div><h3>บัญชีและรูป</h3><p>ใช้สำหรับเข้าสู่ระบบ</p></div></div><div class="card-body"><div class="form-group"><label>Username <span class="required">*</span></label><input class="form-control" name="m_username" maxlength="50" required autocomplete="off" value="<?=e($v('m_username'))?>"></div><div class="form-group mt-2"><label>รหัสผ่าน <?=$id?'<span class="muted">(เว้นว่างถ้าไม่เปลี่ยน)</span>':'<span class="required">*</span>'?></label><input class="form-control" type="password" name="m_password" minlength="4" autocomplete="new-password" <?=$id?'':'required'?>></div><div class="divider"></div><div class="image-uploader"><div class="image-preview" id="memberPreview"><?php $preview=$row?member_image($row):''; if($preview):?><img src="<?=e($preview)?>" alt=""><?php else:?>รูป<?php endif;?></div><div class="upload-copy"><b>รูปโปรไฟล์</b><small class="form-help">JPG, PNG, WEBP ไม่เกิน 5 MB</small><input type="file" name="m_img" accept="image/jpeg,image/png,image/webp" data-preview="#memberPreview"></div></div></div></div></div>
<div class="form-actions mt-2"><a class="btn btn-soft" href="<?=e(url('admin/users.php'))?>">ยกเลิก</a><button class="btn btn-primary" type="submit">บันทึกข้อมูล</button></div></form>
<?php render_footer();?>
