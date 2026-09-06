<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[\AllowDynamicProperties]
class Staff extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ((int)$this->session->userdata('m_level') !== 3) redirect('user');
        $this->load->model('member_model');
        $this->load->model('type_model');
        $this->load->model('devices_model');
        $this->load->model('status_model');
        $this->load->model('services_model');
    }

    public function index()
    {
        $this->render('staff/request_list', array('query'=>$this->services_model->list_pending_requests()));
    }

    public function loans()
    {
        $this->render('staff/list_lend', array('query'=>$this->services_model->list_lend()));
    }

    public function approve_request($ser_id)
    {
        et_require_post();
        if ($this->services_model->approve_request((int)$ser_id,(int)$this->session->userdata('m_id'),(string)$this->session->userdata('m_name'))) {
            $this->session->set_flashdata('save_success', TRUE);
        } else {
            $this->session->set_flashdata('message','ไม่สามารถอนุมัติคำขอได้ อุปกรณ์อาจถูกยืมไปแล้วหรือคำขอไม่อยู่ในสถานะรออนุมัติ');
        }
        redirect('staff');
    }

    public function reject_request($ser_id)
    {
        et_require_post();
        if ($this->services_model->reject_request((int)$ser_id,(int)$this->session->userdata('m_id'),(string)$this->session->userdata('m_name'))) {
            $this->session->set_flashdata('save_success', TRUE);
        } else {
            $this->session->set_flashdata('message','ไม่สามารถปฏิเสธคำขอนี้ได้');
        }
        redirect('staff');
    }

    public function return_list()
    {
        $this->render('staff/list_return', array('query'=>$this->services_model->list_return()));
    }

    public function damaged_list()
    {
        $this->render('staff/list_damaged', array('query'=>$this->services_model->list_damaged()));
    }

    // Legacy direct-borrow URLs now use the maintained multi-item screen.
    public function add_lend1(){ redirect('staff/add_lend4'); }
    public function add_lend2(){ redirect('staff/add_lend4'); }
    public function add_lend3(){ redirect('staff/add_lend4'); }
    public function add_lend1_db(){ redirect('staff/add_lend4'); }
    public function add_lend2_db(){ redirect('staff/add_lend4'); }
    public function add_lend3_db(){ redirect('staff/add_lend4'); }

    public function add_lend4()
    {
        $data=array('ld'=>$this->devices_model->list_devices_free());
        if ($this->input->post('s') === 'q') $data['query']=$this->member_model->find_borrower($this->input->post('m_id', TRUE));
        $this->render('staff/form_add_lend4',$data);
    }


    public function add_lend4_db()
    {
        $this->form_validation->set_rules('ref_m_id','นักศึกษา','trim|required|integer');
        $this->form_validation->set_rules('device[]','ครุภัณฑ์','required');
        $this->form_validation->set_rules('ser_reason','วัตถุประสงค์การยืม','trim|required|min_length[3]|max_length[255]');
        if ($this->form_validation->run() === FALSE) {
            $data=array('query'=>$this->member_model->read((int)$this->input->post('ref_m_id')),'ld'=>$this->devices_model->list_devices_free());
            $this->render('staff/form_add_lend4',$data); return;
        }
        if ($this->services_model->add_lend4_db()) $this->session->set_flashdata('save_success',TRUE);
        else $this->session->set_flashdata('message','ไม่สามารถบันทึกการยืมได้ อุปกรณ์ที่เลือกอาจไม่พร้อมใช้งาน');
        redirect('staff/loans');
    }

    public function edit_lend($ser_id)
    {
        $data['rsedit']=$this->services_model->query_edit_lend((int)$ser_id); if(!$data['rsedit']) show_404();
        $this->render('staff/form_edit_lend',$data);
    }

    public function edit_lend_db()
    {
        $this->form_validation->set_rules('ser_id','รายการยืม','trim|required|integer');
        $this->form_validation->set_rules('ser_reason','วัตถุประสงค์การยืม','trim|required|min_length[3]|max_length[255]');
        if($this->form_validation->run()===FALSE) return $this->edit_lend((int)$this->input->post('ser_id'));
        if($this->services_model->edit_lend_db()) $this->session->set_flashdata('save_success',TRUE);
        else $this->session->set_flashdata('message','ไม่สามารถแก้ไขรายการยืมได้');
        redirect('staff/loans');
    }

    public function return_lend($ser_id)
    {
        $data['rsedit']=$this->services_model->query_edit_lend((int)$ser_id); if(!$data['rsedit']) show_404();
        $data['querystatus']=$this->status_model->list_status_return();
        $this->render('staff/form_return_lend',$data);
    }

    public function return_lend_db()
    {
        $this->form_validation->set_rules('ser_id','รายการยืม','trim|required|integer');
        $this->form_validation->set_rules('ref_s_id','สถานะหลังคืน','trim|required|integer');
        if($this->form_validation->run()===FALSE) return $this->return_lend((int)$this->input->post('ser_id'));
        if($this->services_model->return_lend_db()) $this->session->set_flashdata('save_success',TRUE);
        else $this->session->set_flashdata('message','ไม่สามารถรับคืนรายการนี้ได้ หรือรายการถูกคืนไปแล้ว');
        redirect('staff/loans');
    }

    // Old unfinished document module is intentionally redirected instead of throwing errors.
    public function adding(){ $this->session->set_flashdata('message','โมดูลเอกสารเดิมไม่มีโครงสร้างข้อมูลรองรับ จึงปิดไว้เพื่อไม่ให้ระบบ Error'); redirect('staff'); }
    public function adddoc(){ return $this->adding(); }

    // ----- Profile -----
    public function profile()
    {
        $data['rsedit']=$this->member_model->read((int)$this->session->userdata('m_id')); if(!$data['rsedit']) show_404();
        $this->render('staff/member_form_edit',$data);
    }

    public function editdata()
    {
        $this->form_validation->set_rules('m_fname','คำนำหน้า','trim|required|max_length[30]');
        $this->form_validation->set_rules('m_name','ชื่อ','trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('m_lname','นามสกุล','trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('m_email','ข้อมูลหน่วยงาน/ผู้ใช้งาน','trim|required|max_length[120]');
        $this->form_validation->set_rules('m_phone','เบอร์โทรศัพท์','trim|required|min_length[9]|max_length[20]');
        if($this->form_validation->run()===FALSE) return $this->profile();

        // Force profile id to the logged-in account, never trust hidden POST id.
        $_POST['m_id']=(int)$this->session->userdata('m_id');
        if(!empty($_FILES['m_img']['name'])){
            if(!$this->upload_image('m_img',rtrim(FCPATH, '/\\').DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR,500)){ redirect('staff/profile'); return; }
            $ok=$this->member_model->editmember_img();
            if($ok) $this->session->set_userdata('m_img',$this->upload->file_name);
        }else $ok=$this->member_model->editstaff();
        if($ok){
            $member=$this->member_model->read((int)$this->session->userdata('m_id'));
            if($member) $this->session->set_userdata('m_name',trim($member->m_fname.' '.$member->m_name.' '.$member->m_lname));
            $this->session->set_flashdata('save_success',TRUE);
        } else $this->session->set_flashdata('message','ไม่สามารถแก้ไขข้อมูลส่วนตัวได้');
        redirect('staff/profile');
    }

    public function pwd()
    {
        $data['rsedit']=$this->member_model->read((int)$this->session->userdata('m_id')); if(!$data['rsedit']) show_404();
        $this->render('staff/member_form_pwd',$data);
    }

    public function editpwd()
    {
        $this->form_validation->set_rules('m_password','รหัสผ่าน','trim|required|min_length[4]|max_length[255]');
        $this->form_validation->set_rules('m_password2','ยืนยันรหัสผ่าน','trim|required|matches[m_password]');
        if($this->form_validation->run()===FALSE) return $this->pwd();
        $_POST['m_id']=(int)$this->session->userdata('m_id');
        if($this->member_model->editstaffpwd()) $this->session->set_flashdata('save_success',TRUE);
        else $this->session->set_flashdata('message','ไม่สามารถเปลี่ยนรหัสผ่านได้');
        redirect('staff/profile');
    }

    // ----- Device types -----
    public function type(){ $this->render('staff/type_list',array('query'=>$this->type_model->list_type())); }
    public function add_type(){ $this->render('staff/type_form_add'); }
    public function addtype_db()
    {
        $this->form_validation->set_rules('t_name','ชื่อประเภทครุภัณฑ์','trim|required|min_length[2]|max_length[80]');
        if($this->form_validation->run()===FALSE) return $this->add_type();
        if($this->type_model->name_exists($this->input->post('t_name'))){ $this->session->set_flashdata('message','ชื่อประเภทนี้มีอยู่แล้ว'); redirect('staff/add_type'); return; }
        if($this->type_model->addtype_db()) $this->session->set_flashdata('save_success',TRUE); else $this->session->set_flashdata('message','เพิ่มประเภทไม่สำเร็จ ชื่ออาจซ้ำ');
        redirect('staff/type');
    }
    public function edit_type($t_id){ $data['query']=$this->type_model->read((int)$t_id); if(!$data['query']) show_404(); $this->render('staff/type_form_edit',$data); }
    public function edittype_db()
    {
        $this->form_validation->set_rules('t_name','ชื่อประเภทครุภัณฑ์','trim|required|min_length[2]|max_length[80]');
        if($this->form_validation->run()===FALSE) return $this->edit_type((int)$this->input->post('t_id'));
        if($this->type_model->name_exists($this->input->post('t_name'),(int)$this->input->post('t_id'))){ $this->session->set_flashdata('message','ชื่อประเภทนี้มีอยู่แล้ว'); redirect('staff/edit_type/'.(int)$this->input->post('t_id')); return; }
        if($this->type_model->edittype_db()) $this->session->set_flashdata('save_success',TRUE); else $this->session->set_flashdata('message','แก้ไขประเภทไม่สำเร็จ');
        redirect('staff/type');
    }
    public function del_type($t_id)
    {
        et_require_post();
        if($this->type_model->deltype_db((int)$t_id)) $this->session->set_flashdata('del_success',TRUE);
        else $this->session->set_flashdata('message','ไม่สามารถลบประเภทนี้ได้ เพราะมีครุภัณฑ์หรือประวัติยืม–คืนใช้งานอยู่');
        redirect('staff/type');
    }

    // ----- Devices -----
    public function devices(){ $this->render('staff/devices_list',array('query'=>$this->devices_model->list_devices())); }
    public function add_devices(){ $this->render('staff/devices_form_add',array('query'=>$this->type_model->list_type(),'querystatus'=>$this->status_model->list_status())); }
    public function add_devices_db()
    {
        $this->set_device_rules(FALSE);
        if($this->form_validation->run()===FALSE) return $this->add_devices();
        if(!$this->type_model->read((int)$this->input->post('ref_t_id')) || !$this->status_model->read((int)$this->input->post('ref_s_id'))){ $this->session->set_flashdata('message','ประเภทหรือสถานะครุภัณฑ์ไม่ถูกต้อง'); redirect('staff/add_devices'); return; }
        if((int)$this->input->post('ref_s_id')===2){ $this->session->set_flashdata('message','ครุภัณฑ์ใหม่ไม่สามารถเริ่มต้นด้วยสถานะกำลังถูกยืมได้'); redirect('staff/add_devices'); return; }
        if($this->devices_model->device_code_exists($this->input->post('d_id'))){ $this->session->set_flashdata('message','เลขครุภัณฑ์นี้มีอยู่แล้ว'); redirect('staff/add_devices'); return; }
        $_POST['ref_m_id']=(int)$this->session->userdata('m_id');
        $deviceImage='';
        if(!empty($_FILES['d_img']['name'])){
            if(!$this->upload_image('d_img',rtrim(FCPATH, '/\\').DIRECTORY_SEPARATOR.'devices'.DIRECTORY_SEPARATOR,800)){ redirect('staff/add_devices'); return; }
            $deviceImage=(string)$this->upload->file_name;
        }
        if($this->devices_model->add_devices_db($deviceImage)){
            $this->session->set_flashdata('save_success',TRUE);
        }else{
            if($deviceImage!=='') @unlink(rtrim(FCPATH, '/\\').DIRECTORY_SEPARATOR.'devices'.DIRECTORY_SEPARATOR.basename($deviceImage));
            $this->session->set_flashdata('message','ไม่สามารถเพิ่มครุภัณฑ์ได้');
        }
        redirect('staff/devices');
    }
    public function edit_devices($no)
    {
        $data=array('query'=>$this->type_model->list_type(),'querystatus'=>$this->status_model->list_status(),'rsedit'=>$this->devices_model->read((int)$no));
        if(!$data['rsedit']) show_404(); $this->render('staff/devices_form_edit',$data);
    }
    public function edit_devices_db()
    {
        $no=(int)$this->input->post('no'); $this->set_device_rules(TRUE);
        if($this->form_validation->run()===FALSE) return $this->edit_devices($no);
        if(!$this->type_model->read((int)$this->input->post('ref_t_id')) || !$this->status_model->read((int)$this->input->post('ref_s_id'))){ $this->session->set_flashdata('message','ประเภทหรือสถานะครุภัณฑ์ไม่ถูกต้อง'); redirect('staff/edit_devices/'.$no); return; }
        if($this->devices_model->device_code_exists($this->input->post('d_id'),$no)){ $this->session->set_flashdata('message','เลขครุภัณฑ์นี้มีอยู่แล้ว'); redirect('staff/edit_devices/'.$no); return; }
        $pending=$this->devices_model->has_pending_request_by_no($no);
        if($pending && (int)$this->input->post('ref_s_id')!==1){ $this->session->set_flashdata('message','ครุภัณฑ์นี้มีคำขอยืมรออนุมัติ กรุณาอนุมัติ/ปฏิเสธคำขอก่อนเปลี่ยนสถานะ'); redirect('staff/edit_devices/'.$no); return; }
        $active=$this->devices_model->has_active_loan_by_no($no);
        if($active && (int)$this->input->post('ref_s_id')!==2){ $this->session->set_flashdata('message','ครุภัณฑ์นี้กำลังถูกยืม สถานะต้องเป็น “กำลังถูกยืม” จนกว่าจะรับคืน'); redirect('staff/edit_devices/'.$no); return; }
        if(!$active && (int)$this->input->post('ref_s_id')===2){ $this->session->set_flashdata('message','ไม่สามารถตั้งสถานะ “กำลังถูกยืม” โดยไม่มีรายการยืมที่ใช้งานอยู่'); redirect('staff/edit_devices/'.$no); return; }
        $newDeviceImage='';
        if(!empty($_FILES['d_img']['name'])){
            if(!$this->upload_image('d_img',rtrim(FCPATH, '/\\').DIRECTORY_SEPARATOR.'devices'.DIRECTORY_SEPARATOR,800)){ redirect('staff/edit_devices/'.$no); return; }
            $newDeviceImage=(string)$this->upload->file_name;
            $ok=$this->devices_model->edit_devices_db_img($newDeviceImage);
            if(!$ok && $newDeviceImage!=='') @unlink(rtrim(FCPATH, '/\\').DIRECTORY_SEPARATOR.'devices'.DIRECTORY_SEPARATOR.basename($newDeviceImage));
        }else $ok=$this->devices_model->edit_devices_db();
        if($ok) $this->session->set_flashdata('save_success',TRUE); else $this->session->set_flashdata('message','ไม่สามารถแก้ไขครุภัณฑ์ได้');
        redirect('staff/devices');
    }
    public function del_devices($no)
    {
        et_require_post();
        if($this->devices_model->del_devices_db((int)$no)) $this->session->set_flashdata('del_success',TRUE);
        else $this->session->set_flashdata('message','ไม่สามารถลบครุภัณฑ์นี้ได้ เพราะมีประวัติยืม–คืนที่ต้องเก็บไว้');
        redirect('staff/devices');
    }

    // ----- Device statuses -----
    public function status(){ $this->render('staff/status_list',array('querystatus'=>$this->status_model->list_status())); }
    public function add_status(){ $this->render('staff/status_form_add'); }
    public function addstatus_db()
    {
        $this->form_validation->set_rules('s_name','ชื่อสถานะ','trim|required|min_length[2]|max_length[50]');
        if($this->form_validation->run()===FALSE) return $this->add_status();
        if($this->status_model->name_exists($this->input->post('s_name'))){ $this->session->set_flashdata('message','ชื่อสถานะนี้มีอยู่แล้ว'); redirect('staff/add_status'); return; }
        if($this->status_model->addstatus_db()) $this->session->set_flashdata('save_success',TRUE); else $this->session->set_flashdata('message','เพิ่มสถานะไม่สำเร็จ ชื่ออาจซ้ำ');
        redirect('staff/status');
    }
    public function edit_status($s_id){ $s_id=(int)$s_id; if(in_array($s_id,array(1,2,3),TRUE)){ $this->session->set_flashdata('message','สถานะหลักของระบบถูกล็อกเพื่อให้ตรรกะยืม–คืนทำงานถูกต้อง'); redirect('staff/status'); return; } $data['rsedit']=$this->status_model->read($s_id); if(!$data['rsedit']) show_404(); $this->render('staff/status_form_edit',$data); }
    public function editstatus_db()
    {
        $this->form_validation->set_rules('s_name','ชื่อสถานะ','trim|required|min_length[2]|max_length[50]');
        if($this->form_validation->run()===FALSE) return $this->edit_status((int)$this->input->post('s_id'));
        if(in_array((int)$this->input->post('s_id'),array(1,2,3),TRUE)){ $this->session->set_flashdata('message','สถานะหลักของระบบไม่สามารถแก้ชื่อได้'); redirect('staff/status'); return; }
        if($this->status_model->name_exists($this->input->post('s_name'),(int)$this->input->post('s_id'))){ $this->session->set_flashdata('message','ชื่อสถานะนี้มีอยู่แล้ว'); redirect('staff/edit_status/'.(int)$this->input->post('s_id')); return; }
        if($this->status_model->editstatus_db()) $this->session->set_flashdata('save_success',TRUE); else $this->session->set_flashdata('message','แก้ไขสถานะไม่สำเร็จ');
        redirect('staff/status');
    }
    public function del_status($s_id)
    {
        et_require_post();
        if($this->status_model->del_status_db((int)$s_id)) $this->session->set_flashdata('del_success',TRUE);
        else $this->session->set_flashdata('message','สถานะหลักของระบบหรือสถานะที่กำลังถูกใช้งานไม่สามารถลบได้');
        redirect('staff/status');
    }

    public function report(){ redirect('report'); }

    private function student_members()
    {
        return array_values(array_filter($this->member_model->list_member(), function($m){ return !in_array((int)$m->ref_pid,array(1,3),TRUE); }));
    }

    private function set_device_rules($editing)
    {
        $this->form_validation->set_rules('ref_t_id','ประเภทครุภัณฑ์','trim|required|integer');
        $this->form_validation->set_rules('ref_s_id','สถานะ','trim|required|integer');
        $this->form_validation->set_rules('d_id','เลขครุภัณฑ์','trim|required|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('d_name','ชื่อครุภัณฑ์','trim|required|min_length[2]|max_length[120]');
        $this->form_validation->set_rules('d_detail','รายละเอียดครุภัณฑ์','trim|required|min_length[2]');
        if($editing) $this->form_validation->set_rules('no','รหัสรายการ','trim|required|integer');
    }

    private function upload_image($field,$path,$size)
    {
        if(!is_dir($path)) @mkdir($path,0775,TRUE);
        if(DIRECTORY_SEPARATOR==='/' && is_dir($path) && !is_writable($path)) @chmod($path,0775);
        if(!is_dir($path)||!is_writable($path)){ $this->session->set_flashdata('message','โฟลเดอร์สำหรับอัปโหลดไม่มีสิทธิ์เขียนไฟล์ กรุณาตรวจสอบสิทธิ์ของโฟลเดอร์โปรเจกต์'); return FALSE; }
        $this->load->library('upload');
        $this->upload->initialize(array('encrypt_name'=>TRUE,'upload_path'=>$path,'allowed_types'=>'gif|jpg|png|jpeg','max_size'=>5120,'remove_spaces'=>TRUE),TRUE);
        if(!$this->upload->do_upload($field)){ $this->session->set_flashdata('message',$this->upload->display_errors('','')); return FALSE; }
        $this->load->library('image_lib');
        $uploaded=$path.$this->upload->file_name;
        $this->image_lib->initialize(array('source_image'=>$uploaded,'new_image'=>$path,'maintain_ratio'=>TRUE,'width'=>$size,'height'=>$size));
        if(!$this->image_lib->resize()){ $error=$this->image_lib->display_errors('',''); $this->image_lib->clear(); @unlink($uploaded); $this->session->set_flashdata('message','ปรับขนาดรูปไม่สำเร็จ: '.$error); return FALSE; }
        $this->image_lib->clear(); return TRUE;
    }

    private function render($view,$data=array())
    {
        $this->load->view('template/backheader_staff');
        $this->load->view($view,$data);
        $this->load->view('template/backfooter');
    }
}
