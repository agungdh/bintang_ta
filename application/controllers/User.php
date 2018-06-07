<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	function __construct(){
		parent::__construct();
		
		$this->pustaka->auth($this->session->level, [1]);
	}

	function index() {
		$data['isi'] = 'user/index';
		$data['js'] = 'user/index_js';

		$this->load->view('template/template', $data);
	}

	function tambah() {
		$data['isi'] = 'user/tambah';
		$data['js'] = 'user/tambah_js';

		$this->load->view('template/template', $data);
	}

	function ubah($id) {
		$data['isi'] = 'user/ubah';
		$data['js'] = 'user/ubah_js';
		$data['data']['user'] = $this->db->get_where('user', ['id' => $id])->row();

		$this->load->view('template/template', $data);
	}

	function aksi_tambah() {
		foreach ($this->input->post('data') as $key => $value) {
			switch ($key) {
				case 'password':
					$data[$key] = hash('sha512', $value);
					break;
				
				default:
					$data[$key] = $value;
					break;
			}
		}

		$this->db->insert('user', $data);

		redirect(base_url('user'));
	}

	function aksi_ubah() {
		foreach ($this->input->post('data') as $key => $value) {
			$data[$key] = $value;
		}

		foreach ($this->input->post('where') as $key => $value) {
			$where[$key] = $value;
		}

		$this->db->update('user', $data, $where);

		redirect(base_url('user'));
	}

	function aksi_ubah_password() {
		foreach ($this->input->post('data') as $key => $value) {
			$data[$key] = hash('sha512', $value);
		}

		foreach ($this->input->post('where') as $key => $value) {
			$where[$key] = $value;
		}

		$this->db->update('user', $data, $where);

		redirect(base_url('user'));
	}

	function aksi_hapus($id) {
		$this->db->delete('user', ['id' => $id]);

		redirect(base_url('user'));
	}

	function ajax(){
	    $requestData = $_REQUEST;
	    $columns = ['nama', 'username', 'level'];

	      $row = $this->db->query("SELECT count(*) total_data 
	        FROM user
	        WHERE id != ?", [$this->session->id])->row();

	        $totalData = $row->total_data;
	        $totalFiltered = $totalData; 

	    $data = [];

	    if( !empty($requestData['search']['value']) ) {
	      $search_value = "%" . $requestData['search']['value'] . "%";

		    $cari = [];

		    $cari[] = $this->session->id;

	  	    for ($i=1; $i <= 2; $i++) { 
		    	$cari[] = $search_value;
		    }

	      $row = $this->db->query("SELECT count(*) total_data 
	        FROM user
	        WHERE (id != ?
	        		AND (username LIKE ? OR nama LIKE ?)
	    		)", $cari)->row();

	        $totalFiltered = $row->total_data; 

	      $query = $this->db->query("SELECT *
	        FROM user
	        WHERE (id != ?
	        		AND (username LIKE ? OR nama LIKE ?)
	    		)
	        ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length'], $cari);
	            
	    } else {  

	      $query = $this->db->query("SELECT *
	        FROM user
	        WHERE id != ?
	        ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length'], [$this->session->id]);
	            
	    }

	    foreach ($query->result() as $row) { 
	      $nestedData=[]; 
	      $id = $row->id;
	      $nestedData[] = $row->nama;
	      $nestedData[] = $row->username;
	      $nestedData[] = '
	          <div class="btn-group">
	            <a class="btn btn-primary" href="' . base_url('user/ubah/' . $row->id) . '" data-toggle="tooltip" title="Ubah"><i class="fa fa-edit"></i></a>
	            <a class="btn btn-primary" href="#" onclick="hapus(' . "'$row->id'" . ')" data-toggle="tooltip" title="Hapus"><i class="fa fa-trash"></i></a>
	          </div>';

	      $data[] = $nestedData;
	        
	    }

	    $json_data = [
	          "draw"            => intval( $requestData['draw'] ),    
	          "recordsTotal"    => intval( $totalData ), 
	          "recordsFiltered" => intval( $totalFiltered ), 
	          "data"            => $data   
	          ];

	    echo json_encode($json_data);  
	  }

}