<?php
$roleTitle='ผู้ยืม'; $roleBadge='STUDENT'; $homeUrl='student';
$menuItems=array(
 array('label'=>'คำขอของฉัน','icon'=>'fa-history','url'=>'student'),
 array('label'=>'ยืมอุปกรณ์','icon'=>'fa-laptop','url'=>'student/devices'),
 array('label'=>'ข้อมูลส่วนตัว','icon'=>'fa-user','url'=>'student/profile'),
 array('label'=>'เปลี่ยนรหัสผ่าน','icon'=>'fa-key','url'=>'student/pwd')
);
include APPPATH.'views/template/modern_header.php';
