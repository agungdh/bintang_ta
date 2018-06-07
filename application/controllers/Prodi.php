<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prodi extends CI_Controller {
	function __construct(){
		parent::__construct();
		
		$this->pustaka->auth($this->session->level, [1, 2]);
	}

	function index($fakultas_id) {
		$data['isi'] = 'prodi/index';
		$data['js'] = 'prodi/index_js';
		$data['data']['fakultas'] = $this->db->get_where('fakultas', ['id' => $fakultas_id])->row();

		$this->load->view('template/template', $data);
	}

	function tambah($fakultas_id) {
		$data['isi'] = 'prodi/tambah';
		$data['js'] = 'prodi/tambah_js';
		$data['data']['fakultas'] = $this->db->get_where('fakultas', ['id' => $fakultas_id])->row();

		$this->load->view('template/template', $data);
	}

	function ubah($id) {
		$data['isi'] = 'prodi/ubah';
		$data['js'] = 'prodi/ubah_js';
		$data['data']['prodi'] = $this->db->get_where('prodi', ['id' => $id])->row();
		$data['data']['fakultas'] = $this->db->get_where('fakultas', ['id' => $data['data']['prodi']->fakultas_id])->row();

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

		$this->db->insert('prodi', $data);

		redirect(base_url('prodi/index/' . $data['fakultas_id']));
	}

	function aksi_ubah() {
		foreach ($this->input->post('data') as $key => $value) {
			$data[$key] = $value;
		}

		foreach ($this->input->post('where') as $key => $value) {
			$where[$key] = $value;
		}

		$this->db->update('prodi', $data, $where);

		redirect(base_url('prodi/index/' . $data['fakultas_id']));
	}

	function aksi_hapus($id) {
		$this->db->delete('prodi', ['id' => $id]);

		redirect(base_url('prodi'));
	}

	function ajax($fakultas_id){
	    $requestData = $_REQUEST;
	    $columns = ['nama'];

	      $row = $this->db->query("SELECT count(*) total_data 
	        FROM prodi
	        WHERE fakultas_id = ?", [$fakultas_id])->row();

	        $totalData = $row->total_data;
	        $totalFiltered = $totalData; 

	    $data = [];

	    if( !empty($requestData['search']['value']) ) {
	      $search_value = "%" . $requestData['search']['value'] . "%";

		    $cari = [];

		    $cari[] = $fakultas_id;

	  	    for ($i=1; $i <= 1; $i++) { 
		    	$cari[] = $search_value;
		    }

	      $row = $this->db->query("SELECT count(*) total_data 
	        FROM prodi
	        WHERE fakultas_id = ?
	        AND (nama LIKE ?)", $cari)->row();

	        $totalFiltered = $row->total_data; 

	      $query = $this->db->query("SELECT *
	        FROM prodi
	        WHERE fakultas_id = ?
	        AND (nama LIKE ?)
	        ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length'], $cari);
	            
	    } else {  

	      $query = $this->db->query("SELECT *
	        FROM prodi
	        WHERE fakultas_id = ?
	        ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length'], [$fakultas_id]);
	            
	    }

	    foreach ($query->result() as $row) { 
	      $nestedData=[]; 
	      $id = $row->id;
	      $nestedData[] = $row->nama;
	      $nestedData[] = '
	          <div class="btn-group">
	            <a class="btn btn-primary" href="' . base_url('prodi/ubah/' . $row->id) . '" data-toggle="tooltip" title="Ubah"><i class="fa fa-edit"></i></a>
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