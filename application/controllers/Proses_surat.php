<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proses_surat extends CI_Controller {
	function __construct(){
		parent::__construct();
		
		$this->pustaka->auth($this->session->level, [1, 2, 3, 4, 5]);
	}

	function index() {
		$data['isi'] = 'proses_surat/index';
		$data['js'] = 'proses_surat/index_js';

		$this->load->view('template/template', $data);
	}

	function tambah() {
		$data['isi'] = 'proses_surat/tambah';
		$data['js'] = 'proses_surat/tambah_js';

		$this->load->view('template/template', $data);
	}

	function ubah($id) {
		$data['isi'] = 'proses_surat/ubah';
		$data['js'] = 'proses_surat/ubah_js';
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

		move_uploaded_file($berkas['tmp_name'], 'uploads/proses_surat/' . $this->db->insert_id());

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

			move_uploaded_file($berkas['tmp_name'], 'uploads/proses_surat/' . $where['id']);
		}

		$this->db->update('surat', $data, $where);

		redirect(base_url('surat'));
	}
	
	function aksi_hapus($id) {
		$this->db->delete('surat', ['id' => $id]);

		unlink('uploads/proses_surat/' . $id);

		redirect(base_url('surat'));
	}

	function unduh($id) {
		$surat = $this->db->get_where('surat', ['id' => $id])->row();

		if ($surat->waktu_baca == null) {
			$this->db->update('surat', ['waktu_baca' => date('Y-m-d H:i:s')], ['id' => $id]);
		}

		$fileDownload = \Apfelbox\FileDownload\FileDownload::createFromFilePath("uploads/surat/" . $id);
		$fileDownload->sendDownload($surat->nama_file);
	}

	function aksi_disposisi() {
		$surat = $this->db->get_where('surat', ['id' => $this->input->post('id')])->row();

		if ($surat->waktu_baca == null) {
			$this->db->update('surat', ['waktu_baca' => date('Y-m-d H:i:s')], ['id' => $this->input->post('id')]);
		}

		$this->db->update('surat', ['status' => $this->input->post('status')], ['id' => $this->input->post('id')]);

		$this->db->insert('disposisi', ['surat_id' => $this->input->post('id'), 'bidang_id' => $this->input->post('bidang_id') == 'null' ? null : $this->input->post('bidang_id')]);
	}

	function ajax_bidang() {
		$list = [];

		$list[0]['id'] = 'null';
        $list[0]['text'] = "Sekretaris";

		$i = 1;

		$this->db->like('bidang', $this->input->get('search'));
		foreach ($this->db->get('bidang')->result() as $item) {
		 	$list[$i]['id'] = $item->id;
	        $list[$i]['text'] = $item->bidang;

	        $i++;
		 }

		 echo json_encode($list);
	}

	function ajax_kadis_belum(){
	    $requestData = $_REQUEST;
	    $columns = ['tanggal', 'nosurat', 'perihal', 'pengirim'];

	      $row = $this->db->query("SELECT count(*) total_data 
	        FROM surat
	        WHERE status IS NULL", [$this->session->id])->row();

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
	        WHERE status IS NULL
	        AND (perihal like ?
	        	        OR nosurat like ?
	        	        OR pengirim like ?)", $cari)->row();

	        $totalFiltered = $row->total_data; 

	      $query = $this->db->query("SELECT *
	        FROM surat
	        WHERE status IS NULL
	        AND (perihal like ?
	        	        OR nosurat like ?
	        	        OR pengirim like ?)
	        ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length'], $cari);
	            
	    } else {  

	      $query = $this->db->query("SELECT *
	        FROM surat
	        WHERE status IS NULL
	        ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length'], [$this->session->id]);
	            
	    }

	    foreach ($query->result() as $row) { 
	      $nestedData=[]; 
	      $id = $row->id;
	      $nestedData[] = $this->pustaka->tanggal_indo($row->tanggal);
	      $nestedData[] = $row->nosurat;
	      $nestedData[] = $row->pengirim;
	      $nestedData[] = $row->perihal;
	      $href = base_url('proses_surat/unduh/' . $id);
	      $nestedData[] = "<a href='" . $href . "'>" . $row->nama_file . "</a>";
	      $nestedData[] = '
	<select id="bidang'.$id.'" class="bidang">
	</select>
	<button class="btn-group btn btn-success" data-toggle="tooltip" title="Disposisi" onclick="disposisi('.$id.', 1)"><i class="fa fa-check"></i></button>
	<button class="btn-group btn btn-danger" data-toggle="tooltip" title="Tolak" onclick="disposisi('.$id.', 0)"><i class="fa fa-close"></i></button>
';

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

	  function ajax_kadis_sudah(){
	    $requestData = $_REQUEST;
	    $columns = ['tanggal', 'nosurat', 'perihal', 'pengirim'];

	      $row = $this->db->query("SELECT count(*) total_data 
	        FROM surat
	        WHERE status IS NOT NULL", [$this->session->id])->row();

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
	        WHERE status IS NOT NULL
	        AND (perihal like ?
	        	        OR nosurat like ?
	        	        OR pengirim like ?)", $cari)->row();

	        $totalFiltered = $row->total_data; 

	      $query = $this->db->query("SELECT *
	        FROM surat
	        WHERE status IS NOT NULL
	        AND (perihal like ?
	        	        OR nosurat like ?
	        	        OR pengirim like ?)
	        ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length'], $cari);
	            
	    } else {  

	      $query = $this->db->query("SELECT *
	        FROM surat
	        WHERE status IS NOT NULL
	        ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length'], [$this->session->id]);
	            
	    }

	    foreach ($query->result() as $row) { 
	      $nestedData=[]; 
	      $id = $row->id;
	      $nestedData[] = $this->pustaka->tanggal_indo($row->tanggal);
	      $nestedData[] = $row->nosurat;
	      $nestedData[] = $row->pengirim;
	      $nestedData[] = $row->perihal;
	      $href = base_url('proses_surat/unduh/' . $id);
	      $nestedData[] = "<a href='" . $href . "'>" . $row->nama_file . "</a>";
	      $nestedData[] = '
	          <div class="btn-group">
	            <a class="btn btn-primary" href="' . base_url('proses_surat/ubah/' . $row->id) . '" data-toggle="tooltip" title="Ubah"><i class="fa fa-edit"></i></a>
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