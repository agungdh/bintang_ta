<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat extends CI_Controller {
	function __construct(){
		parent::__construct();
		
		$this->pustaka->auth($this->session->level, [1, 2, 3, 4, 5]);
	}

	function index() {
		$data['isi'] = 'surat/index';
		$data['js'] = 'surat/index_js';

		$this->load->view('template/template', $data);
	}

	function tambah() {
		$data['isi'] = 'surat/tambah';
		$data['js'] = 'surat/tambah_js';

		$this->load->view('template/template', $data);
	}

	function ubah($id) {
		$data['isi'] = 'surat/ubah';
		$data['js'] = 'surat/ubah_js';
		$data['data']['surat'] = $this->db->get_where('surat', ['id' => $id])->row();

		$this->load->view('template/template', $data);
	}

	function aksi_tambah() {
		foreach ($this->input->post('data') as $key => $value) {
			switch ($key) {	
				default:
					$data[$key] = $value;
					break;
			}
		}

		$this->db->insert('surat', $data);

		redirect(base_url('surat'));
	}

	function aksi_ubah() {
		foreach ($this->input->post('data') as $key => $value) {
			$data[$key] = $value;	
		}
		
		if (!isset($data['bidang_id'])) {
			$data['bidang_id'] = null;
		}

		foreach ($this->input->post('where') as $key => $value) {
			$where[$key] = $value;
		}

		$this->db->update('surat', $data, $where);

		redirect(base_url('surat'));
	}
	
	function aksi_hapus($id) {
		$this->db->delete('surat', ['id' => $id]);

		redirect(base_url('surat'));
	}

}