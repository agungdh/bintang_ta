<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faker extends CI_Controller {
	function __construct(){
		parent::__construct();
		
		$this->pustaka->auth($this->session->level, [1]);
	}

	function tambah_mahasiswa($jumlah = 10000) {
		$faker = Faker\Factory::create('id_ID');

		$data = [];

		$i = 0;
		foreach (range(1, $jumlah) as $item) {
			$data[$i]['nama'] = $faker->name;
			$data[$i]['prodi_id'] = $faker->numberBetween($min = 3, $max = 6);
			$data[$i]['nip'] = $faker->nik();

			$i++;
		}

		// var_dump($data);

		$this->db->insert_batch('mahasiswa', $data);
	}

	public function test()
	{
		$faker = Faker\Factory::create('id_ID');

		echo $faker->name;
	}

}