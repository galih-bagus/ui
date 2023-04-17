<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Teacher extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model("mteacher");
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->library('bcrypt');
		if ($this->session->userdata('status') != "login") {
			redirect(base_url("login"));
		}
	}

	public function index()
	{
		$data['data'] = $this->mteacher->index();
		$this->load->view('v_header');
		$this->load->view('v_teacherlist', $data);
		$this->load->view('v_footer');
	}

	public function create()
	{
		$this->load->view('v_header');
		$this->load->view('v_teacherform');
		$this->load->view('v_footer');
	}

	public function store()
	{
		$this->form_validation->set_rules('name', 'name', 'required');
		if ($this->form_validation->run() == FALSE) {
			$this->load->view('v_header');
			$this->load->view('v_teacherform');
			$this->load->view('v_footer');
		} else {
			date_default_timezone_set("Asia/Jakarta");
			$date = date('Y-m-d');
			$time = date('Y-m-d h:i:s');
			$data = array(
				'name' => $this->input->post('name'),
				'username' => $this->input->post('username'),
				'password' => $this->bcrypt->hash_password('password')
			);
			$latestRecordStudent = $this->mteacher->store($data);
			$this->session->set_flashdata('success', "Add Teacher Success");
			redirect(base_url("teacher"));
		}
	}

	public function edit($id)
	{
		$data['teacher'] = $this->mteacher->edit($id);
		$this->load->view('v_header');
		$this->load->view('v_teacheredit', $data);
		$this->load->view('v_footer');
	}

	public function update($id)
	{
		$this->form_validation->set_rules('name', 'name', 'required');
		if ($this->form_validation->run() == FALSE) {
			$data['teacher'] = $this->mteacher->edit($id);
			$this->load->view('v_header');
			$this->load->view('v_teacheredit', $data);
			$this->load->view('v_footer');
		} else {
			date_default_timezone_set("Asia/Jakarta");
			$date = date('Y-m-d');
			$time = date('Y-m-d h:i:s');
			$data = array(
				'name' => $this->input->post('name'),
				'username' => $this->input->post('username'),
			);
			$latestRecordStudent = $this->mteacher->update($data, $id);
			$this->session->set_flashdata('success', "Add Teacher Success");
			redirect(base_url("teacher"));
		}
	}

	public function destroy($id)
	{
		$this->mteacher->destroy($id);
		redirect(base_url("teacher"));
	}
}
