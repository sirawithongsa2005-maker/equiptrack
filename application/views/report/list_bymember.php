<div class="content-wrapper"><section class="content"><div class="et-card">
  <div class="et-card-header"><div><h3>รายงานแยกตามผู้ยืม</h3><small class="et-card-subtitle">จำนวนรายการยืม–คืนของผู้ยืมแต่ละคน</small></div></div>
  <div class="table-responsive"><table class="table et-table dataTable"><thead><tr><th>#</th><th>ผู้ยืม</th><th>ข้อมูล</th><th>จำนวน</th><th>ดูรายละเอียด</th></tr></thead><tbody>
  <?php if(empty($query)): ?><tr><td colspan="5" class="et-empty">ยังไม่มีข้อมูลผู้ยืม</td></tr><?php else: foreach($query as $row): ?>
  <tr><td><?php echo (int)$row->m_id; ?></td><td><strong><?php echo html_escape(trim($row->m_fname.$row->m_name.' '.$row->m_lname)); ?></strong></td><td><?php echo html_escape($row->m_email ?: '-'); ?></td><td><span class="et-soft-label"><?php echo number_format((int)$row->total); ?> รายการ</span></td><td><?php if((int)$row->total>0): ?><a href="<?php echo site_url('report/viewbymember/'.$row->m_id); ?>" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> เปิดดู</a><?php else: ?><span class="text-muted">—</span><?php endif; ?></td></tr>
  <?php endforeach; endif; ?></tbody></table></div>
</div></section></div>
