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
		$berkas = $_FILES['berkas'];

		$data['nama_file'] = $berkas['name'];

		foreach ($this->input->post('data') as $key => $value) {
			switch ($key) {	
				case 'tanggal':
					$tanggal = explode('-', $value);
					$data[$key] = $tanggal[2] . '-' . $tanggal[1] . '-' . $tanggal[0];
					break;
				default:
					$data[$key] = $value;
					break;
			}
		}
		$data['waktu_masuk'] = date('Y-m-d H:i:s');

		$this->db->insert('surat', $data);

		move_uploaded_file($berkas['tmp_name'], 'uploads/surat/' . $this->db->insert_id());

		redirect(base_url('surat'));
	}

	function aksi_ubah() {
		$berkas = $_FILES['berkas'];

		foreach ($this->input->post('data') as $key => $value) {
			switch ($key) {	
				case 'tanggal':
					$tanggal = explode('-', $value);
					$data[$key] = $tanggal[2] . '-' . $tanggal[1] . '-' . $tanggal[0];
					break;
				default:
					$data[$key] = $value;
					break;
			}	
		}

		foreach ($this->input->post('where') as $key => $value) {
			$where[$key] = $value;
		}

		if ($berkas['size'] > 0) {
			$data['nama_file'] = $berkas['name'];

			move_uploaded_file($berkas['tmp_name'], 'uploads/surat/' . $where['id']);
		}

		$this->db->update('surat', $data, $where);

		redirect(base_url('surat'));
	}
	
	function aksi_hapus($id) {
		$this->db->delete('surat', ['id' => $id]);

		unlink('uploads/surat/' . $id);

		redirect(base_url('surat'));
	}

	function unduh($id) {
		$fileDownload = \Apfelbox\FileDownload\FileDownload::createFromFilePath("uploads/surat/" . $id);
		$fileDownload->sendDownload($this->db->get_where('surat', ['id' => $id])->row()->nama_file);
	}

	function ajax(){
	    $requestData = $_REQUEST;
	    $columns = ['tanggal', 'nosurat', 'perihal', 'pengirim'];

	      $row = $this->db->query("SELECT count(*) total_data 
	        FROM surat", [$this->session->id])->row();

	        $totalData = $row->total_data;
	        $totalFiltered = $totalData; 

	    $data = [];

	    if( !empty($requestData['search']['value']) ) {
	      $search_value = "%" . $requestData['search']['value'] . "%";

		    $cari = [];

	  	    for ($i=1; $i <= 3; $i++) { 
		    	$cari[] = $search_value;
		    }

	      $row = $this->db->query("SELECT count(*) total_data 
	        FROM surat
	        WHERE perihal like ?
	        OR nosurat like ?
	        OR pengirim like ?", $cari)->row();

	        $totalFiltered = $row->total_data; 

	      $query = $this->db->query("SELECT *
	        FROM surat
	        WHERE perihal like ?
	        OR nosurat like ?
	        OR pengirim like ?
	        ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length'], $cari);
	            
	    } else {  

	      $query = $this->db->query("SELECT *
	        FROM surat
	        ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length'], [$this->session->id]);
	            
	    }

	    foreach ($query->result() as $row) { 
	      $nestedData=[]; 
	      $id = $row->id;
	      $nestedData[] = $this->pustaka->tanggal_indo($row->tanggal);
	      $nestedData[] = $row->nosurat;
	      $nestedData[] = $row->pengirim;
	      $nestedData[] = $row->perihal;
	      $href = base_url('surat/unduh/' . $id);
	      $nestedData[] = "<a href='" . $href . "'>" . $row->nama_file . "</a>";
	      $nestedData[] = '
	          <div class="btn-group">
	            <a class="btn btn-primary" href="' . base_url('surat/ubah/' . $row->id) . '" data-toggle="tooltip" title="Ubah"><i class="fa fa-edit"></i></a>
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