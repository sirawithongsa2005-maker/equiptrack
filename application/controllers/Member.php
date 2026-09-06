<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[\AllowDynamicProperties]
class Member extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ((int)$this->session->userdata('m_level') !== 1) {
            redirect('user');
        }
        $this->load->model('member_model');
        $this->load->model('position_model');
    }

    public function index()
    {
        $data['query'] = $this->member_model->list_member();
        $this->render('admin/member', $data);
    }

    public function adding()
    {
        $data['rspo'] = $this->position_model->list_position();
        $this->render('admin/member_form_add', $data);
    }

    // Legacy endpoint retained for old forms.
    public function adddatax() { return $this->adddata(); }

    public function adddata()
    {
        $this->set_member_rules(TRUE);
        if ($this->form_validation->run() === FALSE) {
            return $this->adding();
        }

        if (!$this->position_model->read((int)$this->input->post('ref_pid'))) {
            $this->session->set_flashdata('message', 'ประเภทผู้ใช้งานไม่ถูกต้อง');
            redirect('member/adding');
            return;
        }

        if ($this->member_model->username_exists($this->input->post('m_username'))) {
            $this->session->set_flashdata('message', 'ชื่อผู้ใช้นี้มีอยู่แล้ว กรุณาใช้ชื่ออื่น');
            redirect('member/adding');
            return;
        }

        if (!empty($_FILES['m_img']['name']) && !$this->upload_member_image('m_img', 500)) {
            redirect('member/adding');
            return;
        }

        if ($this->member_model->addmember()) {
            $this->session->set_flashdata('save_success', TRUE);
        } else {
            $this->session->set_flashdata('message', 'ไม่สามารถเพิ่มผู้ใช้งานได้ กรุณาตรวจสอบข้อมูลอีกครั้ง');
        }
        redirect('member');
    }

    public function edit($m_id)
    {
        $data['rsedit'] = $this->member_model->read((int)$m_id);
        if (!$data['rsedit']) show_404();
        $data['rspo'] = $this->position_model->list_position();
        $this->render('admin/member_form_edit', $data);
    }

    public function edit_img($m_id)
    {
        $data['rsedit'] = $this->member_model->read((int)$m_id);
        if (!$data['rsedit']) show_404();
        $this->render('admin/member_form_edit_img', $data);
    }

    public function editdata_img()
    {
        $m_id = (int)$this->input->post('m_id');
        if (!$this->member_model->read($m_id)) show_404();
        if (empty($_FILES['m_img']['name'])) {
            $this->session->set_flashdata('message', 'กรุณาเลือกรูปภาพ');
            redirect('member/edit_img/'.$m_id);
            return;
        }
        if (!$this->upload_member_image('m_img', 500)) {
            redirect('member/edit_img/'.$m_id);
            return;
        }
        if ($this->member_model->editmember_img_only()) {
            $this->session->set_flashdata('save_success', TRUE);
        } else {
            $this->session->set_flashdata('message', 'ไม่สามารถเปลี่ยนรูปภาพได้');
        }
        redirect('member');
    }

    public function editdata()
    {
        $m_id = (int)$this->input->post('m_id');
        $this->set_member_rules(FALSE);
        if ($this->form_validation->run() === FALSE) {
            return $this->edit($m_id);
        }

        if (!$this->position_model->read((int)$this->input->post('ref_pid'))) {
            $this->session->set_flashdata('message', 'ประเภทผู้ใช้งานไม่ถูกต้อง');
            redirect('member/edit/'.$m_id);
            return;
        }
        if ($m_id === (int)$this->session->userdata('m_id') && (int)$this->input->post('ref_pid') !== (int)$this->session->userdata('m_level')) {
            $this->session->set_flashdata('message', 'ไม่สามารถเปลี่ยนสิทธิ์ของบัญชีที่กำลังเข้าสู่ระบบอยู่ได้');
            redirect('member/edit/'.$m_id);
            return;
        }

        if (!empty($_FILES['m_img']['name'])) {
            if (!$this->upload_member_image('m_img', 500)) {
                redirect('member/edit/'.$m_id);
                return;
            }
            $ok = $this->member_model->editmember_img();
        } else {
            $ok = $this->member_model->editmember();
        }

        if ($ok) $this->session->set_flashdata('save_success', TRUE);
        else $this->session->set_flashdata('message', 'ไม่สามารถแก้ไขข้อมูลผู้ใช้งานได้');
        redirect('member');
    }

    public function pwd($m_id)
    {
        $data['rsedit'] = $this->member_model->read((int)$m_id);
        if (!$data['rsedit']) show_404();
        $this->render('admin/member_form_pwd', $data);
    }

    public function editpwd()
    {
        $m_id = (int)$this->input->post('m_id');
        $this->form_validation->set_rules('m_password', 'รหัสผ่าน', 'trim|required|min_length[4]|max_length[255]');
        $this->form_validation->set_rules('m_password2', 'ยืนยันรหัสผ่าน', 'trim|required|matches[m_password]');
        if ($this->form_validation->run() === FALSE) return $this->pwd($m_id);

        if ($this->member_model->editmemberpwd()) $this->session->set_flashdata('save_success', TRUE);
        else $this->session->set_flashdata('message', 'ไม่สามารถเปลี่ยนรหัสผ่านได้');
        redirect('member');
    }

    public function del($m_id)
    {
        et_require_post();
        $m_id = (int)$m_id;
        if ($m_id === (int)$this->session->userdata('m_id')) {
            $this->session->set_flashdata('message', 'ไม่สามารถลบบัญชีที่กำลังเข้าสู่ระบบอยู่ได้');
        } elseif ($this->member_model->deldata($m_id)) {
            $this->session->set_flashdata('del_success', TRUE);
        } else {
            $this->session->set_flashdata('message', 'ไม่สามารถลบผู้ใช้งานนี้ได้ เนื่องจากมีประวัติการยืม–คืนที่ต้องเก็บไว้');
        }
        redirect('member');
    }

    private function set_member_rules($is_new)
    {
        $this->form_validation->set_rules('ref_pid', 'ประเภทผู้ใช้งาน', 'trim|required|integer');
        if ($is_new) {
            $this->form_validation->set_rules('m_username', 'ชื่อผู้ใช้', 'trim|required|min_length[4]|max_length[50]');
            $this->form_validation->set_rules('m_password', 'รหัสผ่าน', 'trim|required|min_length[4]|max_length[255]');
        }
        $this->form_validation->set_rules('m_fname', 'คำนำหน้า', 'trim|required|max_length[30]');
        $this->form_validation->set_rules('m_name', 'ชื่อ', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('m_lname', 'นามสกุล', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('m_email', 'ข้อมูลนักศึกษา/หน่วยงาน', 'trim|required|max_length[120]');
        $this->form_validation->set_rules('m_phone', 'เบอร์โทรศัพท์', 'trim|required|min_length[9]|max_length[20]');
    }

    private function upload_member_image($field, $size)
    {
        $path = et_writable_media_dir('uploads');
        if ($path === FALSE) {
            $this->session->set_flashdata('message', 'โฟลเดอร์ uploads ไม่มีสิทธิ์เขียนไฟล์ กรุณาตรวจสอบสิทธิ์ของโฟลเดอร์โปรเจกต์');
            return FALSE;
        }

        $config = array('encrypt_name'=>TRUE,'upload_path'=>$path,'allowed_types'=>'gif|jpg|png|jpeg','max_size'=>5120,'remove_spaces'=>TRUE);
        $this->load->library('upload');
        $this->upload->initialize($config, TRUE);
        if (!$this->upload->do_upload($field)) {
            $this->session->set_flashdata('message', $this->upload->display_errors('', ''));
            return FALSE;
        }

        $img = array('source_image'=>$path.$this->upload->file_name,'new_image'=>$path,'maintain_ratio'=>TRUE,'width'=>$size,'height'=>$size);
        $this->load->library('image_lib');
        $this->image_lib->initialize($img);
        if(!$this->image_lib->resize()){ $error=$this->image_lib->display_errors('',''); $uploaded=$path.$this->upload->file_name; $this->image_lib->clear(); @unlink($uploaded); $this->session->set_flashdata('message','ปรับขนาดรูปไม่สำเร็จ: '.$error); return FALSE; }
        $this->image_lib->clear();
        return TRUE;
    }

    private function render($view, $data=array())
    {
        $this->load->view('template/backheader');
        $this->load->view($view, $data);
        $this->load->view('template/backfooter');
    }
}
