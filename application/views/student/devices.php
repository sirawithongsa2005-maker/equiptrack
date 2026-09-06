<div class="content-wrapper">
  <section class="content">
    <?php if (validation_errors()): ?>
      <div class="alert alert-danger"><?php echo validation_errors(); ?></div>
    <?php endif; ?>

    <form method="post" action="<?php echo site_url('student/request_borrow'); ?>" class="et-form">
        <?php echo et_csrf_field(); ?>
      <div class="et-card">
        <div class="et-card-header">
          <h3>เลือกอุปกรณ์ที่ต้องการยืม</h3>
        </div>

        <div class="table-responsive">
          <table class="table et-table dataTable">
            <thead>
              <tr>
                <th class="et-check-cell">เลือก</th>
                <th>อุปกรณ์</th>
                <th>ประเภท</th>
                <th>รายละเอียด</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($query)): ?>
                <tr><td colspan="4" class="et-empty">ไม่มีอุปกรณ์ที่พร้อมให้ยืม</td></tr>
              <?php else: foreach ($query as $row): ?>
                <tr>
                  <td class="et-check-cell"><input type="checkbox" name="device[]" value="<?php echo html_escape($row->d_id); ?>"></td>
                  <td>
                    <strong><?php echo html_escape($row->d_name); ?></strong>
                    <small><?php echo html_escape($row->d_id); ?></small>
                  </td>
                  <td><?php echo html_escape($row->t_name); ?></td>
                  <td><?php echo html_escape($row->d_detail); ?></td>
                </tr>
              <?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>

        <div class="et-form-section">
          <label for="ser_reason">วัตถุประสงค์การยืม</label>
          <textarea id="ser_reason" class="form-control" name="ser_reason" rows="3" required><?php echo html_escape((string)set_value('ser_reason')); ?></textarea>
        </div>

        <div class="et-form-actions">
          <a href="<?php echo site_url('student'); ?>" class="btn btn-default">ยกเลิก</a>
          <button class="btn btn-primary" type="submit"><i class="fa fa-check-circle"></i> ส่งคำขอยืม</button>
        </div>
      </div>
    </form>
  </section>
</div>
