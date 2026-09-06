(function(){
'use strict';
function initTable(table){
  if(!table || table.dataset.etTableReady==='1') return;
  table.dataset.etTableReady='1';
  var tbody=table.tBodies[0];
  if(!tbody) return;
  var rows=Array.prototype.slice.call(tbody.rows);
  if(rows.length<1) return;
  var pageSize=10,page=1,query='';
  var wrap=document.createElement('div'); wrap.className='et-table-tools';
  var left=document.createElement('div'); left.className='et-table-size';
  left.innerHTML='<span>แสดง</span><select aria-label="จำนวนรายการ"><option>10</option><option>25</option><option>50</option><option>100</option></select><span>รายการ</span>';
  var search=document.createElement('input'); search.type='search'; search.className='et-table-search'; search.placeholder='ค้นหา...'; search.setAttribute('aria-label','ค้นหาในตาราง');
  wrap.appendChild(left); wrap.appendChild(search);
  var host=table.parentNode; host.insertBefore(wrap,table);
  var foot=document.createElement('div'); foot.className='et-table-footer';
  var info=document.createElement('span'); info.className='et-table-info';
  var pager=document.createElement('div'); pager.className='et-table-pager';
  foot.appendChild(info); foot.appendChild(pager); host.appendChild(foot);
  function filtered(){return rows.filter(function(r){return !query || r.textContent.toLowerCase().indexOf(query)!==-1;});}
  function render(){
    var list=filtered(),pages=Math.max(1,Math.ceil(list.length/pageSize)); if(page>pages) page=pages;
    rows.forEach(function(r){r.style.display='none';});
    var start=(page-1)*pageSize,end=Math.min(start+pageSize,list.length);
    for(var i=start;i<end;i++) list[i].style.display='';
    info.textContent=list.length?((start+1)+'–'+end+' จาก '+list.length):'ไม่มีข้อมูล';
    pager.innerHTML='';
    function b(label,target,disabled,active){var x=document.createElement('button');x.type='button';x.textContent=label;x.disabled=disabled;x.className=active?'active':'';x.addEventListener('click',function(){page=target;render();});pager.appendChild(x);}
    b('ก่อนหน้า',Math.max(1,page-1),page===1,false);
    var a=Math.max(1,page-2),z=Math.min(pages,a+4);a=Math.max(1,z-4);
    for(var p=a;p<=z;p++) b(String(p),p,false,p===page);
    b('ถัดไป',Math.min(pages,page+1),page===pages,false);
  }
  left.querySelector('select').addEventListener('change',function(){pageSize=parseInt(this.value,10)||10;page=1;render();});
  search.addEventListener('input',function(){query=this.value.trim().toLowerCase();page=1;render();});
  render();
}
function boot(){document.querySelectorAll('table.dataTable').forEach(initTable);}
if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',boot);else boot();
})();
