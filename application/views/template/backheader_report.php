<?php
$level=(int)$this->session->userdata('m_level');
$homeUrl=$level===1?'admin':'staff';
$roleTitle='รายงาน'; $roleBadge='REPORT';
$menuItems=array(
 array('label'=>'กลับหน้าหลัก','icon'=>'fa-home','url'=>$homeUrl),
 array('label'=>'ประวัติทั้งหมด','icon'=>'fa-history','url'=>'report'),
 array('label'=>'รายนักศึกษา','icon'=>'fa-user','url'=>'report/bymember'),
 array('label'=>'ตามประเภทผู้ใช้งาน','icon'=>'fa-sitemap','url'=>'report/byposition'),
 array('label'=>'ตามประเภท','icon'=>'fa-tags','url'=>'report/bytype'),
 array('label'=>'ช่วงวันที่','icon'=>'fa-calendar','url'=>'report/searchbydate'),
 array('label'=>'รายวัน','icon'=>'fa-calendar-o','url'=>'report/byday'),
 array('label'=>'รายเดือน','icon'=>'fa-calendar','url'=>'report/bymonth'),
 array('label'=>'รายปี','icon'=>'fa-line-chart','url'=>'report/byyear')
);
include APPPATH.'views/template/modern_header.php';
