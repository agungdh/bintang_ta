<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Substandar extends CI_Controller {
	function __construct(){
		parent::__construct();

		$this->pustaka->auth($this->session->level, [1, 2, 3]);
	}

	function index($standar_id) {
		$data['isi'] = 'substandar/index';
		$data['js'] = 'substandar/index_js';
		$data['data']['standar'] = $this->db->get_where('standar', ['id' => $standar_id])->row();
		$data['data']['versi'] = $this->db->get_where('versi', ['id' => $data['data']['standar']->versi_id])->row();

		$this->load->view('template/template', $data);
	}

	function tambah($standar_id) {
		$data['isi'] = 'substandar/tambah';
		$data['js'] = 'substandar/tambah_js';
		$data['data']['standar'] = $this->db->get_where('standar', ['id' => $standar_id])->row();
		$data['data']['versi'] = $this->db->get_where('versi', ['id' => $data['data']['standar']->versi_id])->row();

		$this->load->view('template/template', $data);
	}

	function ubah($id) {
		$data['isi'] = 'substandar/ubah';
		$data['js'] = 'substandar/ubah_js';
		$data['data']['substandar'] = $this->db->get_where('substandar', ['id' => $id])->row();
		$data['data']['standar'] = $this->db->get_where('standar', ['id' => $data['data']['substandar']->standar_id])->row();
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

		$standar = $this->db->get_where('standar', ['id' => $data['standar_id']])->row();
		$data['nomor'] = $standar->nomor . '.' . $data['nomor'];
		var_dump($data); die;
		$this->db->insert('substandar', $data);

		redirect(base_url('substandar/index/' . $data['standar_id']));
	}

	function aksi_ubah() {
		foreach ($this->input->post('data') as $key => $value) {
			$data[$key] = $value;
		}

		foreach ($this->input->post('where') as $key => $value) {
			$where[$key] = $value;
		}

		$this->db->update('substandar', $data, $where);

		redirect(base_url('substandar/index/' . $data['standar_id']));
	}

	function aksi_hapus($id) {
		$this->db->delete('substandar', ['id' => $id]);
	}

	function ajax($standar_id){
	    $requestData = $_REQUEST;
	    $columns = ['nomor', 'nama'];

	      $row = $this->db->query("SELECT count(*) total_data 
	        FROM substandar
	        WHERE standar_id = ?", [$standar_id])->row();

	        $totalData = $row->total_data;
	        $totalFiltered = $totalData; 

	    $data = [];

	    if( !empty($requestData['search']['value']) ) {
	      $search_value = "%" . $requestData['search']['value'] . "%";

		    $cari = [];

		    $cari[] = $standar_id;

	  	    for ($i=1; $i <= 2; $i++) { 
		    	$cari[] = $search_value;
		    }

	      $row = $this->db->query("SELECT count(*) total_data 
	        FROM substandar
	        WHERE standar_id = ?
	        AND (nama LIKE ? OR nomor like ?)", $cari)->row();

	        $totalFiltered = $row->total_data; 

	      $query = $this->db->query("SELECT *
	        FROM substandar
	        WHERE standar_id = ?
	        AND (nama LIKE ? OR nomor like ?)
	        ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length'], $cari);
	            
	    } else {  

	      $query = $this->db->query("SELECT *
	        FROM substandar
	        WHERE standar_id = ?
	        ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length'], [$standar_id]);
	            
	    }

	    foreach ($query->result() as $row) { 
	      $nestedData=[]; 
	      $id = $row->id;
	      $nestedData[] = $row->nomor;
	      $nestedData[] = $row->nama;
	      $nestedData[] = '
	          <div class="btn-group">
	            <a class="btn btn-primary" href="' . base_url('butir/index/' . $row->id) . '" data-toggle="tooltip" title="Butir"><i class="fa fa-share"></i></a>
	            <a class="btn btn-primary" href="' . base_url('substandar/ubah/' . $row->id) . '" data-toggle="tooltip" title="Ubah"><i class="fa fa-edit"></i></a>
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