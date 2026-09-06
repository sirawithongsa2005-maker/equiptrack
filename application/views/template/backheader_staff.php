<?php
$roleTitle='เจ้าหน้าที่ห้องคอม'; $roleBadge='STAFF'; $homeUrl='staff';
$menuItems=array(
 array('label'=>'คำขอยืม','icon'=>'fa-check-circle','url'=>'staff'),
 array('label'=>'บันทึกการยืม','icon'=>'fa-plus-circle','url'=>'staff/add_lend4'),
 array('label'=>'กำลังยืม','icon'=>'fa-exchange','url'=>'staff/loans'),
 array('label'=>'รายการคืน','icon'=>'fa-undo','url'=>'staff/return_list'),
 array('label'=>'ครุภัณฑ์','icon'=>'fa-laptop','url'=>'staff/devices'),
 array('label'=>'ครุภัณฑ์ชำรุด','icon'=>'fa-warning','url'=>'staff/damaged_list'),
 array('label'=>'ประเภทครุภัณฑ์','icon'=>'fa-tags','url'=>'staff/type'),
 array('label'=>'สถานะครุภัณฑ์','icon'=>'fa-wrench','url'=>'staff/status'),
 array('label'=>'ข้อมูลส่วนตัว','icon'=>'fa-user','url'=>'staff/profile'),
 array('label'=>'รายงาน','icon'=>'fa-bar-chart','url'=>'report')
);
include APPPATH.'views/template/modern_header.php';
