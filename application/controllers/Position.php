<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[\AllowDynamicProperties]
class Position extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if((int)$this->session->userdata('m_level')!==1) redirect('user');
        $this->load->model('position_model');
    }
    public function index(){ $this->render('admin/position',array('query'=>$this->position_model->list_position())); }
    public function adding(){ $this->render('admin/position_form_add'); }
    public function adddata()
    {
        $this->form_validation->set_rules('pname','ชื่อประเภทผู้ใช้งาน','trim|required|min_length[2]|max_length[100]');
        if($this->form_validation->run()===FALSE) return $this->adding();
        if($this->position_model->name_exists($this->input->post('pname'))){ $this->session->set_flashdata('message','ชื่อประเภทผู้ใช้งานนี้มีอยู่แล้ว'); redirect('position/adding'); return; }
        if($this->position_model->addposition()) $this->session->set_flashdata('save_success',TRUE); else $this->session->set_flashdata('message','เพิ่มประเภทผู้ใช้งานไม่สำเร็จ ชื่ออาจซ้ำ');
        redirect('position');
    }
    public function edit($pid){ $pid=(int)$pid; if(in_array($pid,array(1,3,4),TRUE)){ $this->session->set_flashdata('message','ประเภทผู้ใช้งานหลักถูกล็อกเพื่อให้สิทธิ์ระบบทำงานถูกต้อง'); redirect('position'); return; } $data['rsedit']=$this->position_model->read($pid); if(!$data['rsedit']) show_404(); $this->render('admin/position_form_edit',$data); }
    public function editdata()
    {
        $this->form_validation->set_rules('pid','รหัสประเภท','trim|required|integer');
        $this->form_validation->set_rules('pname','ชื่อประเภทผู้ใช้งาน','trim|required|min_length[2]|max_length[100]');
        if($this->form_validation->run()===FALSE) return $this->edit((int)$this->input->post('pid'));
        if(in_array((int)$this->input->post('pid'),array(1,3,4),TRUE)){ $this->session->set_flashdata('message','ประเภทผู้ใช้งานหลักไม่สามารถแก้ชื่อได้'); redirect('position'); return; }
        if($this->position_model->name_exists($this->input->post('pname'),(int)$this->input->post('pid'))){ $this->session->set_flashdata('message','ชื่อประเภทผู้ใช้งานนี้มีอยู่แล้ว'); redirect('position/edit/'.(int)$this->input->post('pid')); return; }
        if($this->position_model->editposition()) $this->session->set_flashdata('save_success',TRUE); else $this->session->set_flashdata('message','แก้ไขประเภทผู้ใช้งานไม่สำเร็จ');
        redirect('position');
    }
    public function del($pid)
    {
        et_require_post();
        if($this->position_model->deldata((int)$pid)) $this->session->set_flashdata('del_success',TRUE);
        else $this->session->set_flashdata('message','ประเภทผู้ใช้งานหลักหรือประเภทที่มีผู้ใช้งานอยู่ไม่สามารถลบได้');
        redirect('position');
    }
    private function render($view,$data=array()){ $this->load->view('template/backheader');$this->load->view($view,$data);$this->load->view('template/backfooter'); }
}
