<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class OnlineRegistration extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model("mstudent");
		$this->load->library('form_validation');
		$this->load->library('session');
	}

	public function index()
	{
		$this->load->view('v_register_online');
	}

	public function store()
	{
		date_default_timezone_set("Asia/Jakarta");
		$date = date('Y-m-d');
		$time = date('Y-m-d h:i:s');

		$birthday = $this->input->post('month') . " " . $this->input->post('date');
		$data = array(
			'name' => $this->input->post('name'),
			'phone' => $this->input->post('phone'),
			'address' => $this->input->post('address'),
			'school' => $this->input->post('school'),
			'grade' => $this->input->post('grade'),
			'birthday' => $birthday,
			'entrydate' => $date,
			'adjusment' => 0,
			'balance' => 0,
			'penalty' => 0,
			'status' => "ACTIVE",
			'condition' => "DEFAULT",
			'is_online' => true
		);
		$latestRecordStudent = $this->mstudent->addStudent($data);
		$this->session->set_flashdata('success', "Registration Success");
		redirect(base_url("OnlineRegistration"));
	}
}
