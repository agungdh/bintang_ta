<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	function __construct(){
		parent::__construct();
	}

	function index() {
		if ($this->session->login != true) {
			$this->load->view("template/login");
		} else {
			$data['isi'] = "welcome/index";
			$data['js'] = "welcome/index_js";
			$data['data']['config'] = $this->db->get('config')->row();
			
			$this->load->view("template/template", $data);
		}
	}

	function login() {
		$data_user = $this->db->get_where('user', ['username' => $this->input->post('username'), 'password' => hash("sha512", $this->input->post('password'))])->row();

		if ($data_user != null) {			
			$array_data_user = array(
				'id'  => $data_user->id,
				'nama'  => $data_user->nama,
				'username'  => $data_user->username,
				'level'  => $data_user->level,
				'bidang_id'  => $data_user->bidang_id,
				'login'  => true
			);

			$this->session->set_userdata($array_data_user);

			echo json_encode(['login' => true]);
		} else {
			$data['header'] = "ERROR !!!";
			$data['pesan'] = "Password Salah !!!";
			$data['status'] = "error";

			$data['login'] = false;

			echo json_encode($data);
		}
	}

}