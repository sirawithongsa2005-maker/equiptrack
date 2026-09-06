<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[\AllowDynamicProperties]
class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Member_model');
    }

    public function index()
    {
        if ($this->session->userdata('m_id')) {
            $this->redirect_by_role((int)$this->session->userdata('m_level'));
            return;
        }
        $this->load->view('template/backheader_login');
        $this->load->view('login_form');
    }

    // Legacy endpoint kept so old bookmarks/forms still work.
    public function check()
    {
        return $this->check2();
    }

    public function check2()
    {
        $this->form_validation->set_rules('m_username', 'ชื่อผู้ใช้', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('m_password', 'รหัสผ่าน', 'required|max_length[255]');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('msg', trim(strip_tags(validation_errors(' ', ' '))));
            redirect('user');
            return;
        }

        $username = trim((string)$this->input->post('m_username', TRUE));
        $password = (string)$this->input->post('m_password');
        $result = $this->Member_model->fetch_user_login_plain($username, $password);

        if (!$result) {
            $this->session->unset_userdata(array('m_id','m_level','m_name','m_img'));
            $this->session->set_flashdata('msg', 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง');
            redirect('user');
            return;
        }

        $this->session->sess_regenerate(TRUE);
        $this->session->set_userdata(array(
            'm_id'    => (int)$result->m_id,
            'm_level' => (int)$result->ref_pid,
            'm_name'  => trim($result->m_fname.' '.$result->m_name.' '.$result->m_lname),
            'm_img'   => (string)$result->m_img
        ));
        $this->redirect_by_role((int)$result->ref_pid);
    }

    private function redirect_by_role($level)
    {
        if ($level === 1) { redirect('admin'); return; }
        if ($level === 3) { redirect('staff'); return; }
        if ($level > 0) { redirect('student'); return; }
        $this->session->sess_destroy();
        $this->session->set_flashdata('msg', 'บัญชีนี้ยังไม่ได้กำหนดสิทธิ์ใช้งาน');
        redirect('user');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('user');
    }
}
