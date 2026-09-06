<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[\AllowDynamicProperties]
class Devices_model extends CI_Model
{
    public function list_devices()
    {
        return $this->db->select('d.*,t.t_name,s.s_name')
            ->from('tbl_devices d')
            ->join('tbl_devices_type t', 'd.ref_t_id=t.t_id')
            ->join('tbl_devices_status s', 'd.ref_s_id=s.s_id')
            ->order_by('d.no', 'DESC')->get()->result();
    }

    public function list_devices_free()
    {
        return $this->db->select('d.*,t.t_name,s.s_name')
            ->from('tbl_devices d')
            ->join('tbl_devices_type t', 'd.ref_t_id=t.t_id')
            ->join('tbl_devices_status s', 'd.ref_s_id=s.s_id')
            ->where('d.ref_s_id', 1)
            ->where("NOT EXISTS (SELECT 1 FROM tbl_devices_service ps WHERE ps.ref_d_id=d.d_id AND ps.ser_status='pending')", NULL, FALSE)
            ->order_by('d.d_name', 'ASC')->get()->result();
    }

    public function has_active_loan_by_no($no)
    {
        $device=$this->db->select('d_id')->get_where('tbl_devices',array('no'=>(int)$no),1)->row();
        if(!$device) return FALSE;
        return $this->db->where('ref_d_id',$device->d_id)->where_in('ser_status',array('approved','borrowed'))->count_all_results('tbl_devices_service')>0;
    }

    public function has_pending_request_by_no($no)
    {
        $device=$this->db->select('d_id')->get_where('tbl_devices',array('no'=>(int)$no),1)->row();
        if(!$device) return FALSE;
        return $this->db->where('ref_d_id',$device->d_id)->where('ser_status','pending')->count_all_results('tbl_devices_service')>0;
    }

    public function device_code_exists($code, $except_no = NULL)
    {
        $this->db->where('d_id', trim((string)$code));
        if ($except_no !== NULL) $this->db->where('no !=', (int)$except_no);
        return $this->db->count_all_results('tbl_devices') > 0;
    }

    public function add_devices_db($filename = '')
    {
        $filename = basename(trim((string)$filename));
        return $this->db->insert('tbl_devices', array(
            'ref_t_id' => (int)$this->input->post('ref_t_id'),
            'ref_s_id' => (int)$this->input->post('ref_s_id'),
            'd_id' => trim((string)$this->input->post('d_id')),
            'd_name' => trim((string)$this->input->post('d_name')),
            'd_detail' => trim((string)$this->input->post('d_detail')),
            'd_remark' => trim((string)$this->input->post('d_remark')),
            'ref_m_id' => ((int)$this->input->post('ref_m_id') ?: NULL),
            'd_img' => $filename
        ));
    }

    public function read($no)
    {
        $query = $this->db->select('d.*,t.t_name,s.s_name')
            ->from('tbl_devices d')
            ->join('tbl_devices_type t', 'd.ref_t_id=t.t_id')
            ->join('tbl_devices_status s', 'd.ref_s_id=s.s_id')
            ->where('d.no', (int)$no)->get();
        return $query->num_rows() ? $query->row() : FALSE;
    }

    public function edit_devices_db_img($filename)
    {
        $filename = basename(trim((string)$filename));
        if ($filename === '') return FALSE;
        return $this->update_device(TRUE, $filename);
    }

    public function edit_devices_db()
    {
        return $this->update_device(FALSE, '');
    }

    private function update_device($with_image, $filename = '')
    {
        $data = array(
            'ref_t_id' => (int)$this->input->post('ref_t_id'),
            'ref_s_id' => (int)$this->input->post('ref_s_id'),
            'd_id' => trim((string)$this->input->post('d_id')),
            'd_name' => trim((string)$this->input->post('d_name')),
            'd_detail' => trim((string)$this->input->post('d_detail')),
            'd_remark' => trim((string)$this->input->post('d_remark'))
        );
        if ($with_image) $data['d_img'] = basename(trim((string)$filename));
        return $this->db->where('no', (int)$this->input->post('no'))->update('tbl_devices', $data);
    }

    public function del_devices_db($no)
    {
        $device = $this->db->get_where('tbl_devices', array('no' => (int)$no), 1)->row();
        if (!$device) return FALSE;
        if ($this->db->where('ref_d_id', $device->d_id)->count_all_results('tbl_devices_service') > 0) {
            return FALSE;
        }
        return $this->db->delete('tbl_devices', array('no' => (int)$no));
    }
}
