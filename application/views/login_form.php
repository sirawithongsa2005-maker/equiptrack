<?php $loginMsg = $this->session->flashdata('msg'); ?>
<div class="et-login-card">
  <div class="et-login-brand"><span><i class="fa fa-cube"></i></span><b>EquipTrack</b></div>
  <div class="et-login-title">
    <span class="et-login-kicker">Equipment Borrowing System</span>
    <h1>ระบบยืม–คืนครุภัณฑ์</h1>
    <p>เข้าสู่ระบบเพื่อจัดการครุภัณฑ์ คำขอยืม และประวัติการใช้งาน</p>
  </div>

  <?php if ($loginMsg): ?>
    <div class="gateway-alert"><i class="fa fa-exclamation-circle"></i><span><?php echo html_escape($loginMsg); ?></span></div>
  <?php endif; ?>

  <form action="<?php echo site_url('user/check2'); ?>" method="post" autocomplete="off">
      <?php echo et_csrf_field(); ?>
    <div class="et-login-field">
      <label for="loginUsername">ชื่อผู้ใช้</label>
      <div><i class="fa fa-user"></i><input id="loginUsername" type="text" name="m_username" required autofocus autocomplete="username" maxlength="50" placeholder="กรอกชื่อผู้ใช้"></div>
    </div>
    <div class="et-login-field">
      <label for="loginPassword">รหัสผ่าน</label>
      <div><i class="fa fa-lock"></i><input id="loginPassword" type="password" name="m_password" required autocomplete="current-password" maxlength="255" placeholder="กรอกรหัสผ่าน"><button type="button" onclick="togglePassword()" aria-label="แสดงหรือซ่อนรหัสผ่าน"><i id="passIcon" class="fa fa-eye"></i></button></div>
    </div>
    <button class="et-login-submit" type="submit"><i class="fa fa-sign-in"></i> เข้าสู่ระบบ</button>
  </form>

  <div class="et-demo-login">
    <div class="et-demo-title"><i class="fa fa-info-circle"></i><span>บัญชีตัวอย่างสำหรับทดสอบระบบ</span></div>
    <div class="et-demo-grid">
      <div><strong>ผู้ดูแลระบบ</strong><span>admin / admin</span></div>
      <div><strong>เจ้าหน้าที่</strong><span>staff / staff</span></div>
      <div><strong>นักศึกษา</strong><span>student / student</span></div>
    </div>
  </div>
</div>
</div>
<script>
function togglePassword(){var p=document.getElementById('loginPassword'),i=document.getElementById('passIcon');var show=p.type==='password';p.type=show?'text':'password';i.className=show?'fa fa-eye-slash':'fa fa-eye';}
</script>
</body>
</html>
