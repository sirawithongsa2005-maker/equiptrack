<div class="content-wrapper"><section class="content"><div class="et-card">
  <div class="et-card-header"><div><h3>สรุปรายการยืม–คืนรายวัน</h3><small class="et-card-subtitle">จำนวนรายการที่ถูกบันทึกแยกตามวัน</small></div></div>
  <div class="table-responsive"><table class="table et-table dataTable"><thead><tr><th>วัน</th><th>จำนวนรายการ</th></tr></thead><tbody>
  <?php if(empty($query)): ?><tr><td colspan="2" class="et-empty">ยังไม่มีข้อมูล</td></tr><?php else: foreach($query as $row): ?>
  <tr><td><strong><?php echo html_escape($row->datesave); ?></strong></td><td><span class="et-soft-label"><?php echo number_format((int)$row->total); ?> รายการ</span></td></tr>
  <?php endforeach; endif; ?></tbody></table></div>
</div></section></div>
