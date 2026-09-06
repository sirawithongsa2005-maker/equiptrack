<div class="content-wrapper">
  <section class="content">
    <div class="et-card">
      <div class="et-card-header">
        <h3>คำขอยืมของฉัน</h3>
        <a class="btn btn-primary" href="<?php echo site_url('student/devices'); ?>"><i class="fa fa-plus-circle"></i> ยืมอุปกรณ์</a>
      </div>

      <div class="table-responsive">
        <table class="table et-table dataTable">
          <thead>
            <tr>
              <th>อุปกรณ์</th>
              <th>วันที่ขอ</th>
              <th>วัตถุประสงค์</th>
              <th>สถานะ</th>
              <th>จัดการ</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($query)): ?>
              <tr><td colspan="5" class="et-empty">ยังไม่มีคำขอยืม</td></tr>
            <?php else: foreach ($query as $row): ?>
              <?php
                $st = $row->ser_status ?: ($row->ser_date_return ? 'returned' : 'borrowed');
                $labels = array(
                  'pending' => 'รออนุมัติ',
                  'approved' => 'กำลังยืม',
                  'borrowed' => 'กำลังยืม',
                  'rejected' => 'ไม่อนุมัติ',
                  'cancelled' => 'ยกเลิก',
                  'returned' => 'คืนแล้ว'
                );
                $label = isset($labels[$st]) ? $labels[$st] : $st;
              ?>
              <tr>
                <td>
                  <strong><?php echo html_escape($row->d_name); ?></strong>
                  <small><?php echo html_escape($row->d_id); ?></small>
                </td>
                <td class="et-table-date"><?php echo html_escape($row->ser_request_date ?: $row->ser_datesave); ?></td>
                <td><?php echo html_escape($row->ser_reason); ?></td>
                <td><span class="et-status et-status-<?php echo html_escape($st); ?>"><?php echo html_escape($label); ?></span></td>
                <td>
                  <?php if ($st === 'pending'): ?>
                    <form method="post" action="<?php echo site_url('student/cancel_request/'.$row->ser_id); ?>" class="et-inline-action" onsubmit="return confirm('ยกเลิกคำขอนี้?');"><?php echo et_csrf_field(); ?><button class="btn btn-default btn-xs" type="submit">ยกเลิก</button></form>
                  <?php else: ?>
                    <span class="text-muted">—</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</div>
