<?php
$statusLabels=array('pending'=>'รออนุมัติ','approved'=>'กำลังยืม','borrowed'=>'กำลังยืม','rejected'=>'ไม่อนุมัติ','cancelled'=>'ยกเลิก','returned'=>'คืนแล้ว');
?>
<div class="content-wrapper"><section class="content">
  <div class="et-card">
    <div class="et-card-header"><div><h3>ประวัติการยืม–คืนทั้งหมด</h3><small class="et-card-subtitle">รวมคำขอ การอนุมัติ การยืม และการคืนในระบบ</small></div></div>
    <div class="table-responsive"><table class="table et-table dataTable">
      <thead><tr><th>วันที่</th><th>ผู้ยืม</th><th>ครุภัณฑ์</th><th>วัตถุประสงค์</th><th>สถานะ</th><th>เจ้าหน้าที่</th><th>วันที่คืน</th></tr></thead>
      <tbody>
      <?php if(empty($query)): ?><tr><td colspan="7" class="et-empty">ยังไม่มีประวัติการยืม–คืน</td></tr>
      <?php else: foreach($query as $row): $st=(string)$row->ser_status; ?>
        <tr>
          <td class="et-table-date"><?php echo html_escape($row->ser_date_lend ?: ($row->ser_request_date ?: $row->ser_datesave)); ?></td>
          <td><strong><?php echo html_escape(trim($row->m_fname.$row->m_name.' '.$row->m_lname)); ?></strong><small><?php echo html_escape($row->m_email); ?></small></td>
          <td><strong><?php echo html_escape($row->d_name); ?></strong><small><?php echo html_escape($row->d_id); ?></small></td>
          <td><?php echo nl2br(html_escape($row->ser_reason)); ?></td>
          <td><span class="et-status et-status-<?php echo html_escape($st); ?>"><?php echo html_escape(isset($statusLabels[$st])?$statusLabels[$st]:$st); ?></span></td>
          <td><?php echo html_escape($row->ser_staff_name_lend ?: '-'); ?></td>
          <td><?php echo html_escape($row->ser_date_return ?: '-'); ?></td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table></div>
  </div>
</section></div>
