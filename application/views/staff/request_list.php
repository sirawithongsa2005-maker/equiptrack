<div class="content-wrapper">
  <section class="content">
    <div class="et-card">
      <div class="et-card-header">
        <h3>คำขอยืมที่รออนุมัติ</h3>
      </div>

      <div class="table-responsive">
        <table class="table et-table dataTable">
          <thead>
            <tr>
              <th>นักศึกษา</th>
              <th>อุปกรณ์</th>
              <th>วัตถุประสงค์</th>
              <th>วันที่ขอ</th>
              <th>จัดการ</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($query)): ?>
              <tr><td colspan="5" class="et-empty">ไม่มีคำขอที่รออนุมัติ</td></tr>
            <?php else: foreach ($query as $row): ?>
              <tr>
                <td>
                  <strong><?php echo html_escape(trim($row->m_fname.$row->m_name.' '.$row->m_lname)); ?></strong>
                  <small><?php echo html_escape($row->m_email); ?></small>
                </td>
                <td>
                  <strong><?php echo html_escape($row->d_name); ?></strong>
                  <small><?php echo html_escape($row->d_id); ?></small>
                </td>
                <td><?php echo html_escape($row->ser_reason); ?></td>
                <td><?php echo html_escape($row->ser_request_date ?: $row->ser_datesave); ?></td>
                <td>
                  <div class="et-action-group">
                    <form method="post" action="<?php echo site_url('staff/approve_request/'.$row->ser_id); ?>" class="et-inline-action" onsubmit="return confirm('อนุมัติคำขอนี้?');"><?php echo et_csrf_field(); ?><button class="btn btn-success btn-xs" type="submit">อนุมัติ</button></form>
                    <form method="post" action="<?php echo site_url('staff/reject_request/'.$row->ser_id); ?>" class="et-inline-action" onsubmit="return confirm('ไม่อนุมัติคำขอนี้?');"><?php echo et_csrf_field(); ?><button class="btn btn-danger btn-xs" type="submit">ไม่อนุมัติ</button></form>
                  </div>
                </td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</div>
