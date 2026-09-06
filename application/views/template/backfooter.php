    </div>
  </main>
</div>
<script>
$(function(){
  $('.content-header,.breadcrumb').remove();
  $('.box-title').filter(function(){ return $.trim($(this).text()) === 'ตารางข้อมูล'; }).closest('.box-header').remove();
  $('.content p:empty,.box-body p:empty').remove();
  $('a.btn').each(function(){ if($.trim($(this).text()).toLowerCase()==='pwd') $(this).html('<i class="fa fa-key"></i><span>รหัสผ่าน</span>'); });
  $('.content > .box').addClass('et-panel');
  $('.box-body').addClass('et-panel-body');
  $('.content > .row > [class*="col-"] > .box').addClass('et-panel');
  $('.form-horizontal').addClass('et-form');
  $('.form-group').addClass('et-field-row');
  $('table').addClass('et-data-table');
  $('.et-menu-parent').on('click',function(e){
    e.preventDefault();
    var $group=$(this).closest('.et-menu-group');
    var willOpen=!$group.hasClass('open');
    $('.et-menu-group').not($group).removeClass('open');
    $group.toggleClass('open',willOpen);
  });
  function sidebar(open){ $('body').toggleClass('et-sidebar-open',open); }
  $('#etMenuToggle').on('click',function(){ sidebar(true); });
  $('#etSidebarClose,#etSidebarOverlay').on('click',function(){ sidebar(false); });
});
</script>
<?php
$etSaveSuccess = (bool)$this->session->flashdata('save_success');
$etDelSuccess  = (bool)$this->session->flashdata('del_success');
$etMessage     = $this->session->flashdata('message');
$etToastText   = $etDelSuccess ? 'ลบข้อมูลเรียบร้อยแล้ว' : ($etSaveSuccess ? 'บันทึกข้อมูลเรียบร้อยแล้ว' : (string)$etMessage);
$etToastError  = !$etSaveSuccess && !$etDelSuccess && !empty($etMessage);
?>
<?php if ($etToastText !== ''): ?>
<div id="etToast" class="et-toast <?php echo $etToastError ? 'et-toast-error' : 'et-toast-success'; ?>" role="status" aria-live="polite">
  <span class="et-toast-icon"><?php echo $etToastError ? '!' : '✓'; ?></span>
  <span><?php echo html_escape($etToastText); ?></span>
</div>
<style>
.et-toast{position:fixed;right:24px;bottom:24px;z-index:99999;display:flex;align-items:center;gap:10px;max-width:min(430px,calc(100vw - 32px));padding:14px 18px;border-radius:14px;box-shadow:0 14px 40px rgba(31,41,55,.14);font-size:14px;font-weight:650;transition:opacity .25s ease,transform .25s ease}
.et-toast-success{border:1px solid #d9eadf;background:#f3fbf6;color:#315c40}.et-toast-error{border:1px solid #f1d8dd;background:#fff5f7;color:#93475a}
.et-toast-icon{display:grid;place-items:center;width:26px;height:26px;border-radius:999px;background:rgba(255,255,255,.75);font-size:16px;font-weight:800;flex:0 0 26px}.et-toast.et-hide{opacity:0;transform:translateY(10px);pointer-events:none}
@media(max-width:640px){.et-toast{left:16px;right:16px;bottom:16px;max-width:none}}
</style>
<script>(function(){var t=document.getElementById('etToast');if(!t)return;setTimeout(function(){t.classList.add('et-hide')},3200);setTimeout(function(){if(t.parentNode)t.parentNode.removeChild(t)},3600)})();</script>
<?php endif; ?>
</body>
</html>
