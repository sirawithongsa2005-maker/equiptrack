<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[\AllowDynamicProperties]
class Position_model extends CI_Model
{
    public function list_position(){ return $this->db->order_by('pid','ASC')->get('tbl_position')->result(); }
    public function name_exists($name,$except_id=NULL){ $this->db->where('pname',trim((string)$name)); if($except_id!==NULL) $this->db->where('pid !=',(int)$except_id); return $this->db->count_all_results('tbl_position')>0; }
    public function addposition(){ return $this->db->insert('tbl_position',array('pname'=>trim((string)$this->input->post('pname')))); }
    public function read($pid){ $q=$this->db->get_where('tbl_position',array('pid'=>(int)$pid),1); return $q->num_rows()?$q->row():FALSE; }
    public function editposition(){ $pid=(int)$this->input->post('pid'); if(in_array($pid,array(1,3,4),TRUE)) return FALSE; return $this->db->where('pid',$pid)->update('tbl_position',array('pname'=>trim((string)$this->input->post('pname')))); }
    public function deldata($pid){ $pid=(int)$pid; if(in_array($pid,array(1,3,4),TRUE)) return FALSE; if($this->db->where('ref_pid',$pid)->count_all_results('tbl_member')>0) return FALSE; return $this->db->delete('tbl_position',array('pid'=>$pid)); }
}
