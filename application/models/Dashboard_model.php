<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[\AllowDynamicProperties]
class Dashboard_model extends CI_Model
{
    public function stats()
    {
        return array(
            'devices'=>$this->db->count_all('tbl_devices'),
            'available'=>$this->db->where('ref_s_id',1)->count_all_results('tbl_devices'),
            'borrowed'=>$this->db->where('ref_s_id',2)->count_all_results('tbl_devices'),
            'damaged'=>$this->db->where('ref_s_id',3)->count_all_results('tbl_devices'),
            'members'=>$this->db->where_not_in('ref_pid',array(1,3))->count_all_results('tbl_member'),
            'pending'=>$this->db->where('ser_status','pending')->count_all_results('tbl_devices_service'),
            'active_loans'=>$this->db->where_in('ser_status',array('approved','borrowed'))->count_all_results('tbl_devices_service')
        );
    }

    public function recent_loans($limit=8)
    {
        return $this->db->select('s.ser_id,s.ser_status,s.ser_date_lend,s.ser_date_return,s.ser_reason,m.m_name,m.m_lname,d.d_id,d.d_name')
            ->from('tbl_devices_service s')->join('tbl_member m','m.m_id=s.ref_m_id','left')
            ->join('tbl_devices d','d.d_id=s.ref_d_id','left')->where_in('s.ser_status',array('approved','borrowed','returned'))
            ->order_by('s.ser_id','DESC')->limit((int)$limit)->get()->result();
    }
}
