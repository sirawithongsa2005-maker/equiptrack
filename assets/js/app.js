(()=>{
  const qs=(s,r=document)=>r.querySelector(s), qsa=(s,r=document)=>[...r.querySelectorAll(s)];
  const shell=qs('[data-app-shell]');
  qs('[data-menu-toggle]')?.addEventListener('click',()=>shell?.classList.toggle('sidebar-open'));
  qs('[data-sidebar-backdrop]')?.addEventListener('click',()=>shell?.classList.remove('sidebar-open'));
  qsa('[data-toast]').forEach(t=>{const close=()=>{t.style.opacity='0';t.style.transform='translateY(-6px)';setTimeout(()=>t.remove(),180)};qs('[data-toast-close]',t)?.addEventListener('click',close);setTimeout(close,5000)});
  const pwd=qs('[data-toggle-password]');
  pwd?.addEventListener('click',()=>{const input=qs('#password');if(!input)return; const show=input.type==='password';input.type=show?'text':'password';pwd.textContent=show?'ซ่อน':'แสดง'});
  qsa('[data-demo]').forEach(b=>b.addEventListener('click',()=>{const [u,p]=b.dataset.demo.split('|');const ui=qs('#username'),pi=qs('#password');if(ui&&pi){ui.value=u;pi.value=p;pi.focus()}}));
  qsa('input[type=file][data-preview]').forEach(input=>input.addEventListener('change',()=>{const target=qs(input.dataset.preview);const f=input.files?.[0];if(!target||!f)return;const reader=new FileReader();reader.onload=()=>{if(target.tagName==='IMG')target.src=reader.result;else target.innerHTML=`<img src="${reader.result}" alt="preview">`};reader.readAsDataURL(f)}));
  qsa('[data-table-search]').forEach(input=>input.addEventListener('input',()=>{const table=qs(input.dataset.tableSearch);if(!table)return;const q=input.value.trim().toLocaleLowerCase('th');qsa('tbody tr',table).forEach(row=>{if(row.dataset.empty!==undefined)return;row.hidden=q!==''&&!row.textContent.toLocaleLowerCase('th').includes(q)})}));
  const layer=qs('[data-confirm-layer]'); let pendingForm=null;
  qsa('form[data-confirm]').forEach(form=>form.addEventListener('submit',e=>{if(form.dataset.confirmed==='1')return;e.preventDefault();pendingForm=form;if(!layer){if(confirm(form.dataset.confirm||'ยืนยันการทำรายการ?')){form.dataset.confirmed='1';form.submit()}return;}qs('[data-confirm-title]',layer).textContent=form.dataset.confirmTitle||'ยืนยันการทำรายการ';qs('[data-confirm-message]',layer).textContent=form.dataset.confirm||'คุณต้องการดำเนินการต่อหรือไม่';layer.hidden=false;}));
  qs('[data-confirm-cancel]',layer)?.addEventListener('click',()=>{layer.hidden=true;pendingForm=null});
  qs('[data-confirm-ok]',layer)?.addEventListener('click',()=>{if(!pendingForm)return;const f=pendingForm;pendingForm=null;layer.hidden=true;f.dataset.confirmed='1';f.requestSubmit?f.requestSubmit():f.submit()});
  layer?.addEventListener('click',e=>{if(e.target===layer){layer.hidden=true;pendingForm=null}});
  qsa('form:not([data-no-loading])').forEach(form=>form.addEventListener('submit',()=>{if(!form.checkValidity())return;qsa('button[type=submit]',form).forEach(btn=>{if(btn.dataset.keepEnabled!==undefined)return;setTimeout(()=>{btn.disabled=true;btn.dataset.oldText=btn.innerHTML;btn.innerHTML='กำลังบันทึก…'},20)})}));
  qsa('[data-check-all]').forEach(master=>master.addEventListener('change',()=>qsa(`input[name="${master.dataset.checkAll}"]`).forEach(c=>c.checked=master.checked)));
})();
