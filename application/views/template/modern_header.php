<?php
$roleTitle = isset($roleTitle) ? $roleTitle : 'ผู้ใช้งาน';
$menuItems = isset($menuItems) ? $menuItems : array();
$currentUri = trim($this->uri->uri_string(), '/');
$userName = $this->session->userdata('m_name') ?: 'ผู้ใช้งาน';
$userImg = $this->session->userdata('m_img');
$imgUrl = et_media_url('uploads', $userImg, 'dist/img/avatar5.png');
$home = isset($homeUrl) ? $homeUrl : '';
$pageTitle = 'ภาพรวม';
foreach ($menuItems as $item) {
  if (!empty($item['children'])) {
    foreach ($item['children'] as $ch) {
      $u = trim($ch['url'], '/');
      if ($currentUri === $u || ($u !== '' && strpos($currentUri, $u.'/') === 0)) { $pageTitle = $ch['label']; }
    }
  } else {
    $u = trim($item['url'], '/');
    if ($currentUri === $u || ($u !== '' && strpos($currentUri, $u.'/') === 0)) { $pageTitle = $item['label']; }
  }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo html_escape($pageTitle); ?> | EquipTrack</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <?php echo link_tag('bootstrap/css/bootstrap.min.css'); ?>
  <?php echo link_tag('dist/css/mycustom.css?v=20260906-ui-fix-v3'); ?>
  <script src="<?php echo base_url(); ?>dist/js/et-icons.js?v=20260826-icons" defer></script>
  <script src="<?php echo base_url(); ?>dist/js/et-table.js?v=20260826-table" defer></script>
  <script src="<?php echo base_url(); ?>plugins/jQuery/jquery-3.7.1.min.js"></script>
  <script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js"></script>
</head>
<body class="et-body">
<div class="et-layout">
  <aside class="et-sidebar" id="etSidebar">
    <div class="et-sidebar-head">
      <a class="et-logo" href="<?php echo site_url($home); ?>">
        <span class="et-logo-mark"><i class="fa fa-cubes"></i></span>
        <span class="et-logo-text">EquipTrack</span>
      </a>
      <button class="et-sidebar-close" id="etSidebarClose" type="button"><i class="fa fa-times"></i></button>
    </div>

    <div class="et-user-card">
      <img src="<?php echo html_escape($imgUrl); ?>" alt="รูปผู้ใช้งาน">
      <div><strong><?php echo html_escape($userName); ?></strong><span><?php echo html_escape($roleTitle); ?></span></div>
    </div>

    <nav class="et-menu">
      <?php foreach ($menuItems as $item): ?>
        <?php if (!empty($item['children'])): ?>
          <?php $childActive=false; foreach($item['children'] as $ch){ $u=trim($ch['url'],'/'); if($currentUri===$u || ($u!=='' && strpos($currentUri,$u.'/')===0)){$childActive=true;break;} } ?>
          <div class="et-menu-group <?php echo $childActive?'open active-group':''; ?>">
            <button class="et-menu-parent" type="button"><span class="et-menu-icon"><i class="fa <?php echo $item['icon']; ?>"></i></span><span><?php echo html_escape($item['label']); ?></span><i class="fa fa-angle-down et-menu-arrow"></i></button>
            <div class="et-submenu">
              <?php foreach($item['children'] as $ch): $u=trim($ch['url'],'/'); $active=($currentUri===$u || ($u!=='' && strpos($currentUri,$u.'/')===0)); ?>
                <a class="<?php echo $active?'active':''; ?>" href="<?php echo site_url($ch['url']); ?>"><span><?php echo html_escape($ch['label']); ?></span></a>
              <?php endforeach; ?>
            </div>
          </div>
        <?php else: $u=trim($item['url'],'/'); $active=($currentUri===$u || ($u!=='' && strpos($currentUri,$u.'/')===0)); ?>
          <a class="et-menu-item <?php echo $active?'active':''; ?>" href="<?php echo site_url($item['url']); ?>">
            <span class="et-menu-icon"><i class="fa <?php echo $item['icon']; ?>"></i></span><span><?php echo html_escape($item['label']); ?></span>
          </a>
        <?php endif; ?>
      <?php endforeach; ?>
    </nav>

    <div class="et-sidebar-foot">
      <a href="<?php echo site_url('user/logout'); ?>" onclick="return confirm('ต้องการออกจากระบบใช่หรือไม่?');"><i class="fa fa-sign-out"></i><span>ออกจากระบบ</span></a>
    </div>
  </aside>

  <div class="et-sidebar-overlay" id="etSidebarOverlay"></div>

  <main class="et-main">
    <header class="et-topbar">
      <div class="et-topbar-left">
        <button type="button" class="et-menu-toggle" id="etMenuToggle"><i class="fa fa-bars"></i></button>
        <div><span class="et-topbar-kicker">EquipTrack</span><h1 id="etCurrentPage"><?php echo html_escape($pageTitle); ?></h1></div>
      </div>
      <div class="et-topbar-actions">
        <div class="et-top-user dropdown">
          <button class="dropdown-toggle" data-toggle="dropdown" type="button"><img src="<?php echo html_escape($imgUrl); ?>" alt="รูปผู้ใช้งาน"><span><?php echo html_escape($userName); ?></span><i class="fa fa-angle-down"></i></button>
          <ul class="dropdown-menu dropdown-menu-right"><li><a href="<?php echo site_url('user/logout'); ?>"><i class="fa fa-sign-out"></i> ออกจากระบบ</a></li></ul>
        </div>
      </div>
    </header>
    <div class="et-content">
