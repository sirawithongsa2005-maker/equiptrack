<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[\AllowDynamicProperties]
class Admin extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata('m_level') !=1){
				redirect('user','refresh');
		}
		$this->load->model('member_model');
		$this->load->model('services_model');
		$this->load->model('devices_model');
		$this->load->model('dashboard_model');
	}

	public function index()
	{
		$data['stats'] = $this->dashboard_model->stats();
		$data['recent'] = $this->dashboard_model->recent_loans(8);
		$this->load->view('template/backheader');
		$this->load->view('admin/dashboard',$data);
		$this->load->view('template/backfooter');
	}

		public function devices()
{
		$data['query']=$this->devices_model->list_devices();
		$this->load->view('template/backheader');
		$this->load->view('admin/devices_list',$data);
		$this->load->view('template/backfooter');	 
}


}
