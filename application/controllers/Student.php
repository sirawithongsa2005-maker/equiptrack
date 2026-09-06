<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[\AllowDynamicProperties]
class Student extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $level=(int)$this->session->userdata('m_level');
        if(!$this->session->userdata('m_id') || $level < 1 || in_array($level,array(1,3),TRUE)) redirect('user');
        $this->load->model('member_model');
        $this->load->model('services_model');
        $this->load->model('devices_model');
    }

    public function index()
    {
        $this->render('student/requests',array('query'=>$this->services_model->list_student_requests((int)$this->session->userdata('m_id'))));
    }

    public function devices()
    {
        $this->render('student/devices',array('query'=>$this->devices_model->list_devices_free()));
    }

    public function request_borrow()
    {
        $this->form_validation->set_rules('device[]','อุปกรณ์','required');
        $this->form_validation->set_rules('ser_reason','วัตถุประสงค์การยืม','trim|required|min_length[3]|max_length[255]');
        if($this->form_validation->run()===FALSE) return $this->devices();
        if($this->services_model->request_devices((int)$this->session->userdata('m_id'),(array)$this->input->post('device'),$this->input->post('ser_reason',TRUE))){
            $this->session->set_flashdata('save_success',TRUE);
        }else{
            $this->session->set_flashdata('message','ไม่สามารถส่งคำขอยืมได้ อุปกรณ์ที่เลือกอาจมีคำขอรออนุมัติอยู่แล้ว');
        }
        redirect('student');
    }

    public function cancel_request($ser_id)
    {
        et_require_post();
        if($this->services_model->cancel_student_request((int)$ser_id,(int)$this->session->userdata('m_id'))) $this->session->set_flashdata('save_success',TRUE);
        else $this->session->set_flashdata('message','ไม่สามารถยกเลิกคำขอนี้ได้');
        redirect('student');
    }

    public function profile()
    {
        $data['rsedit']=$this->member_model->read((int)$this->session->userdata('m_id')); if(!$data['rsedit']) show_404();
        $this->render('student/member_form_edit',$data);
    }

    public function edit_profile()
    {
        $this->form_validation->set_rules('m_fname','คำนำหน้า','trim|required|max_length[30]');
        $this->form_validation->set_rules('m_name','ชื่อ','trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('m_lname','นามสกุล','trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('m_email','ระดับชั้นและรหัสนักศึกษา','trim|required|max_length[120]');
        $this->form_validation->set_rules('m_phone','เบอร์โทรศัพท์','trim|required|min_length[9]|max_length[20]');
        if($this->form_validation->run()===FALSE) return $this->profile();
        $_POST['m_id']=(int)$this->session->userdata('m_id');
        if(!empty($_FILES['m_img']['name'])){
            if(!$this->upload_profile_image()){ redirect('student/profile'); return; }
            $ok=$this->member_model->editmember_img();
            if($ok) $this->session->set_userdata('m_img',$this->upload->file_name);
        }else{
            $ok=$this->member_model->editstaff();
        }
        if($ok){
            $member=$this->member_model->read((int)$this->session->userdata('m_id'));
            if($member) $this->session->set_userdata('m_name',trim($member->m_fname.' '.$member->m_name.' '.$member->m_lname));
            $this->session->set_flashdata('save_success',TRUE);
        }else $this->session->set_flashdata('message','ไม่สามารถแก้ไขข้อมูลส่วนตัวได้');
        redirect('student/profile');
    }

    public function pwd()
    {
        $data['rsedit']=$this->member_model->read((int)$this->session->userdata('m_id')); if(!$data['rsedit']) show_404();
        $this->render('student/member_form_pwd',$data);
    }

    public function editpwd()
    {
        $this->form_validation->set_rules('m_password','รหัสผ่าน','trim|required|min_length[4]|max_length[255]');
        $this->form_validation->set_rules('m_password2','ยืนยันรหัสผ่าน','trim|required|matches[m_password]');
        if($this->form_validation->run()===FALSE) return $this->pwd();
        $_POST['m_id']=(int)$this->session->userdata('m_id');
        if($this->member_model->editstaffpwd()) $this->session->set_flashdata('save_success',TRUE);
        else $this->session->set_flashdata('message','ไม่สามารถเปลี่ยนรหัสผ่านได้');
        redirect('student');
    }

    private function upload_profile_image()
    {
        $path=et_writable_media_dir('uploads');
        if($path===FALSE){ $this->session->set_flashdata('message','โฟลเดอร์ uploads ไม่มีสิทธิ์เขียนไฟล์ กรุณาตรวจสอบสิทธิ์ของโฟลเดอร์โปรเจกต์'); return FALSE; }
        $this->load->library('upload');
        $this->upload->initialize(array('encrypt_name'=>TRUE,'upload_path'=>$path,'allowed_types'=>'gif|jpg|png|jpeg','max_size'=>5120,'remove_spaces'=>TRUE),TRUE);
        if(!$this->upload->do_upload('m_img')){ $this->session->set_flashdata('message',$this->upload->display_errors('','')); return FALSE; }
        $this->load->library('image_lib');
        $uploaded=$path.$this->upload->file_name;
        $this->image_lib->initialize(array('source_image'=>$uploaded,'new_image'=>$path,'maintain_ratio'=>TRUE,'width'=>500,'height'=>500));
        if(!$this->image_lib->resize()){ $error=$this->image_lib->display_errors('',''); $this->image_lib->clear(); @unlink($uploaded); $this->session->set_flashdata('message','ปรับขนาดรูปไม่สำเร็จ: '.$error); return FALSE; }
        $this->image_lib->clear(); return TRUE;
    }

    private function render($view,$data=array())
    {
        $this->load->view('template/backheader_student');
        $this->load->view($view,$data);
        $this->load->view('template/backfooter');
    }
}
