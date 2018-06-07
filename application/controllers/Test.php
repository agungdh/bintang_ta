<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {
	function __construct(){
		parent::__construct();
		
		$this->pustaka->auth($this->session->level, [1]);
	}

	function index() {
		?>
		<!DOCTYPE html>
		<html>
		<head>
			<title></title>
		</head>
		<body>
			<table border="1">
				<thead>
					<tr>
						<th>Standar</th>
						<th>Substandar</th>
						<th>Butir</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$standar = $this->db->get_where('standar', ['versi_id' => 1])->result();
					foreach ($standar as $item_standar) {
						$substandar = $this->db->get_where('substandar', ['standar_id' => $item_standar->id])->result();
						foreach ($substandar as $item_substandar) {
							$butir = $this->db->get_where('butir', ['substandar_id' => $item_substandar->id])->result();
							if ($butir != null) {
								foreach ($butir as $item_butir) {
									?>
									<tr>
										<td><?php echo $item_standar->nomor . ' ' . $item_standar->nama; ?></td>
										<td><?php echo $item_substandar->nomor . ' ' . $item_substandar->nama; ?></td>
										<td><?php echo $item_butir->nomor . ' ' . $item_butir->nama; ?></td>
									</tr>
									<?php
								}
							} else {
								?>
								<tr>
									<td><?php echo $item_standar->nomor . ' ' . $item_standar->nama; ?></td>
									<td><?php echo $item_substandar->nomor . ' ' . $item_substandar->nama; ?></td>
									<td></td>
								</tr>
								<?php
							}
						}
					}
					?>			
				</tbody>
			</table>		
		</body>
		</html>
		<?php
	}

}