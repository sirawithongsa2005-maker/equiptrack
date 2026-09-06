<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[\AllowDynamicProperties]
class Status_model extends CI_Model
{
    public function list_status(){ return $this->db->order_by('s_id','ASC')->get('tbl_devices_status')->result(); }
    public function list_status_return(){ return $this->db->where_in('s_id',array(1,3))->order_by('s_id','ASC')->get('tbl_devices_status')->result(); }
    public function name_exists($name,$except_id=NULL){ $this->db->where('s_name',trim((string)$name)); if($except_id!==NULL) $this->db->where('s_id !=',(int)$except_id); return $this->db->count_all_results('tbl_devices_status')>0; }
    public function addstatus_db(){ return $this->db->insert('tbl_devices_status',array('s_name'=>trim((string)$this->input->post('s_name')))); }
    public function read($s_id){ $q=$this->db->get_where('tbl_devices_status',array('s_id'=>(int)$s_id),1); return $q->num_rows()?$q->row():FALSE; }
    public function editstatus_db(){ $s_id=(int)$this->input->post('s_id'); if(in_array($s_id,array(1,2,3),TRUE)) return FALSE; return $this->db->where('s_id',$s_id)->update('tbl_devices_status',array('s_name'=>trim((string)$this->input->post('s_name')))); }
    public function del_status_db($s_id){
        $s_id=(int)$s_id; if(in_array($s_id,array(1,2,3),TRUE)) return FALSE;
        if($this->db->where('ref_s_id',$s_id)->count_all_results('tbl_devices')>0) return FALSE;
        return $this->db->delete('tbl_devices_status',array('s_id'=>$s_id));
    }
}
