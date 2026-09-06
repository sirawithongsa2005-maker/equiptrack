<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[\AllowDynamicProperties]
class Services_model extends CI_Model
{
    public function list_lend()
    {
        return $this->db->select('s.*,m.m_fname,m.m_name,m.m_lname,m.m_email,d.d_name,d.d_id')
            ->from('tbl_devices_service s')
            ->join('tbl_member m', 's.ref_m_id=m.m_id')
            ->join('tbl_devices d', 's.ref_d_id=d.d_id')
            ->where_in('s.ser_status', array('approved','borrowed'))
            ->order_by('s.ser_id', 'DESC')->get()->result();
    }

    public function list_return()
    {
        return $this->db->select('s.*,m.m_fname,m.m_name,m.m_lname,m.m_email,d.d_name,d.d_id')
            ->from('tbl_devices_service s')
            ->join('tbl_member m', 's.ref_m_id=m.m_id')
            ->join('tbl_devices d', 's.ref_d_id=d.d_id')
            ->where('s.ser_status', 'returned')
            ->order_by('s.ser_date_return', 'DESC')->order_by('s.ser_id', 'DESC')->get()->result();
    }

    public function list_damaged()
    {
        return $this->db->select('d.*,t.t_name,s.s_name')
            ->from('tbl_devices d')->join('tbl_devices_type t','d.ref_t_id=t.t_id')
            ->join('tbl_devices_status s','d.ref_s_id=s.s_id')
            ->where('d.ref_s_id',3)->order_by('d.no','DESC')->get()->result();
    }

    public function list_lend_member($m_id)
    {
        return $this->db->select('s.*,m.m_fname,m.m_name,m.m_lname,m.m_email,d.d_name,d.d_id')
            ->from('tbl_devices_service s')->join('tbl_member m','s.ref_m_id=m.m_id')
            ->join('tbl_devices d','s.ref_d_id=d.d_id')->where('s.ref_m_id',(int)$m_id)
            ->order_by('s.ser_id','DESC')->get()->result();
    }

    public function add_lend1_db()
    {
        return $this->create_direct_loan(
            array((string)$this->input->post('device')),
            (int)$this->input->post('ref_m_id'),
            (string)$this->input->post('ser_reason'),
            (int)$this->session->userdata('m_id'),
            (string)$this->session->userdata('m_name')
        );
    }

    public function add_lend4_db()
    {
        return $this->create_direct_loan(
            (array)$this->input->post('device'),
            (int)$this->input->post('ref_m_id'),
            (string)$this->input->post('ser_reason'),
            (int)$this->session->userdata('m_id'),
            (string)$this->session->userdata('m_name')
        );
    }

    private function create_direct_loan(array $rawDeviceIds, $memberId, $reason, $staffId, $staffName)
    {
        $deviceIds = $this->normalize_device_ids($rawDeviceIds);
        $memberId = (int)$memberId;
        $staffId = (int)$staffId;
        $reason = trim((string)$reason);
        if (!$deviceIds || $memberId < 1 || $staffId < 1 || $reason === '') return FALSE;

        if ($this->db->where('m_id',$memberId)->where_not_in('ref_pid',array(1,3))->count_all_results('tbl_member') !== 1) {
            return FALSE;
        }

        // Lock devices in a stable order to reduce deadlocks when multiple users submit together.
        sort($deviceIds, SORT_STRING);
        $this->db->trans_begin();
        foreach ($deviceIds as $deviceId) {
            $device = $this->lock_available_device($deviceId);
            if (!$device) { $this->db->trans_rollback(); return FALSE; }

            // Do not bypass a student's pending request with a direct staff loan.
            $pending = $this->db->where('ref_d_id',$device->d_id)->where('ser_status','pending')
                ->count_all_results('tbl_devices_service');
            if ($pending > 0) { $this->db->trans_rollback(); return FALSE; }

            $ok = $this->db->where('d_id',$device->d_id)->where('ref_s_id',1)
                ->update('tbl_devices',array('ref_s_id'=>2));
            if (!$ok || $this->db->affected_rows() !== 1) { $this->db->trans_rollback(); return FALSE; }

            $ok = $this->db->insert('tbl_devices_service', array(
                'ref_t_id'=>(int)$device->ref_t_id,
                'ref_d_id'=>$device->d_id,
                'ref_m_id'=>$memberId,
                'ser_reason'=>$reason,
                'ser_status'=>'borrowed',
                'ser_request_date'=>date('Y-m-d H:i:s'),
                'ser_approved_at'=>date('Y-m-d H:i:s'),
                'ser_date_lend'=>date('Y-m-d'),
                'ser_staff_id_lend'=>$staffId,
                'ser_staff_name_lend'=>trim($staffName)
            ));
            if (!$ok || $this->db->affected_rows() !== 1) { $this->db->trans_rollback(); return FALSE; }
        }

        if ($this->db->trans_status() === FALSE) { $this->db->trans_rollback(); return FALSE; }
        $this->db->trans_commit();
        return TRUE;
    }

    public function query_edit_lend($ser_id)
    {
        $q=$this->db->select('s.*,m.m_fname,m.m_name,m.m_lname,d.*,t.t_name')
            ->from('tbl_devices_service s')->join('tbl_member m','s.ref_m_id=m.m_id')
            ->join('tbl_devices d','s.ref_d_id=d.d_id')->join('tbl_devices_type t','s.ref_t_id=t.t_id')
            ->where('s.ser_id',(int)$ser_id)->get();
        return $q->num_rows() ? $q->row() : FALSE;
    }

    public function edit_lend_db()
    {
        $serId=(int)$this->input->post('ser_id');
        if($serId<1) return FALSE;
        return $this->db->where('ser_id',$serId)
            ->where_in('ser_status',array('approved','borrowed'))
            ->update('tbl_devices_service',array(
                'ser_reason'=>trim((string)$this->input->post('ser_reason'))
            ));
    }

    public function return_lend_db()
    {
        $serId=(int)$this->input->post('ser_id');
        $returnStatus=(int)$this->input->post('ref_s_id');
        if($serId<1 || !in_array($returnStatus,array(1,3),TRUE)) return FALSE;

        $this->db->trans_begin();
        $loan=$this->db->query(
            "SELECT * FROM tbl_devices_service WHERE ser_id=? FOR UPDATE",
            array($serId)
        )->row();
        if(!$loan || !in_array($loan->ser_status,array('borrowed','approved'),TRUE)) {
            $this->db->trans_rollback();
            return FALSE;
        }

        $device=$this->db->query(
            "SELECT d_id,ref_s_id FROM tbl_devices WHERE d_id=? FOR UPDATE",
            array($loan->ref_d_id)
        )->row();
        if(!$device){ $this->db->trans_rollback(); return FALSE; }

        $ok=$this->db->where('ser_id',$serId)->where_in('ser_status',array('borrowed','approved'))
            ->update('tbl_devices_service',array(
                'ser_date_return'=>date('Y-m-d'),
                'ser_staff_id_return'=>(int)$this->session->userdata('m_id'),
                'ser_staff_name_return'=>trim((string)$this->session->userdata('m_name')),
                'ser_status'=>'returned'
            ));
        if(!$ok || $this->db->affected_rows()!==1){ $this->db->trans_rollback(); return FALSE; }

        $ok=$this->db->where('d_id',$loan->ref_d_id)->update('tbl_devices',array('ref_s_id'=>$returnStatus));
        if(!$ok || $this->db->trans_status()===FALSE){ $this->db->trans_rollback(); return FALSE; }

        $this->db->trans_commit();
        return TRUE;
    }

    public function list_pending_requests()
    {
        return $this->db->select('s.*,m.m_fname,m.m_name,m.m_lname,m.m_email,d.d_name,d.d_id')
            ->from('tbl_devices_service s')->join('tbl_member m','s.ref_m_id=m.m_id')
            ->join('tbl_devices d','s.ref_d_id=d.d_id')->where('s.ser_status','pending')
            ->order_by('s.ser_request_date','ASC')->order_by('s.ser_id','ASC')->get()->result();
    }

    public function list_student_requests($m_id)
    {
        return $this->db->select('s.*,d.d_name,d.d_id')->from('tbl_devices_service s')
            ->join('tbl_devices d','s.ref_d_id=d.d_id')->where('s.ref_m_id',(int)$m_id)
            ->order_by('s.ser_id','DESC')->get()->result();
    }

    public function request_devices($memberId, array $rawDevices, $reason)
    {
        $memberId=(int)$memberId;
        $deviceIds=$this->normalize_device_ids($rawDevices);
        $reason=trim((string)$reason);
        if(!$deviceIds || $memberId<1 || $reason==='') return FALSE;

        if ($this->db->where('m_id',$memberId)->where_not_in('ref_pid',array(1,3))->count_all_results('tbl_member') !== 1) {
            return FALSE;
        }

        sort($deviceIds, SORT_STRING);
        $this->db->trans_begin();
        foreach($deviceIds as $deviceId){
            // Lock the device row so two simultaneous requests cannot both pass the pending check.
            $device=$this->lock_available_device($deviceId);
            if(!$device){ $this->db->trans_rollback(); return FALSE; }

            $pending=$this->db->where('ref_d_id',$device->d_id)->where('ser_status','pending')
                ->count_all_results('tbl_devices_service');
            if($pending>0){ $this->db->trans_rollback(); return FALSE; }

            $ok=$this->db->insert('tbl_devices_service',array(
                'ref_t_id'=>(int)$device->ref_t_id,
                'ref_d_id'=>$device->d_id,
                'ref_m_id'=>$memberId,
                'ser_reason'=>$reason,
                'ser_status'=>'pending',
                'ser_request_date'=>date('Y-m-d H:i:s'),
                'ser_date_lend'=>NULL,
                'ser_staff_id_lend'=>NULL,
                'ser_staff_name_lend'=>''
            ));
            if(!$ok || $this->db->affected_rows()!==1){ $this->db->trans_rollback(); return FALSE; }
        }

        if($this->db->trans_status()===FALSE){ $this->db->trans_rollback(); return FALSE; }
        $this->db->trans_commit();
        return TRUE;
    }

    public function approve_request($serId,$staffId,$staffName)
    {
        $serId=(int)$serId; $staffId=(int)$staffId;
        if($serId<1 || $staffId<1) return FALSE;

        $this->db->trans_begin();
        $req=$this->db->query(
            "SELECT * FROM tbl_devices_service WHERE ser_id=? FOR UPDATE",
            array($serId)
        )->row();
        if(!$req || $req->ser_status!=='pending'){ $this->db->trans_rollback(); return FALSE; }

        $device=$this->lock_available_device($req->ref_d_id);
        if(!$device){ $this->db->trans_rollback(); return FALSE; }

        $ok=$this->db->where('ser_id',$serId)->where('ser_status','pending')->update('tbl_devices_service',array(
            'ser_status'=>'approved',
            'ser_date_lend'=>date('Y-m-d'),
            'ser_approved_at'=>date('Y-m-d H:i:s'),
            'ser_staff_id_lend'=>$staffId,
            'ser_staff_name_lend'=>trim((string)$staffName)
        ));
        if(!$ok || $this->db->affected_rows()!==1){ $this->db->trans_rollback(); return FALSE; }

        $ok=$this->db->where('d_id',$req->ref_d_id)->where('ref_s_id',1)->update('tbl_devices',array('ref_s_id'=>2));
        if(!$ok || $this->db->affected_rows()!==1 || $this->db->trans_status()===FALSE){ $this->db->trans_rollback(); return FALSE; }

        $this->db->trans_commit();
        return TRUE;
    }

    public function reject_request($serId,$staffId,$staffName)
    {
        $serId=(int)$serId; $staffId=(int)$staffId;
        if($serId<1 || $staffId<1) return FALSE;

        $this->db->trans_begin();
        $req=$this->db->query("SELECT ser_id,ser_status FROM tbl_devices_service WHERE ser_id=? FOR UPDATE",array($serId))->row();
        if(!$req || $req->ser_status!=='pending'){ $this->db->trans_rollback(); return FALSE; }

        $ok=$this->db->where('ser_id',$serId)->where('ser_status','pending')->update('tbl_devices_service',array(
            'ser_status'=>'rejected',
            'ser_rejected_at'=>date('Y-m-d H:i:s'),
            'ser_staff_id_lend'=>$staffId,
            'ser_staff_name_lend'=>trim((string)$staffName)
        ));
        if(!$ok || $this->db->affected_rows()!==1 || $this->db->trans_status()===FALSE){ $this->db->trans_rollback(); return FALSE; }
        $this->db->trans_commit();
        return TRUE;
    }

    public function cancel_student_request($serId,$memberId)
    {
        $serId=(int)$serId; $memberId=(int)$memberId;
        if($serId<1 || $memberId<1) return FALSE;

        $this->db->trans_begin();
        $req=$this->db->query(
            "SELECT ser_id,ref_m_id,ser_status FROM tbl_devices_service WHERE ser_id=? FOR UPDATE",
            array($serId)
        )->row();
        if(!$req || (int)$req->ref_m_id!==$memberId || $req->ser_status!=='pending'){
            $this->db->trans_rollback();
            return FALSE;
        }
        $ok=$this->db->where('ser_id',$serId)->where('ref_m_id',$memberId)->where('ser_status','pending')
            ->update('tbl_devices_service',array('ser_status'=>'cancelled'));
        if(!$ok || $this->db->affected_rows()!==1 || $this->db->trans_status()===FALSE){ $this->db->trans_rollback(); return FALSE; }
        $this->db->trans_commit();
        return TRUE;
    }

    private function lock_available_device($deviceId)
    {
        return $this->db->query(
            "SELECT no,ref_t_id,ref_s_id,d_id,d_name FROM tbl_devices WHERE d_id=? AND ref_s_id=1 FOR UPDATE",
            array((string)$deviceId)
        )->row();
    }

    private function normalize_device_ids(array $rawValues)
    {
        $ids=array();
        foreach($rawValues as $raw){
            $id=$this->normalize_device_id($raw);
            if($id!=='') $ids[$id]=TRUE;
        }
        return array_keys($ids);
    }

    private function normalize_device_id($raw)
    {
        $value=trim((string)$raw);
        if($value==='') return '';

        // Current forms submit only d_id. Keep compatibility with older "d_idxTYPE" values.
        if($this->db->where('d_id',$value)->count_all_results('tbl_devices')===1){
            return $value;
        }
        if(preg_match('/^(.*)x([0-9]+)$/',$value,$m)){
            $legacy=trim($m[1]);
            if($legacy!=='' && $this->db->where('d_id',$legacy)->count_all_results('tbl_devices')===1){
                return $legacy;
            }
        }
        return '';
    }
}
