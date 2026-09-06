<div class="content-wrapper"><section class="content"><div class="et-card">
  <div class="et-card-header"><div><h3>รายการคืนครุภัณฑ์</h3><small class="et-card-subtitle">ประวัติรายการที่รับคืนเรียบร้อยแล้ว</small></div></div>
  <div class="table-responsive"><table class="table et-table dataTable"><thead><tr><th>ผู้ยืม</th><th>ครุภัณฑ์</th><th>วันที่ยืม</th><th>วันที่คืน</th><th>เจ้าหน้าที่รับคืน</th></tr></thead><tbody>
  <?php if(empty($query)): ?><tr><td colspan="5" class="et-empty">ยังไม่มีประวัติการคืน</td></tr><?php else: foreach($query as $row): ?>
  <tr><td><strong><?php echo html_escape(trim($row->m_fname.$row->m_name.' '.$row->m_lname)); ?></strong><small><?php echo html_escape($row->m_email); ?></small></td><td><strong><?php echo html_escape($row->d_name); ?></strong><small><?php echo html_escape($row->d_id); ?></small></td><td class="et-table-date"><?php echo html_escape($row->ser_date_lend ?: '-'); ?></td><td class="et-table-date"><?php echo html_escape($row->ser_date_return ?: '-'); ?></td><td><?php echo html_escape($row->ser_staff_name_return ?: '-'); ?></td></tr>
  <?php endforeach; endif; ?></tbody></table></div>
</div></section></div>
