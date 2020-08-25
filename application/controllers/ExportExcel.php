<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * 
 */

class ExportExcel extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Bangkok");
		header('Access-Control-Allow-Origin: *');
		$this->load->model("UnitModel", "unit");
	}

	public function index()
	{
	}

	public function excelV($tanggal)
	{
		// use PhpOffice\PhpSpreadsheet\Spreadsheet;
		// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
		$filter = "DATE(a.created_date) = '$tanggal' AND a.segmen != '' ";
		$result = array();
		$statusHeader = 200;
		$fileTitle = "ExcelHourly-" . str_replace("-", "", $tanggal);
		try {
			$group = "b.no_unit";
			$data = $this->unit->getDataToExcelAllInput($filter);

			if ($data->num_rows() > 0) {
				$result['data'] = $data->result();
				$result['response'] = "success";
				$result['title'] = $fileTitle;
				$statusHeader = 200;
				$spreadsheet = new Spreadsheet();
				$sheet1 = $spreadsheet->getActiveSheet()->setTitle('Database All Input');
				$table_columns = array("TANGGAL", "WAKTU", "UNIT", "OPERATOR", "NRP", "SHIFT", "HARI KE", "SEGMEN", "KETERANGAN", "TIMER START", "TIMER STOP", "DURASI", "RITASE");
				$column = 1;

				foreach ($table_columns as $field) {
					$sheet1->setCellValueByColumnAndRow($column, 1, $field);
					$column++;
				}

				$dataResponse = $data->result();
				$excel_row = 2;
				$urutan = 0;
				$indexMuatan = 0;
				foreach ($dataResponse as $key) {
					$segmen[$urutan] = $key->segmen;
					$durasi = date_create("00:00:00");
					$timer_start[$urutan] = $key->timer_start;
					$constStartTime = date_create($key->timer_start);
					$start_time[$urutan] = date_format($constStartTime, "H:i:s");
					$hasil = "";
					$timer_stop = "";
					$waktu[$urutan] = $key->waktu;
					if ($urutan > 0) {
						$time1 = new DateTime($waktu[$urutan]);
						$time2 = new DateTime($waktu[$urutan - 1]);
						$diffTime = $time2->diff($time1);
						$jamTime = $diffTime->format('%h');
						$menitTime = $diffTime->format('%i');
						$detikTime = $diffTime->format('%m');
						// $timer_stop = $waktu[$urutan] - $waktu[$urutan-1];
						if ($jamTime >= 0 && $jamTime <= 9) {
							$jamTime = "0" . $jamTime;
						}

						if ($menitTime >= 0 && $menitTime <= 9) {
							$menitTime = "0" . $menitTime;
						}

						if ($detikTime >= 0 && $detikTime <= 9) {
							$detikTime = "0" . $detikTime;
						}
						$timer_stop = $jamTime . ":" . $menitTime . ":" . $detikTime;
						if ($segmen[$urutan - 1] !== "Engine On") {
							$date = new DateTime($key->timer_start);
							$date2 = new DateTime($timer_start[$urutan - 1]);
							$diff = $date2->diff($date);
							$jam = $diff->format('%h');
							$menit = $diff->format('%i');
							$detik = $diff->format('%s');

							if ($jam >= 0 && $jam <= 9) {
								$jam = "0" . $jam;
							}
							if ($menit >= 0 && $menit <= 9) {
								$menit = "0" . $menit;
							}
							if ($detik >= 0 && $detik <= 9) {
								$detik = "0" . $detik;
							}

							$hasil = $jam . ":" . $menit . ":" . $detik;
							// $durasi = date_diff($start_time[$urutan], $start_time[$urutan-1]);
							// $durasi = date_create($durasi);
						}
					}
					$newDurasi = date_format($durasi, "H:i:s");
					// if($key->segmen=="Muatan"){
					// 	$muatan[$indexMuatan] = $key->muatan;
					// 	if($muatan[$indexMuatan-1]){
					// 		$muatan[$indexMuatan] -= $muatan[$indexMuatan-1];
					// 	}
					// 	$indexMuatan++;
					// }
					\PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());
					$keterangan = $key->keterangan;
					$sheet1->setCellValue("A$excel_row", $key->tanggal);
					$spreadsheet->getActiveSheet()->getStyle("A$excel_row")->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
					$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

					$sheet1->setCellValue("B$excel_row", $key->waktu);
					$spreadsheet->getActiveSheet()->getStyle("B$excel_row")->getNumberFormat()->setFormatCode('H:MM:SS');
					$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

					$sheet1->setCellValue("C$excel_row", $key->no_unit);
					$sheet1->setCellValue("D$excel_row", $key->operator);
					$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

					$sheet1->setCellValue("E$excel_row", $key->nrp);
					$sheet1->setCellValue("F$excel_row", $key->shift);
					$sheet1->setCellValue("G$excel_row", $key->days_of);
					$sheet1->setCellValue("H$excel_row", $key->segmen);
					$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

					$sheet1->setCellValue("I$excel_row", $keterangan);
					$spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);

					$sheet1->setCellValue("J$excel_row", $key->timer_start);
					$spreadsheet->getActiveSheet()->getStyle("J$excel_row")->getNumberFormat()->setFormatCode('HH:MM:SS');

					$sheet1->setCellValue("K$excel_row", $timer_stop);
					$spreadsheet->getActiveSheet()->getStyle("K$excel_row")->getNumberFormat()->setFormatCode('HH:MM:SS');

					$sheet1->setCellValue("L$excel_row", "=K$excel_row - J$excel_row");
					$spreadsheet->getActiveSheet()->getStyle("L$excel_row")->getNumberFormat()->setFormatCode('HH:MM:SS');
					$sheet1->setCellValue("M$excel_row", $key->ritase_sekarang);
					$excel_row++;
					$urutan++;
				}

				// SHEET 2
				$worksheet2 = $spreadsheet->createSheet();
				$worksheet2->setTitle('Database Hourly');
				$table_columns = array("TANGGAL", "WAKTU", "C/N UNIT", "OPERATOR", "NRP", "SHIFT", "HARI KE", "HM START", "HM STOP", "TOTAL HM", "TOTAL LOADING TIME", "TOTAL RITASE", "TOTAL MUATAN", "PRODUCTIVITY ALL", "PRODUCTIVITY ACT", "EFF. UNIT", "VALIDATION", "V. TIME", "V. PIC");
				$column = 1;

				foreach ($table_columns as $field) {
					$worksheet2->setCellValueByColumnAndRow($column, 1, $field);
					$column++;
				}
				$data = $this->unit->getDataToExcelAllInput($filter);
				$dataResponse = $data->result();
				$excel_row = 2;
				foreach ($dataResponse as $key) {
					$worksheet2->setCellValueByColumnAndRow(1, $excel_row, $key->tanggal);
					$spreadsheet->getActiveSheet()->getStyle("A$excel_row")->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
					$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

					$worksheet2->setCellValueByColumnAndRow(2, $excel_row, $key->waktu);
					$spreadsheet->getActiveSheet()->getStyle("B$excel_row")->getNumberFormat()->setFormatCode('H:MM:SS');
					$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

					$worksheet2->setCellValueByColumnAndRow(3, $excel_row, $key->no_unit);
					$worksheet2->setCellValueByColumnAndRow(4, $excel_row, $key->operator);
					$worksheet2->setCellValueByColumnAndRow(5, $excel_row, $key->nrp);
					$worksheet2->setCellValueByColumnAndRow(6, $excel_row, $key->shift);
					$worksheet2->setCellValueByColumnAndRow(7, $excel_row, $key->days_of);
					$worksheet2->setCellValueByColumnAndRow(8, $excel_row, $key->hm_start);
					$worksheet2->setCellValueByColumnAndRow(9, $excel_row, $key->hm_stop);
					$worksheet2->setCellValueByColumnAndRow(10, $excel_row, "=H$excel_row+I$excel_row");
					$worksheet2->setCellValueByColumnAndRow(11, $excel_row, $key->activity_time);

					$worksheet2->setCellValueByColumnAndRow(12, $excel_row, $key->ritase_sekarang + $key->ritase_sebelum);
					$worksheet2->setCellValueByColumnAndRow(13, $excel_row, $key->muatan);
					$worksheet2->setCellValueByColumnAndRow(14, $excel_row, $key->all_productivity_unit);
					$worksheet2->setCellValueByColumnAndRow(15, $excel_row, $key->activity_productivity_unit);
					$worksheet2->setCellValueByColumnAndRow(16, $excel_row, $key->effectivness . "%");

					$validation = '-';
					$vtime = '-';
					$vpic = '-';
					if ($key->validation !== null || $key->validation !== "" || !empty($key->validation)) {
						$where = "id = '" . $key->validation . "'";
						$validationData = $this->db->get_where("validation_data", $where, 1);
						if ($validationData->num_rows() > 0) {
							$validationData = $validationData->row();
							$validation = $validationData->comment;
							$vtime = $validationData->vtime;
							$vpic = $this->db->get_where("detail_user", "id = " . $validationData->vpic, 1)->row()->nama;
						}
					}

					$worksheet2->setCellValueByColumnAndRow(17, $excel_row, $validation);
					$worksheet2->setCellValueByColumnAndRow(18, $excel_row, $vtime);
					$spreadsheet->getActiveSheet()->getStyle("R$excel_row")->getNumberFormat()->setFormatCode('H:MM:SS');
					$spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
					$worksheet2->setCellValueByColumnAndRow(19, $excel_row, $vpic);
					$excel_row++;
				}

				$writer = new Xlsx($spreadsheet);

				$filename = 'Database ' . $tanggal;

				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
				header('Cache-Control: max-age=0');

				$writer->save('php://output');
			} else {
				$result['data'] = array();
				$result['response'] = "Not Found";
				$statusHeader = 404;
				echo "Kosong";
			}
		} catch (Exception $e) {
			$result['data'] = array();
			$result['response'] = "Error " . $e->getMessage();
			$statusHeader = 500;
			echo "Failed " . $e->getMessage();
		}
		//

	}

	public function excel($tanggal)
	{
		include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
		$filter = "DATE(a.created_date) = '$tanggal' AND a.segmen != '' ";
		$result = array();
		$statusHeader = 200;
		$fileTitle = "ExcelHourly-" . str_replace("-", "", $tanggal);
		try {
			$group = "b.no_unit";
			$data = $this->unit->getDataToExcelAllInput($filter);
			// echo $this->db->last_query();
			// exit();
			if ($data->num_rows() > 0) {
				$result['data'] = $data->result();
				$result['response'] = "success";
				$result['title'] = $fileTitle;
				$statusHeader = 200;
				$spreadsheet = new PHPExcel();
				$spreadsheet->getProperties()->setCreator('Thinkpad')
					->setLastModifiedBy('Thinkpad')
					->setTitle("Database " . $tanggal)
					->setSubject("Database")
					->setDescription("Laporan Database " . $tanggal)
					->setKeywords("Laporan");
				$sheet1 = $spreadsheet->getActiveSheet()->setTitle('Database All Input');
				$table_columns = array("TANGGAL", "WAKTU", "UNIT", "OPERATOR", "NRP", "SHIFT", "HARI KE", "SEGMEN", "KETERANGAN", "TIMER START", "TIMER STOP", "DURASI", "RITASE", "METODE", "MATERIAL", "AKTIVITAS");
				$column = 0;

				foreach ($table_columns as $field) {
					$sheet1->setCellValueByColumnAndRow($column, 1, $field);
					$column++;
				}

				$dataResponse = $data->result();
				$excel_row = 2;
				$urutan = 0;
				$indexMuatan = 0;
				foreach ($dataResponse as $key) {
					$segmen[$urutan] = $key->segmen;
					$durasi = date_create("00:00:00");
					$timer_start[$urutan] = $key->timer_start;
					$constStartTime = date_create($key->timer_start);
					$start_time[$urutan] = date_format($constStartTime, "H:i:s");
					$hasil = "";
					$timer_stop = "";
					$waktu[$urutan] = $key->waktu;
					if ($urutan > 0) {
						$time1 = new DateTime($waktu[$urutan]);
						$time2 = new DateTime($waktu[$urutan - 1]);
						$diffTime = $time2->diff($time1);
						$jamTime = $diffTime->format('%h');
						$menitTime = $diffTime->format('%i');
						$detikTime = $diffTime->format('%m');
						// $timer_stop = $waktu[$urutan] - $waktu[$urutan-1];
						if ($jamTime >= 0 && $jamTime <= 9) {
							$jamTime = "0" . $jamTime;
						}

						if ($menitTime >= 0 && $menitTime <= 9) {
							$menitTime = "0" . $menitTime;
						}

						if ($detikTime >= 0 && $detikTime <= 9) {
							$detikTime = "0" . $detikTime;
						}
						if ($segmen[$urutan - 1] !== "Engine On") {
							$date = new DateTime($key->timer_start);
							$date2 = new DateTime($timer_start[$urutan - 1]);
							$diff = $date2->diff($date);
							$jam = $diff->format('%h');
							$menit = $diff->format('%i');
							$detik = $diff->format('%s');

							if ($jam >= 0 && $jam <= 9) {
								$jam = "0" . $jam;
							}
							if ($menit >= 0 && $menit <= 9) {
								$menit = "0" . $menit;
							}
							if ($detik >= 0 && $detik <= 9) {
								$detik = "0" . $detik;
							}

							$hasil = $jam . ":" . $menit . ":" . $detik;
							// $durasi = date_diff($start_time[$urutan], $start_time[$urutan-1]);
							// $durasi = date_create($durasi);
						}
					}
					$newDurasi = date_format($durasi, "H:i:s");
					// if($key->segmen=="Muatan"){
					// 	$muatan[$indexMuatan] = $key->muatan;
					// 	if($muatan[$indexMuatan-1]){
					// 		$muatan[$indexMuatan] -= $muatan[$indexMuatan-1];
					// 	}
					// 	$indexMuatan++;
					// }
					// \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder( new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder() );
					$keterangan = $key->keterangan;
					$sheet1->setCellValue("A$excel_row", $key->tanggal);
					$sheet1->getStyle('A' . $excel_row)
						->getNumberFormat()
						->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4);
					// $spreadsheet->getActiveSheet()->getStyle("A$excel_row")->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH); 
					$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

					$sheet1->setCellValue("B$excel_row", $key->waktu);
					// $spreadsheet->getActiveSheet()->getStyle("B$excel_row")->getNumberFormat()->setFormatCode('H:MM:SS'); 
					$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

					$sheet1->setCellValue("C$excel_row", $key->no_unit);
					$sheet1->setCellValue("D$excel_row", $key->operator);
					$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

					$sheet1->setCellValue("E$excel_row", $key->nrp);
					$sheet1->setCellValue("F$excel_row", $key->shift);
					$sheet1->setCellValue("G$excel_row", $key->days_of);
					$sheet1->setCellValue("H$excel_row", $key->segmen);
					$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

					$sheet1->setCellValue("I$excel_row", $keterangan);
					$spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);

					$sheet1->setCellValue("J$excel_row", $key->timer_start);
					$spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
					$sheet1->getStyle('J' . $excel_row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME8);
					// $spreadsheet->getActiveSheet()->getStyle("J$excel_row")->getNumberFormat()->setFormatCode('HH:MM:SS');
					$excelRow_1 = $excel_row + 1;
					$sheet1->setCellValue("K$excel_row", "=J$excelRow_1");
					$spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
					$sheet1->getStyle('K' . $excel_row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME8);
					// $spreadsheet->getActiveSheet()->getStyle("K$excel_row")->getNumberFormat()->setFormatCode('HH:MM:SS');

					$sheet1->setCellValue("L$excel_row", "=K$excel_row - J$excel_row");
					$spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
					// $spreadsheet->getActiveSheet()->getStyle("L$excel_row")->getNumberFormat()->setFormatCode('HH:MM:SS');
					$sheet1->getStyle('L' . $excel_row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME8);
					$sheet1->setCellValue("M$excel_row", $key->ritase_sekarang);
					$spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);

					$this->db->reset_query();
					$metode = $this->db->select("metode")->from("metode")->where("id", $key->metode)->get();
					$vMetode = "-";
					if($metode->num_rows() > 0){
						$vMetode = $metode->row()->metode;
					}
					$sheet1->setCellValue("N$excel_row", $vMetode);

					$this->db->reset_query();
					$material = $this->db->select("jenis")->from("material")->where("kode_material", $key->material)->get();
					$vMaterial = "-";
					if($material->num_rows() > 0){
						$vMaterial = $material->row()->jenis;
					}
					$sheet1->setCellValue("O$excel_row", $vMaterial);

					$this->db->reset_query();
					$aktivitas = $this->db->select("aktivitas")->from("status")->where("id = '$key->activity_now' AND kategori = 'AKTIVITAS'")->limit(1)->get();
					$vAktivitas = "-";
					if($aktivitas->num_rows() > 0){
						$vAktivitas = $aktivitas->row()->aktivitas;
					}
					$sheet1->setCellValue("P$excel_row", $vAktivitas);

					$excel_row++;
					$urutan++;
				}

				// SHEET 2
				$worksheet2 = $spreadsheet->createSheet();
				$worksheet2->setTitle('Database Hourly');
				$table_columns = array("TANGGAL", "WAKTU", "C/N UNIT", "OPERATOR", "NRP", "SHIFT", "HARI KE", "HM START", "HM STOP", "TOTAL LOADING TIME", "TOTAL RITASE", "TOTAL MUATAN", "PRODUCTIVITY ALL", "PRODUCTIVITY ACT", "EFF. UNIT", "VALIDATION", "V. TIME", "V. PIC", "TOTAL ENGINE RUNNING");
				$column = 0;

				$excel_row = 2;
				foreach ($table_columns as $field) {
					$worksheet2->setCellValueByColumnAndRow($column, 1, $field);
					$column++;
				}
				$select = "";
				$where = "DATE(a.created_date) = '$tanggal' ";
				$group_by = "GROUP BY jam";
				$order_by = "ORDER BY jam ASC";
				$get_jam = $this->unit->customeGetPerHour($select, $where, $group_by, $order_by);
				$result = array();
				if ($get_jam->num_rows() > 0) {

					foreach ($get_jam->result() as $key) {
						$select2 = ", a.no_unit";
						$join2 = " JOIN activity_operator b ON a.id_activity_operator = b.id ";
						$where2 = " HOUR(a.jam_sekarang) = '$key->jam' AND DATE(a.created_date) = '$tanggal' ";
						$group_by2 = " GROUP BY a.no_unit ";
						$order_by2 = "ORDER BY a.no_unit ASC";
						$insideData = $this->unit->customeGetDataWithJoin($select2, $join2, $where2, $group_by2, $order_by2);
						if ($insideData->num_rows() > 0) {
							foreach ($insideData->result() as $key2) {
								$select3 = ", a.akumulatif_loading_time_per_hour as load_per_hour, a.id, 
								date(a.created_date) as tanggal, a.jam_sekarang as waktu, a.no_unit, 
								(select nama from detail_user where detail_user.id = a.created_by) as operator, 
								b.nrp as nrp, b.shift, b.days_of, a.segmen, a.muatan, a.actual_prod,b.hm_start, b.hm_stop, 
											  CASE 
											  WHEN (a.segmen='Aktifitas') THEN (SELECT aktivitas FROM status WHERE id = a.activity_now)
											  WHEN (a.segmen='Metode') THEN (SELECT metode FROM metode where id = a.metode)
											  ELSE a.keterangan
											  END AS keterangan,
											  a.jam_engine as timer_start,
											  time((a.jam_engine- a.activity_time) + a.jam_engine) as timer_stop,
											  time(a.jam_engine- a.activity_time) as durasi, a.ritase_sekarang, a.ritase_sebelum, a.activity_time, a.status_time, a.all_productivity_unit, a.activity_productivity_unit, a.effectivness, a.validation, a.jam_engine as total_engine_time";
								$unit = $key2->no_unit;
								$where3 = $where2 . " AND a.no_unit = '$key2->no_unit'";
								$group_by3 = "";
								$order_by3 = "ORDER BY a.id DESC";
								$limit3 = " LIMIT 1; ";
								$insideData2 = $this->unit->customeGetDataWithJoin($select3, $join2, $where3, $group_by3, $order_by3, $limit3);
								// echo $this->db->last_query();
								// exit();
								if ($insideData2->num_rows() > 0) {
									$result['data'][] = $insideData2->row();
									$key2 = $insideData2->row();
									$worksheet2->setCellValueByColumnAndRow(0, $excel_row, $key2->tanggal);
									// $spreadsheet->getActiveSheet()->getStyle("A$excel_row")->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH); 
									$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

									$worksheet2->setCellValueByColumnAndRow(1, $excel_row, $key2->waktu);
									// $spreadsheet->getActiveSheet()->getStyle("B$excel_row")->getNumberFormat()->setFormatCode('H:MM:SS'); 
									$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

									$worksheet2->setCellValueByColumnAndRow(2, $excel_row, $unit);
									$worksheet2->setCellValueByColumnAndRow(3, $excel_row, $key2->operator);
									$worksheet2->setCellValueByColumnAndRow(4, $excel_row, $key2->nrp);
									$worksheet2->setCellValueByColumnAndRow(5, $excel_row, $key2->shift);
									$worksheet2->setCellValueByColumnAndRow(6, $excel_row, $key2->days_of);
									$worksheet2->setCellValueByColumnAndRow(7, $excel_row, $key2->hm_start);
									$worksheet2->setCellValueByColumnAndRow(8, $excel_row, $key2->hm_stop);
									$worksheet2->setCellValueByColumnAndRow(9, $excel_row, $key2->load_per_hour);

									$worksheet2->setCellValueByColumnAndRow(10, $excel_row, $key2->ritase_sekarang + $key2->ritase_sebelum);
									$worksheet2->setCellValueByColumnAndRow(11, $excel_row, $key2->actual_prod);
									$worksheet2->setCellValueByColumnAndRow(12, $excel_row, $key2->all_productivity_unit);
									$worksheet2->setCellValueByColumnAndRow(13, $excel_row, $key2->activity_productivity_unit);
									$worksheet2->setCellValueByColumnAndRow(14, $excel_row, $key2->effectivness . "%");

									$validation = '-';
									$vtime = '-';
									$vpic = '-';
									if ($key2->validation !== null || $key2->validation !== "" || !empty($key2->validation)) {
										$where = "id_engine_operator = '" . $key2->id . "'";
										$validationData = $this->db->get_where("validation_data", $where, 1);
										if ($validationData->num_rows() > 0) {
											$validationData = $validationData->row();
											$validation = $validationData->comment;
											$vtime = strval($validationData->vtime);
											$vpic = $this->db->get_where("detail_user", "id = " . $validationData->vpic, 1)->row()->nama;
										}
									}

									$worksheet2->setCellValueByColumnAndRow(15, $excel_row, $validation);
									$worksheet2->setCellValueByColumnAndRow(16, $excel_row, $vtime);
									// $spreadsheet->getActiveSheet()->getStyle("R$excel_row")->getNumberFormat()->setFormatCode('H:MM:SS'); 
									$spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
									$worksheet2->setCellValueByColumnAndRow(17, $excel_row, $vpic);
									
									$worksheet2->setCellValueByColumnAndRow(18, $excel_row, $key2->total_engine_time);
									$spreadsheet->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
									$excel_row++;
								}
							}
						}
					}
				}

				// $writer = new Xlsx($spreadsheet);

				$filename = 'Database ' . $tanggal;

				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
				header('Cache-Control: max-age=0');

				$write = PHPExcel_IOFactory::createWriter($spreadsheet, 'Excel2007');
				$write->save('php://output');
			} else {
				$result['data'] = array();
				$result['response'] = "Not Found";
				$statusHeader = 404;
				echo "Kosong";
			}
		} catch (Exception $e) {
			$result['data'] = array();
			$result['response'] = "Error " . $e->getMessage();
			$statusHeader = 500;
			echo "Failed " . $e->getMessage();
		}
	}

	public function TryGetData($tanggal)
	{
		# code...

		$data = $result['data'];
		if (count($data) > 0) {
			$result['status'] = "success";
			$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		} else {
			$result['status'] = "failed";
			$this->output
				->set_status_header(400)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}
	}
}

