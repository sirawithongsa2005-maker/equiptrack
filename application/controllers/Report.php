<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[\AllowDynamicProperties]
class Report extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if(!in_array((int)$this->session->userdata('m_level'), array(1,3), TRUE)){
			redirect('user','refresh');
		}
		$this->load->model('member_model');
		$this->load->model('services_model');
		$this->load->model('devices_model');
		$this->load->model('report_model');
	}

	public function index()
	{
		//print_r($_SESSION);
		$data['query']=$this->report_model->listall();
		
		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';
		// exit;

		$this->load->view('template/backheader_report');
	    $this->load->view('report/list_all',$data);
		$this->load->view('template/backfooter');
	}


	public function bymember()
	{
		$data['query']=$this->report_model->list_bymember();
		
		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';
		// exit;

		$this->load->view('template/backheader_report');
	    $this->load->view('report/list_bymember',$data); //$data
		$this->load->view('template/backfooter');
	}


	public function viewbymember($m_id)
	{
		$data['query']=$this->report_model->list_viewbymember($m_id);
		
		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';
		// exit;

		$this->load->view('template/backheader_report');
	     $this->load->view('report/list_all',$data); //$data
		$this->load->view('template/backfooter');
	}



public function byposition()
	{
		$data['query']=$this->report_model->list_byposition();
		
		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';
		// exit;

		$this->load->view('template/backheader_report');
	    $this->load->view('report/list_byposition',$data); //$data
		$this->load->view('template/backfooter');
	}


	public function byposition_chart()
	{
		$data['query']=$this->report_model->list_byposition();
		
		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';
		// exit;

		$this->load->view('template/backheader_report');
	    $this->load->view('report/list_byposition_chart',$data); //$data
		$this->load->view('template/backfooter');
	}

	public function viewbyposition($pid)
	{
		$data['query']=$this->report_model->list_viewbyposition($pid);
		
		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';
		// exit;

		$this->load->view('template/backheader_report');
	     $this->load->view('report/list_viewbyposition',$data); //$data
		$this->load->view('template/backfooter');
	}


	public function bytype()
	{
		$data['query']=$this->report_model->list_bytype();
		
		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';
		// exit;

		$this->load->view('template/backheader_report');
	    $this->load->view('report/list_bytype',$data); //$data
		$this->load->view('template/backfooter');
	}

	public function bytype_chart()
	{
		$data['query']=$this->report_model->list_bytype();
		
		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';
		// exit;

		$this->load->view('template/backheader_report');
	    $this->load->view('report/list_bytype_chart',$data); //$data
		$this->load->view('template/backfooter');
	}


	public function viewbytype($t_id)
	{
		$data['query']=$this->report_model->list_viewbytype($t_id);
		
		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';
		// exit;

		$this->load->view('template/backheader_report');
	     $this->load->view('report/list_viewbytype',$data); //$data
		$this->load->view('template/backfooter');
	}


	public function searchbydate()
	{
		//print_r($_SESSION);
		$data['query']=$this->report_model->listall();
		
		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';
		// exit;

		$this->load->view('template/backheader_report');
	    $this->load->view('report/list_form_search',$data); //$data
		$this->load->view('template/backfooter');
	}

	public function searchbydate_db()
	{
        $this->form_validation->set_rules('ds','วันที่เริ่มต้น','trim|required');
        $this->form_validation->set_rules('de','วันที่สิ้นสุด','trim|required');
        $ds=(string)$this->input->post('ds',TRUE);
        $de=(string)$this->input->post('de',TRUE);
        if ($this->form_validation->run()===FALSE || !$this->valid_date($ds) || !$this->valid_date($de) || $ds>$de) {
            $this->session->set_flashdata('message','กรุณาเลือกช่วงวันที่ให้ถูกต้อง');
            redirect('report/searchbydate');
            return;
        }
        $data['query']=$this->report_model->listbydate($ds,$de);
        $this->load->view('template/backheader_report');
        $this->load->view('report/list_form_search',$data);
        $this->load->view('template/backfooter');
	}


	public function byday()
	{
		$data['query']=$this->report_model->list_byday();
		
		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';
		// exit;

		$this->load->view('template/backheader_report');
	    $this->load->view('report/list_byday',$data); //$data
		$this->load->view('template/backfooter');
	}



	public function bymonth()
	{
		$data['query']=$this->report_model->list_bymonth();
		
		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';
		// exit;

		$this->load->view('template/backheader_report');
	    $this->load->view('report/list_bymonth',$data); //$data
		$this->load->view('template/backfooter');
	}


	public function byyear()
	{
		$data['query']=$this->report_model->list_byyear();
		
		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';
		// exit;

		$this->load->view('template/backheader_report');
	    $this->load->view('report/list_byyear',$data); //$data
		$this->load->view('template/backfooter');
	}












	

		

    private function valid_date($value)
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', (string)$value)) return FALSE;
        $date = DateTime::createFromFormat('!Y-m-d', (string)$value);
        $errors = DateTime::getLastErrors();
        if ($date === FALSE) return FALSE;
        if (is_array($errors) && ($errors['warning_count'] > 0 || $errors['error_count'] > 0)) return FALSE;
        return $date->format('Y-m-d') === $value;
    }

}
