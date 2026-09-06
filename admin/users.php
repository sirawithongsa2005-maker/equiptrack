<?php
require dirname(__DIR__).'/includes/bootstrap.php';
require_roles([1]);
if($_SERVER['REQUEST_METHOD']==='POST'){
    verify_csrf();
    if(post('action')==='delete'){
        $id=int_post('id'); $me=(int)current_user()['m_id'];
        if($id<1) flash('danger','ไม่พบผู้ใช้งานที่ต้องการลบ');
        elseif($id===$me) flash('danger','ไม่สามารถลบบัญชีที่กำลังใช้งานอยู่ได้');
        elseif((int)scalar('SELECT ref_pid FROM tbl_member WHERE m_id=?',[$id])===1 && (int)scalar('SELECT COUNT(*) FROM tbl_member WHERE ref_pid=1')<=1) flash('danger','ต้องมีบัญชีผู้ดูแลระบบอย่างน้อย 1 บัญชี');
        elseif((int)scalar('SELECT COUNT(*) FROM tbl_devices_service WHERE ref_m_id=? OR ser_staff_id_lend=? OR ser_staff_id_return=?',[$id,$id,$id])>0) flash('danger','ลบไม่ได้ เนื่องจากผู้ใช้งานนี้มีประวัติยืม–คืนอยู่ในระบบ');
        else { try{execute_sql('DELETE FROM tbl_member WHERE m_id=?',[$id]);flash('success','ลบผู้ใช้งานเรียบร้อย');}catch(Throwable $e){flash('danger','ลบผู้ใช้งานไม่สำเร็จ');} }
        redirect('admin/users.php');
    }
}
$rows=fetch_all('SELECT m.m_id,m.ref_pid,m.m_username,m.m_fname,m.m_name,m.m_lname,m.m_email,m.m_phone,m.m_img,m.m_datesave,p.pname FROM tbl_member m JOIN tbl_position p ON p.pid=m.ref_pid ORDER BY CASE m.ref_pid WHEN 1 THEN 0 WHEN 3 THEN 1 ELSE 2 END,m.m_id ASC');
render_header('จัดการผู้ใช้งาน','users');
?>
<div class="page-intro"><div><h2>ผู้ใช้งานทั้งหมด</h2><p>เพิ่ม แก้ไข และกำหนดประเภทบัญชีสำหรับผู้ดูแล เจ้าหน้าที่ และผู้ยืม</p></div><div class="actions"><a class="btn btn-primary" href="<?=e(url('admin/user_form.php'))?>">+ เพิ่มผู้ใช้งาน</a></div></div>
<div class="card"><div class="table-tools"><div class="table-search"><input type="search" placeholder="ค้นหาชื่อ, Username, อีเมล…" data-table-search="#usersTable"></div><span class="table-meta">ทั้งหมด <?=count($rows)?> บัญชี</span></div><div class="table-wrap"><table class="data-table" id="usersTable"><thead><tr><th>ผู้ใช้งาน</th><th>Username</th><th>ประเภท</th><th>ติดต่อ</th><th>วันที่เพิ่ม</th><th>จัดการ</th></tr></thead><tbody>
<?php if(!$rows):?><tr data-empty><td colspan="6"><div class="empty-state"><div class="empty-icon">👥</div><h3>ยังไม่มีผู้ใช้งาน</h3></div></td></tr><?php else: foreach($rows as $r):?>
<tr><td><div class="member-cell"><span class="mini-avatar"><?php if($img=member_image($r)):?><img src="<?=e($img)?>" alt=""><?php else:?><?=e(user_initial($r))?><?php endif;?></span><span><b class="cell-title"><?=e(full_name($r))?></b><small class="cell-sub">ID #<?=e($r['m_id'])?></small></span></div></td><td><b><?=e($r['m_username'])?></b></td><td><span class="badge <?=((int)$r['ref_pid']===1?'badge-primary':((int)$r['ref_pid']===3?'badge-warning':'badge-muted'))?>"><?=e($r['pname'])?></span></td><td><span class="cell-title"><?=e($r['m_phone']?:'-')?></span><small class="cell-sub"><?=e($r['m_email']?:'-')?></small></td><td><?=e(thai_date($r['m_datesave'],true))?></td><td><div class="btn-group"><a class="btn btn-soft btn-sm" href="<?=e(url('admin/user_form.php?id='.(int)$r['m_id']))?>">แก้ไข</a><?php if((int)$r['m_id']!==(int)current_user()['m_id']):?><form method="post" data-confirm="ลบบัญชี <?=e($r['m_username'])?> ออกจากระบบหรือไม่?" data-confirm-title="ลบผู้ใช้งาน"><?=csrf_field()?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=e($r['m_id'])?>"><button class="btn btn-danger-soft btn-sm" type="submit">ลบ</button></form><?php endif;?></div></td></tr>
<?php endforeach; endif;?></tbody></table></div></div>
<?php render_footer();?>
