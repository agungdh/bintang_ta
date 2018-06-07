<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fakultas extends CI_Controller {
	function __construct(){
		parent::__construct();

		$this->pustaka->auth($this->session->level, [1, 2]);
	}

	function index() {
		$data['isi'] = 'fakultas/index';
		$data['js'] = 'fakultas/index_js';

		$this->load->view('template/template', $data);
	}

	function tambah() {
		$data['isi'] = 'fakultas/tambah';
		$data['js'] = 'fakultas/tambah_js';

		$this->load->view('template/template', $data);
	}

	function ubah($id) {
		$data['isi'] = 'fakultas/ubah';
		$data['js'] = 'fakultas/ubah_js';
		$data['data']['fakultas'] = $this->db->get_where('fakultas', ['id' => $id])->row();

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

		$this->db->insert('fakultas', $data);

		redirect(base_url('fakultas'));
	}

	function aksi_ubah() {
		foreach ($this->input->post('data') as $key => $value) {
			$data[$key] = $value;
		}

		foreach ($this->input->post('where') as $key => $value) {
			$where[$key] = $value;
		}

		$this->db->update('fakultas', $data, $where);

		redirect(base_url('fakultas'));
	}

	function aksi_hapus($id) {
		$this->db->delete('fakultas', ['id' => $id]);

		redirect(base_url('fakultas'));
	}

	function ajax(){
	    $requestData = $_REQUEST;
	    $columns = ['nama'];

	      $row = $this->db->query("SELECT count(*) total_data 
	        FROM fakultas", [])->row();

	        $totalData = $row->total_data;
	        $totalFiltered = $totalData; 

	    $data = [];

	    if( !empty($requestData['search']['value']) ) {
	      $search_value = "%" . $requestData['search']['value'] . "%";

		    $cari = [];

	  	    for ($i=1; $i <= 1; $i++) { 
		    	$cari[] = $search_value;
		    }

	      $row = $this->db->query("SELECT count(*) total_data 
	        FROM fakultas
	        WHERE nama LIKE ?", $cari)->row();

	        $totalFiltered = $row->total_data; 

	      $query = $this->db->query("SELECT *
	        FROM fakultas
	        WHERE nama LIKE ?
	        ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length'], $cari);
	            
	    } else {  

	      $query = $this->db->query("SELECT *
	        FROM fakultas
	        ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length'], []);
	            
	    }

	    foreach ($query->result() as $row) { 
	      $nestedData=[]; 
	      $id = $row->id;
	      $nestedData[] = $row->nama;
	      $nestedData[] = '
	          <div class="btn-group">
	            <a class="btn btn-primary" href="' . base_url('prodi/index/' . $row->id) . '" data-toggle="tooltip" title="Prodi"><i class="fa fa-share"></i></a>
	            <a class="btn btn-primary" href="' . base_url('fakultas/ubah/' . $row->id) . '" data-toggle="tooltip" title="Ubah"><i class="fa fa-edit"></i></a>
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