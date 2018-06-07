<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Standar extends CI_Controller {
	function __construct(){
		parent::__construct();

		$this->pustaka->auth($this->session->level, [1, 2, 3]);
	}

	function index($versi_id) {
		$data['isi'] = 'standar/index';
		$data['js'] = 'standar/index_js';
		$data['data']['versi'] = $this->db->get_where('versi', ['id' => $versi_id])->row();

		$this->load->view('template/template', $data);
	}

	function tambah($versi_id) {
		$data['isi'] = 'standar/tambah';
		$data['js'] = 'standar/tambah_js';
		$data['data']['versi'] = $this->db->get_where('versi', ['id' => $versi_id])->row();

		$this->load->view('template/template', $data);
	}

	function ubah($id) {
		$data['isi'] = 'standar/ubah';
		$data['js'] = 'standar/ubah_js';
		$data['data']['standar'] = $this->db->get_where('standar', ['id' => $id])->row();
		$data['data']['versi'] = $this->db->get_where('versi', ['id' => $data['data']['standar']->versi_id])->row();

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

		$this->db->insert('standar', $data);

		redirect(base_url('standar/index/' . $data['versi_id']));
	}

	function aksi_ubah() {
		foreach ($this->input->post('data') as $key => $value) {
			$data[$key] = $value;
		}

		foreach ($this->input->post('where') as $key => $value) {
			$where[$key] = $value;
		}

		$this->db->update('standar', $data, $where);

		redirect(base_url('standar/index/' . $data['versi_id']));
	}

	function aksi_hapus($id) {
		$this->db->delete('standar', ['id' => $id]);
	}

	function ajax($versi_id){
	    $requestData = $_REQUEST;
	    $columns = ['nomor', 'nama'];

	      $row = $this->db->query("SELECT count(*) total_data 
	        FROM standar
	        WHERE versi_id = ?", [$versi_id])->row();

	        $totalData = $row->total_data;
	        $totalFiltered = $totalData; 

	    $data = [];

	    if( !empty($requestData['search']['value']) ) {
	      $search_value = "%" . $requestData['search']['value'] . "%";

		    $cari = [];

		    $cari[] = $versi_id;

	  	    for ($i=1; $i <= 2; $i++) { 
		    	$cari[] = $search_value;
		    }

	      $row = $this->db->query("SELECT count(*) total_data 
	        FROM standar
	        WHERE versi_id = ?
	        AND (nama LIKE ? OR nomor like ?)", $cari)->row();

	        $totalFiltered = $row->total_data; 

	      $query = $this->db->query("SELECT *
	        FROM standar
	        WHERE versi_id = ?
	        AND (nama LIKE ? OR nomor like ?)
	        ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length'], $cari);
	            
	    } else {  

	      $query = $this->db->query("SELECT *
	        FROM standar
	        WHERE versi_id = ?
	        ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length'], [$versi_id]);
	            
	    }

	    foreach ($query->result() as $row) { 
	      $nestedData=[]; 
	      $id = $row->id;
	      $nestedData[] = $row->nomor;
	      $nestedData[] = $row->nama;
	      $nestedData[] = '
	          <div class="btn-group">
	            <a class="btn btn-primary" href="' . base_url('substandar/index/' . $row->id) . '" data-toggle="tooltip" title="Substandar"><i class="fa fa-share"></i></a>
	            <a class="btn btn-primary" href="' . base_url('standar/ubah/' . $row->id) . '" data-toggle="tooltip" title="Ubah"><i class="fa fa-edit"></i></a>
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