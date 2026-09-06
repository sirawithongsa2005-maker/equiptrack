<?php
$roleTitle='ผู้ดูแลระบบ'; $roleBadge='ADMIN'; $homeUrl='admin';
$menuItems=array(
 array('label'=>'ภาพรวม','icon'=>'fa-dashboard','url'=>'admin'),
 array('label'=>'ครุภัณฑ์','icon'=>'fa-laptop','url'=>'admin/devices'),
 array('label'=>'ผู้ใช้งาน','icon'=>'fa-users','url'=>'member'),
 array('label'=>'ประเภทผู้ใช้งาน','icon'=>'fa-sitemap','url'=>'position'),
 array('label'=>'รายงาน','icon'=>'fa-bar-chart','url'=>'report')
);
include APPPATH.'views/template/modern_header.php';
