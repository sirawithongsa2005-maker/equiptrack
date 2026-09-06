<?php
require_login();
$user=current_user();
$flashes=pull_flashes();
$pending=(is_staff()||is_admin())?pending_count():0;
?>
<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=e($title)?> • EquipTrack</title>
<link rel="stylesheet" href="<?=e(asset('css/app.css'))?>?v=1.0.0">
</head>
<body>
<div class="app-shell" data-app-shell>
  <aside class="sidebar" data-sidebar>
    <a class="brand" href="<?=e(url(home_for_role()))?>"><span class="brand-mark">ET</span><span><strong>EquipTrack</strong><small>Equipment System</small></span></a>
    <nav class="nav">
      <?php if(is_admin()): ?>
        <div class="nav-label">ภาพรวม</div>
        <a class="nav-link <?=$active==='dashboard'?'active':''?>" href="<?=e(url('admin/dashboard.php'))?>"><span>⌂</span>แดชบอร์ด</a>
        <div class="nav-label">ผู้ใช้งาน</div>
        <a class="nav-link <?=$active==='users'?'active':''?>" href="<?=e(url('admin/users.php'))?>"><span>👥</span>ผู้ใช้งาน</a>
        <a class="nav-link <?=$active==='positions'?'active':''?>" href="<?=e(url('admin/positions.php'))?>"><span>◈</span>ประเภทผู้ใช้</a>
        <div class="nav-label">ยืม–คืน</div>
        <a class="nav-link <?=$active==='requests'?'active':''?>" href="<?=e(url('staff/requests.php'))?>"><span>◷</span>คำขอยืม<?php if($pending):?><b class="nav-badge"><?=$pending?></b><?php endif;?></a>
        <a class="nav-link <?=$active==='direct-loan'?'active':''?>" href="<?=e(url('staff/direct_loan.php'))?>"><span>＋</span>บันทึกยืม</a>
        <a class="nav-link <?=$active==='loans'?'active':''?>" href="<?=e(url('staff/loans.php'))?>"><span>↗</span>กำลังยืม</a>
        <a class="nav-link <?=$active==='returns'?'active':''?>" href="<?=e(url('staff/returns.php'))?>"><span>↙</span>ประวัติคืน</a>
        <div class="nav-label">ครุภัณฑ์</div>
        <a class="nav-link <?=$active==='devices'?'active':''?>" href="<?=e(url('staff/devices.php'))?>"><span>▣</span>ครุภัณฑ์</a>
        <a class="nav-link <?=$active==='damaged'?'active':''?>" href="<?=e(url('staff/damaged.php'))?>"><span>⚠</span>ชำรุด</a>
        <a class="nav-link <?=$active==='types'?'active':''?>" href="<?=e(url('staff/types.php'))?>"><span>◇</span>ประเภทครุภัณฑ์</a>
        <a class="nav-link <?=$active==='statuses'?'active':''?>" href="<?=e(url('staff/statuses.php'))?>"><span>●</span>สถานะครุภัณฑ์</a>
        <a class="nav-link <?=$active==='reports'?'active':''?>" href="<?=e(url('reports/index.php'))?>"><span>▤</span>รายงาน</a>
      <?php elseif(is_staff()): ?>
        <div class="nav-label">ภาพรวม</div>
        <a class="nav-link <?=$active==='dashboard'?'active':''?>" href="<?=e(url('staff/dashboard.php'))?>"><span>⌂</span>แดชบอร์ด</a>
        <div class="nav-label">ยืม–คืน</div>
        <a class="nav-link <?=$active==='requests'?'active':''?>" href="<?=e(url('staff/requests.php'))?>"><span>◷</span>คำขอยืม<?php if($pending):?><b class="nav-badge"><?=$pending?></b><?php endif;?></a>
        <a class="nav-link <?=$active==='direct-loan'?'active':''?>" href="<?=e(url('staff/direct_loan.php'))?>"><span>＋</span>บันทึกยืม</a>
        <a class="nav-link <?=$active==='loans'?'active':''?>" href="<?=e(url('staff/loans.php'))?>"><span>↗</span>กำลังยืม</a>
        <a class="nav-link <?=$active==='returns'?'active':''?>" href="<?=e(url('staff/returns.php'))?>"><span>↙</span>ประวัติคืน</a>
        <div class="nav-label">ครุภัณฑ์</div>
        <a class="nav-link <?=$active==='devices'?'active':''?>" href="<?=e(url('staff/devices.php'))?>"><span>▣</span>รายการครุภัณฑ์</a>
        <a class="nav-link <?=$active==='damaged'?'active':''?>" href="<?=e(url('staff/damaged.php'))?>"><span>⚠</span>ครุภัณฑ์ชำรุด</a>
        <a class="nav-link <?=$active==='types'?'active':''?>" href="<?=e(url('staff/types.php'))?>"><span>◇</span>ประเภทครุภัณฑ์</a>
        <a class="nav-link <?=$active==='statuses'?'active':''?>" href="<?=e(url('staff/statuses.php'))?>"><span>●</span>สถานะครุภัณฑ์</a>
        <a class="nav-link <?=$active==='reports'?'active':''?>" href="<?=e(url('reports/index.php'))?>"><span>▤</span>รายงาน</a>
      <?php else: ?>
        <div class="nav-label">เมนูของฉัน</div>
        <a class="nav-link <?=$active==='dashboard'?'active':''?>" href="<?=e(url('student/dashboard.php'))?>"><span>⌂</span>ภาพรวม</a>
        <a class="nav-link <?=$active==='borrow'?'active':''?>" href="<?=e(url('student/borrow.php'))?>"><span>＋</span>ยืมครุภัณฑ์</a>
        <a class="nav-link <?=$active==='my-requests'?'active':''?>" href="<?=e(url('student/requests.php'))?>"><span>▤</span>คำขอของฉัน</a>
      <?php endif; ?>
    </nav>
    <div class="sidebar-user">
      <a href="<?=e(url('profile.php'))?>" class="user-link <?=$active==='profile'?'active':''?>">
        <?php if($img=member_image($user)):?><img src="<?=e($img)?>" alt=""><?php else:?><span class="avatar"><?=e(user_initial($user))?></span><?php endif;?>
        <span class="user-meta"><strong><?=e(full_name($user))?></strong><small><?=e($user['pname'])?></small></span>
      </a>
      <form method="post" action="<?=e(url('auth/logout.php'))?>" class="logout-form"><?=csrf_field()?><button class="icon-btn danger" type="submit" title="ออกจากระบบ">↪</button></form>
    </div>
  </aside>
  <div class="sidebar-backdrop" data-sidebar-backdrop></div>
  <main class="main">
    <header class="topbar">
      <button class="menu-btn" type="button" data-menu-toggle aria-label="เปิดเมนู">☰</button>
      <div class="page-heading"><h1><?=e($title)?></h1><span><?=e(date('d/m/Y'))?></span></div>
      <a class="profile-chip" href="<?=e(url('profile.php'))?>"><span class="dot"></span><?=e($user['m_username'])?></a>
    </header>
    <div class="page-content">
      <?php foreach($flashes as $f):?>
        <div class="toast toast-<?=e($f['type'])?>" data-toast><div class="toast-icon"><?=($f['type']==='success'?'✓':($f['type']==='danger'?'!':'i'))?></div><div><strong><?=e($f['title']?:($f['type']==='success'?'สำเร็จ':'แจ้งเตือน'))?></strong><p><?=e($f['message'])?></p></div><button type="button" data-toast-close>×</button><span class="toast-progress"></span></div>
      <?php endforeach;?>
