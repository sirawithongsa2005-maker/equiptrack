<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[\AllowDynamicProperties]
class Type_model extends CI_Model
{
    public function list_type(){ return $this->db->order_by('t_name','ASC')->get('tbl_devices_type')->result(); }
    public function name_exists($name,$except_id=NULL){ $this->db->where('t_name',trim((string)$name)); if($except_id!==NULL) $this->db->where('t_id !=',(int)$except_id); return $this->db->count_all_results('tbl_devices_type')>0; }
    public function addtype_db(){ return $this->db->insert('tbl_devices_type',array('t_name'=>trim((string)$this->input->post('t_name')))); }
    public function read($t_id){ $q=$this->db->get_where('tbl_devices_type',array('t_id'=>(int)$t_id),1); return $q->num_rows()?$q->row():FALSE; }
    public function edittype_db(){ return $this->db->where('t_id',(int)$this->input->post('t_id'))->update('tbl_devices_type',array('t_name'=>trim((string)$this->input->post('t_name')))); }
    public function deltype_db($t_id){
        $t_id=(int)$t_id;
        if($this->db->where('ref_t_id',$t_id)->count_all_results('tbl_devices')>0 || $this->db->where('ref_t_id',$t_id)->count_all_results('tbl_devices_service')>0) return FALSE;
        return $this->db->delete('tbl_devices_type',array('t_id'=>$t_id));
    }
}
