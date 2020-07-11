<!DOCTYPE html>
<html>
<head>
	<title>Excel All Input</title>
	<style type="text/css">
		body{
			font-family: serif;
		}
		table{
			margin: 20px auto;
			border-collapse: collapse;
		}
		table th,
		table td{
			border: 1px solid #3c3c3c;
			padding: 3px 8px;
	 
		}
	</style>
</head>
<body>
<table class="table table-bordered table-condensed">
	<thead>
		<tr>
			<th>TANGGAL</th>	
			<th>WAKTU</th>	
			<th>UNIT</th>	
			<th>OPERATOR</th>	
			<th>NRP</th>	
			<th>SHIFT</th>	
			<th nowrap="">HARI KE</th>	
			<th>SEGMEN</th>	
			<th>KETERANGAN</th>	
			<th nowrap="">TIMER START</th> 	
			<th nowrap="">TIMER STOP</th>	
			<th>DURASI</th>	
			<th>RITASE</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$urutan = 0;
		foreach ($data as $result) {
			$segmen[$urutan] = $result->segmen;
			$durasi = date_create("00:00:00");
			$timer_start[$urutan] = $result->timer_start;
			$constStartTime = date_create($result->timer_start);
			$start_time[$urutan] = date_format($constStartTime, "H:i:s");
			$hasil = "";
			$timer_stop = "";
			$waktu[$urutan] = $result->waktu;
			if($urutan>0){
				$time1 = new DateTime($waktu[$urutan]);
				$time2 = new DateTime($waktu[$urutan-1]);
				$diffTime = $time2->diff($time1);
				$jamTime = $diffTime->format('%h');
				$menitTime = $diffTime->format('%i');
				$detikTime = $diffTime->format('%m');
				// $timer_stop = $waktu[$urutan] - $waktu[$urutan-1];
				if($jamTime >= 0 && $jamTime <= 9){
					$jamTime = "0" . $jamTime;
				}

				if($menitTime >= 0 && $menitTime <= 9){
					$menitTime = "0" . $menitTime;
				}

				if($detikTime >= 0 && $detikTime <= 9){
					$detikTime = "0" . $detikTime;
				}
				$timer_stop = $jamTime . ":" . $menitTime . ":" . $detikTime;
				if($segmen[$urutan-1]!=="Engine On"){
					$date = new DateTime($result->timer_start);
					$date2 = new DateTime($timer_start[$urutan-1]);
					$diff = $date2->diff($date);
					$jam = $diff->format('%h');
					$menit = $diff->format('%i');
					$detik = $diff->format('%s');
					 
					if($jam >= 0 && $jam <= 9){
					 $jam = "0".$jam;
					}
					 if($menit >= 0 && $menit <= 9){
					   $menit = "0".$menit;
					 }
					if($detik >=0 && $detik <=9){
					 $detik = "0".$detik;
					}
					 
					$hasil = $jam.":".$menit.":".$detik;
					// $durasi = date_diff($start_time[$urutan], $start_time[$urutan-1]);
					// $durasi = date_create($durasi);
				}
			}
			$newDurasi = date_format($durasi, "H:i:s");
			if($result->segmen=="Muatan"){
				$muatan[$urutan] = $result->muatan;
				if($muatan[$urutan-1]){
					$muatan[$urutan] -= $muatan[$urutan-1];
				}
			}
		?>
		<tr>
			<td nowrap=""><?=$result->tanggal;?></td>
			<td nowrap=""><?=$result->waktu;?></td>
			<td nowrap=""><?=$result->no_unit;?></td>
			<td nowrap=""><?=$result->operator;?></td>
			<td nowrap=""><?=$result->nrp;?></td>
			<td nowrap=""><?=$result->shift;?></td>
			<td nowrap=""><?=$result->days_of;?></td>
			<td nowrap=""><?=$result->segmen;?></td>
			<td nowrap=""><?=($result->segmen==="Muatan") ? $muatan[$urutan] : $result->keterangan;?></td>
			<td nowrap=""><?=$result->timer_start;?></td>
			<td nowrap=""><?=$timer_stop;?></td>
			<td nowrap=""><?=$hasil;?></td>
			<td nowrap=""><?=$result->ritase_sekarang;?></td>
		</tr>
		<?php
		$urutan++;
		}
		?>
	</tbody>
</table>
</body>
</html>