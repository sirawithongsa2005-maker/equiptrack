<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[\AllowDynamicProperties]
class Member_model extends CI_Model
{
    public function list_member()
    {
        return $this->db
            ->select('m.m_id,m.ref_pid,m.m_username,m.m_fname,m.m_name,m.m_lname,p.pname,m.m_img,m.m_email,m.m_phone,m.m_datesave')
            ->from('tbl_member m')
            ->join('tbl_position p', 'm.ref_pid=p.pid')
            ->order_by('m.m_id', 'ASC')
            ->get()->result();
    }

    public function username_exists($username, $except_id = NULL)
    {
        $this->db->where('m_username', trim((string)$username));
        if ($except_id !== NULL) {
            $this->db->where('m_id !=', (int)$except_id);
        }
        return $this->db->count_all_results('tbl_member') > 0;
    }

    public function addmember()
    {
        $filename = (isset($this->upload) && !empty($this->upload->file_name)) ? $this->upload->file_name : '';
        return $this->db->insert('tbl_member', array(
            'ref_pid'    => (int)$this->input->post('ref_pid'),
            'm_username' => trim((string)$this->input->post('m_username')),
            'm_password' => password_hash((string)$this->input->post('m_password'), PASSWORD_DEFAULT),
            'm_fname'    => trim((string)$this->input->post('m_fname')),
            'm_name'     => trim((string)$this->input->post('m_name')),
            'm_lname'    => trim((string)$this->input->post('m_lname')),
            'm_email'    => trim((string)$this->input->post('m_email')),
            'm_phone'    => trim((string)$this->input->post('m_phone')),
            'm_img'      => $filename
        ));
    }

    // Compatibility alias for old code.
    public function addmember2()
    {
        if ($this->username_exists($this->input->post('m_username'))) {
            return FALSE;
        }
        return $this->addmember();
    }

    public function read($m_id)
    {
        $query = $this->db
            ->select('m.*,p.pname')
            ->from('tbl_member m')
            ->join('tbl_position p', 'm.ref_pid=p.pid')
            ->where('m.m_id', (int)$m_id)
            ->get();
        return $query->num_rows() ? $query->row() : FALSE;
    }

    // Legacy lookup used by older borrowing forms. Accept member id or exact first name.
    public function read1($value)
    {
        $this->db->select('m.*,p.pname')->from('tbl_member m')->join('tbl_position p', 'm.ref_pid=p.pid');
        if (ctype_digit((string)$value)) {
            $this->db->where('m.m_id', (int)$value);
        } else {
            $this->db->where('m.m_name', trim((string)$value));
        }
        $query = $this->db->get();
        return $query->num_rows() ? $query->row() : FALSE;
    }

    public function find_borrower($term)
    {
        $term=trim((string)$term);
        if($term==='') return FALSE;

        $this->db->select('m.*,p.pname')->from('tbl_member m')->join('tbl_position p','m.ref_pid=p.pid')
            ->where_not_in('m.ref_pid',array(1,3));
        if(ctype_digit($term)){
            $this->db->where('m.m_id',(int)$term);
        }else{
            $this->db->group_start()
                ->where('m.m_username',$term)
                ->or_where('m.m_name',$term)
                ->or_where("TRIM(CONCAT(m.m_name,' ',m.m_lname)) = ".$this->db->escape($term), NULL, FALSE)
            ->group_end();
        }
        $q=$this->db->order_by('m.m_id','ASC')->limit(1)->get();
        return $q->num_rows() ? $q->row() : FALSE;
    }

    public function editmember()
    {
        return $this->update_profile_fields((int)$this->input->post('m_id'), TRUE, FALSE);
    }

    public function editmember_img()
    {
        return $this->update_profile_fields((int)$this->input->post('m_id'), TRUE, TRUE);
    }

    public function editmember_img_only()
    {
        if (!isset($this->upload) || empty($this->upload->file_name)) {
            return FALSE;
        }
        return $this->db->where('m_id', (int)$this->input->post('m_id'))
            ->update('tbl_member', array('m_img' => $this->upload->file_name));
    }

    private function update_profile_fields($m_id, $with_role, $with_image)
    {
        $data = array(
            'm_fname' => trim((string)$this->input->post('m_fname')),
            'm_name'  => trim((string)$this->input->post('m_name')),
            'm_lname' => trim((string)$this->input->post('m_lname')),
            'm_email' => trim((string)$this->input->post('m_email')),
            'm_phone' => trim((string)$this->input->post('m_phone'))
        );
        if ($with_role && $this->input->post('ref_pid') !== NULL && $this->input->post('ref_pid') !== '') {
            $data['ref_pid'] = (int)$this->input->post('ref_pid');
        }
        if ($with_image && isset($this->upload) && !empty($this->upload->file_name)) {
            $data['m_img'] = $this->upload->file_name;
        }
        return $this->db->where('m_id', (int)$m_id)->update('tbl_member', $data);
    }

    public function editmemberpwd()
    {
        return $this->update_password((int)$this->input->post('m_id'), (string)$this->input->post('m_password'));
    }

    public function editstaff()
    {
        return $this->update_profile_fields((int)$this->input->post('m_id'), FALSE, FALSE);
    }

    public function editstaffpwd()
    {
        return $this->update_password((int)$this->input->post('m_id'), (string)$this->input->post('m_password'));
    }

    // Compatibility methods retained for old links/forms.
    public function editboss() { return $this->editstaff(); }
    public function editemp() { return $this->editstaff(); }
    public function editbosspwd() { return $this->editstaffpwd(); }
    public function editemppwd() { return $this->editstaffpwd(); }

    private function update_password($m_id, $password)
    {
        if ($m_id < 1 || strlen($password) < 4) {
            return FALSE;
        }
        return $this->db->where('m_id', $m_id)->update('tbl_member', array(
            'm_password' => password_hash($password, PASSWORD_DEFAULT)
        ));
    }

    public function deldata($m_id)
    {
        $m_id = (int)$m_id;
        if ($m_id < 1) return FALSE;
        if ($this->db->where('ref_m_id', $m_id)->count_all_results('tbl_devices_service') > 0) {
            return FALSE;
        }
        return $this->db->delete('tbl_member', array('m_id' => $m_id));
    }

    public function fetch_user_login($m_username, $m_password)
    {
        return $this->fetch_user_login_plain($m_username, $m_password);
    }

    public function fetch_user_login_plain($username, $password)
    {
        $user = $this->db->get_where('tbl_member', array('m_username' => trim((string)$username)), 1)->row();
        if (!$user) return FALSE;

        $stored = (string)$user->m_password;
        $valid = FALSE;

        // Backward compatibility: old database versions stored SHA1.
        if (preg_match('/^[a-f0-9]{40}$/i', $stored)) {
            $valid = hash_equals(strtolower($stored), sha1((string)$password));
            if ($valid) {
                $this->db->where('m_id', (int)$user->m_id)->update('tbl_member', array(
                    'm_password' => password_hash((string)$password, PASSWORD_DEFAULT)
                ));
            }
        } else {
            $valid = password_verify((string)$password, $stored);
            if ($valid && password_needs_rehash($stored, PASSWORD_DEFAULT)) {
                $this->db->where('m_id', (int)$user->m_id)->update('tbl_member', array(
                    'm_password' => password_hash((string)$password, PASSWORD_DEFAULT)
                ));
            }
        }
        return $valid ? $user : FALSE;
    }
}
