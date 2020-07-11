<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<table class="table" width="100">
	<thead>
		<tr>
			<th>TANGGAL</th>
			<th>WAKTU</th>
			<th>C/N UNIT</th>	
			<th>OPERATOR</th>	
			<th>NRP</th>	
			<th>SHIFT</th>	
			<th>HARI KE</th>	
			<th>HM START</th>	
			<th>HM STOP</th>	
			<th>TOTAL HM</th>	
			<th>TOTAL LOADING TIME</th>	
			<th>TOTAL RITASE</th>	
			<th>TOTAL MUATAN</th>	
			<th>PRODUCTIVITY ALL</th>	
			<th>PRODUCTIVITY ACT</th>	
			<th>EFF. UNIT</th>	
			<th>VALIDATION</th>	
			<th>V. TIME</th>	
			<th>V. PIC</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($data as $key) {
		?>
		<tr>
			<td><?=$key->tanggal;?></td>
			<td><?=$key->waktu;?></td>
			<td><?=$key->no_unit;?></td>
			<td><?=$key->operator?></td>
			<td><?=$key->nrp;?></td>
			<td><?=$key->shift?></td>
			<td><?=$key->days_of?></td>
			<td><?=$key->hm_start;?></td>
			<td><?=$key->hm_stop;?></td>
			<td><?=$key->hm_start + $key->hm_stop;?></td>
			<td><?=$key->activity_time;?></td>
			<td><?=$key->ritase_sekarang + $key->ritase_sebelum;?></td>
			<td><?=$key->muatan;?></td>
			<td><?=$key->all_productivity_unit;?></td>
			<td><?=$key->activity_productivity_unit;?></td>
			<td><?=$key->effectivness."%";?></td>
			<?php 
				$validation = $key->validation;
				$vtime = ''; $vpic = '';
				if($key->validation!==null||$key->validation!==""){
					$where = "id = '".$key->validation."'";
					$validationData = $this->db->get_where("validation_data", $where, 1)->row();
					$validation = $validationData->comment;
					$vtime = $validationData->vtime;
					$vpic = $this->db->get_where("detail_user", "id = " . $validationData->vpic, 1)->row()->nama;
			} ?>
			<td><?=$validation;?></td>
			<td><?=$vtime;?></td>
			<td><?=$vpic;?></td>
		</tr>
		<?php
		}
		?>
	</tbody>
</table>
</body>
</html>