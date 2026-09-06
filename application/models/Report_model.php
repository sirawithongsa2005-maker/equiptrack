<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[\AllowDynamicProperties]
class Report_model extends CI_Model
{
    private function service_base()
    {
        return $this->db->select('s.*,m.m_fname,m.m_name,m.m_lname,m.m_email,d.d_name,d.d_id,t.t_name')
            ->from('tbl_devices_service s')
            ->join('tbl_member m','s.ref_m_id=m.m_id')
            ->join('tbl_devices d','s.ref_d_id=d.d_id')
            ->join('tbl_devices_type t','s.ref_t_id=t.t_id','left');
    }

    public function listall()
    {
        return $this->service_base()->order_by('s.ser_id','DESC')->get()->result();
    }

    public function list_bymember()
    {
        return $this->db->select('m.m_id,m.m_fname,m.m_name,m.m_lname,m.m_email,COUNT(s.ser_id) AS total')
            ->from('tbl_member m')->join('tbl_devices_service s','m.m_id=s.ref_m_id','left')
            ->where_not_in('m.ref_pid',array(1,3))->group_by(array('m.m_id','m.m_fname','m.m_name','m.m_lname','m.m_email'))
            ->order_by('m.m_name','ASC')->get()->result();
    }

    public function list_viewbymember($m_id)
    {
        return $this->service_base()->where('s.ref_m_id',(int)$m_id)->order_by('s.ser_id','DESC')->get()->result();
    }

    public function list_byposition()
    {
        return $this->db->select('p.pid,p.pname,COUNT(s.ser_id) AS total')->from('tbl_position p')
            ->join('tbl_member m','p.pid=m.ref_pid','left')->join('tbl_devices_service s','m.m_id=s.ref_m_id','left')
            ->group_by(array('p.pid','p.pname'))->order_by('p.pid','ASC')->get()->result();
    }

    public function list_viewbyposition($pid)
    {
        return $this->db->select('s.*,m.m_fname,m.m_name,m.m_lname,m.m_email,d.d_name,d.d_id,p.pname')
            ->from('tbl_devices_service s')->join('tbl_member m','s.ref_m_id=m.m_id')
            ->join('tbl_devices d','s.ref_d_id=d.d_id')->join('tbl_position p','m.ref_pid=p.pid')
            ->where('m.ref_pid',(int)$pid)->order_by('s.ser_id','DESC')->get()->result();
    }

    public function list_bytype()
    {
        return $this->db->select('t.t_id,t.t_name,COUNT(s.ser_id) AS total')->from('tbl_devices_type t')
            ->join('tbl_devices_service s','t.t_id=s.ref_t_id','left')->group_by(array('t.t_id','t.t_name'))
            ->order_by('t.t_name','ASC')->get()->result();
    }

    public function list_viewbytype($t_id)
    {
        return $this->db->select('s.*,m.m_fname,m.m_name,m.m_lname,m.m_email,d.d_name,d.d_id,p.pname')
            ->from('tbl_devices_service s')->join('tbl_member m','s.ref_m_id=m.m_id')
            ->join('tbl_devices d','s.ref_d_id=d.d_id')->join('tbl_position p','m.ref_pid=p.pid')
            ->where('s.ref_t_id',(int)$t_id)->order_by('s.ser_id','DESC')->get()->result();
    }

    public function listbydate($ds=NULL,$de=NULL)
    {
        $ds=$ds ?: $this->input->post('ds'); $de=$de ?: $this->input->post('de');
        return $this->service_base()->where('s.ser_datesave >=',$ds.' 00:00:00')->where('s.ser_datesave <=',$de.' 23:59:59')
            ->order_by('s.ser_datesave','DESC')->get()->result();
    }

    public function list_byday()
    {
        return $this->db->select("DATE(ser_datesave) AS raw_date, DATE_FORMAT(ser_datesave, '%d-%m-%Y') AS datesave, COUNT(ser_id) AS total",FALSE)
            ->from('tbl_devices_service')->group_by('DATE(ser_datesave)')->order_by('raw_date','DESC')->get()->result();
    }

    public function list_bymonth()
    {
        return $this->db->select("DATE_FORMAT(ser_datesave, '%Y-%m') AS raw_date, DATE_FORMAT(ser_datesave, '%m-%Y') AS datesave, COUNT(ser_id) AS total",FALSE)
            ->from('tbl_devices_service')->group_by("DATE_FORMAT(ser_datesave, '%Y-%m')")->order_by('raw_date','DESC')->get()->result();
    }

    public function list_byyear()
    {
        return $this->db->select("YEAR(ser_datesave) AS raw_date, DATE_FORMAT(ser_datesave, '%Y') AS datesave, COUNT(ser_id) AS total",FALSE)
            ->from('tbl_devices_service')->group_by('YEAR(ser_datesave)')->order_by('raw_date','DESC')->get()->result();
    }
}
