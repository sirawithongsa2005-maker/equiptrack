<?php
$rows = is_array($query) ? $query : array();
$max = 0;
foreach ($rows as $row) $max = max($max, (int)$row->total);
?>
<div class="content-wrapper">
  <section class="content">
    <div class="et-page-head">
      <div>
        <span class="et-eyebrow">REPORT</span>
        <h1>สรุปการยืม–คืนตามประเภทผู้ใช้งาน</h1>
        <p>เปรียบเทียบจำนวนรายการของผู้ใช้งานแต่ละประเภท โดยไม่ต้องพึ่งไลบรารีจากอินเทอร์เน็ต</p>
      </div>
      <a class="btn btn-default" href="<?php echo site_url('report/byposition'); ?>"><i class="fa fa-fw fa-bars"></i> ดูแบบตาราง</a>
    </div>
    <div class="et-card">
      <div class="et-card-body">
        <?php if (!$rows): ?>
          <div class="et-empty"><i class="fa fa-fw fa-info-circle"></i><div>ยังไม่มีข้อมูลสำหรับสร้างกราฟ</div></div>
        <?php else: ?>
          <div class="et-bars">
            <?php foreach ($rows as $row): $pct = $max > 0 ? round(((int)$row->total / $max) * 100, 2) : 0; ?>
              <div class="et-bar-row">
                <div class="et-bar-meta"><strong><?php echo html_escape($row->pname); ?></strong><span><?php echo number_format((int)$row->total); ?> รายการ</span></div>
                <div class="et-bar-track"><span style="width:<?php echo $pct; ?>%"></span></div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
</div>
