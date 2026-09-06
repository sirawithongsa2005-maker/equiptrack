<div class="content-wrapper"><section class="content"><div class="et-card">
  <div class="et-card-header"><div><h3>รายงานตามประเภทครุภัณฑ์</h3><small class="et-card-subtitle">สรุปจำนวนรายการยืม–คืนของครุภัณฑ์แต่ละประเภท</small></div><a class="btn btn-default" href="<?php echo site_url('report/bytype_chart'); ?>"><i class="fa fa-bar-chart"></i> ดูกราฟ</a></div>
  <div class="table-responsive"><table class="table et-table dataTable"><thead><tr><th>#</th><th>ประเภทครุภัณฑ์</th><th>จำนวน</th><th>ดูรายละเอียด</th></tr></thead><tbody>
  <?php if(empty($query)): ?><tr><td colspan="4" class="et-empty">ยังไม่มีข้อมูล</td></tr><?php else: foreach($query as $row): ?>
  <tr><td><?php echo (int)$row->t_id; ?></td><td><strong><?php echo html_escape($row->t_name); ?></strong></td><td><span class="et-soft-label"><?php echo number_format((int)$row->total); ?> รายการ</span></td><td><?php if((int)$row->total>0): ?><a href="<?php echo site_url('report/viewbytype/'.$row->t_id); ?>" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> เปิดดู</a><?php else: ?><span class="text-muted">—</span><?php endif; ?></td></tr>
  <?php endforeach; endif; ?></tbody></table></div>
</div></section></div>
