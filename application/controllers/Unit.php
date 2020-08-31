<?php
/**
 * 
 */
class Unit extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Bangkok");
		header('Access-Control-Allow-Origin: *');
		$this->load->model("UnitModel", "unit");
		$this->load->model("Engine");
		$this->load->model("Activity");
		$this->load->model("StatusModel", "Status");
	}

	public function getAll()
	{
		try {
			$data = $this->unit->getAll();
			// echo $this->db->last_query();
			if($data->num_rows() > 0){
				response(200, $data->result_array());
			}else{
				response(404, array('response'=>'Not Found'));
			}
		} catch (Exception $e) {
			response(500, array('response'=>'Server Error'));
		}

	}

	public function getAllUnitActivitiesByDateAndHour()
	{
		$tanggal = $this->input->post('tanggal');
		$jam = $this->input->post('jam');
		if ($jam===null||$jam==="") {
			// echo $newJam;
			$time = time();
			$jam =  date("H:i:s", $time);
			// echo $jam;
		}
		try {
			$filterUnit = "created_date LIKE \"$tanggal%\" AND `created_date` <= \"$tanggal $jam\"";
			$dataUnit = $this->unit->getUnitByFilter($filterUnit);
			// echo $this->db->last_query();
			if($dataUnit->num_rows() > 0){
				$allData = array();
				// echo $this->db->last_query();
				foreach ($dataUnit->result() as $unit) {
					$filter = "a.created_date LIKE \"$tanggal%\" AND a.jam_sekarang <= \"$jam\" AND a.id_activity_operator = \"$unit->id\" ";
					$data = $this->unit->getByFilter($filter);
					if($data->num_rows() > 0){
						array_push($allData, $data->row_array());
					}
				}
				if(count($allData) > 0){
					response(200, $allData);
				}else{
					response(400, array('response'=>"Data Engine Not Found"));
				}
			}else{
				response(404, array('response'=>"Filter Not Found"));
			}
		} catch (Exception $e) {
			response(500, array('response' => 'Server Exhausted'));
		}
	}

	public function getDateUnitData()
	{
		$data = $this->unit->getDateDataEngineV2();
		$result = array();
		try {
			if($data->num_rows() > 0){
				$result['data'] = $data->result_array();
				$result['response'] = "success";
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			}else{
				$result = array('response' => "Date Not Found");
				$this->output
				->set_status_header(404)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			}
		} catch (Exception $e) {
			$result = array('response' => "Server Fault");
			$this->output
			->set_status_header(500)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}
	}

	public function getTimeUnitData()
	{
		$tanggal = $this->input->post('tanggal');
		// DATE(jam_sekarang) = '2020-06-03' GROUP BY hour(jam_sekarang) ORDER BY jam_sekarang ASC
		$select = "hour(jam_sekarang), substring(time(concat_ws(\":\", hour(jam_sekarang),\"00\")), 1,5) as timeformat";
		$where = "DATE(created_date) = '$tanggal'";
		$group = "hour(jam_sekarang)";
		$order = "HOUR(jam_sekarang) DESC";
		// $filter = "DATE(created_date) = '$tanggal' GROUP BY hour(jam_sekarang) ORDER BY jam_sekarang ASC";
		$data = $this->Engine->get_where($select, $where, $order, $group);
		$result['data'] = $data->result_array();
		$result['response'] = "success";
		$this->output
		->set_status_header(200)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}

	public function getEngineUnitData()
	{
		$date = $this->input->post('tanggal');
		$jam = $this->input->post('jam');
		
		$filter = "date(created_date) = '$date' ";
		$order = "id_activity_operator DESC";
		$group = "id_activity_operator";
		$data_no_unit = $this->Engine->get_where("*", $filter, $order, $group);
		// echo $this->db->last_query();
		// var_dump($jam);
		if($jam){
			$time = explode(":", $jam);
			$time2 = $time[0];
			$filter .= "AND HOUR(jam_sekarang) = '$jam' ";
		}else{
			$time = time();
			$jam = date("H:", $time);
			// $jam = String $jam;
			$filter .= "AND HOUR(jam_sekarang) = '$jam' ";
			// echo $filter;
		}
		try {
			$array = array();
			$count = 0;
			foreach ($data_no_unit->result() as $key) {
				// var_dump($key);
				$this->db->reset_query();
				$id_activity_operator = $key->id_activity_operator;
				$filter2 = $filter."AND id_activity_operator = '$id_activity_operator'";
				$data = $this->unit->getDataUnitEngineByFilter($filter2, "act");
				// echo $this->db->last_query();
				// var_dump($data);
				if($data->num_rows() > 0){
					$data = $data->row();
					$activity = array('no_unit' => $key->no_unit,
						'status_engine' => $data->status_engine, 
						'act'=>$data->act,
						'duration' => $data->activity_time,
						'validation' => $data->validation,
						'id' => $data->id);
					$array['activity'][] = $activity;
					$count++;
				}
				$this->db->reset_query();
				$data2 = $this->unit->getDataUnitEngineByFilter($filter2, "status");
				// echo $this->db->last_query();
				if($data2->num_rows() > 0){
					$data2 = $data2->row();
					$status =  array('no_unit' => $key->no_unit,
						'status_engine' => $data->status_engine, 
						'status'=>$data->act,
						'duration' => $data->status_time,
						'validation' => $key->validation,
						'id' => $key->id);
					$array['status'][] = $status;
					$count++;
				}
				$this->db->reset_query();
				$data3 = $this->unit->getDataUnitEngineByFilter($filter2,null);
				// echo $this->db->last_query();
				if($data3->num_rows() > 0){
					$data3 = $data3->row();
					$array['data'][] = array('no_unit' => $key->no_unit,
						'nama' => $data3->nama, 
						'id' => $data3->id,
						'act_pdty' => $data3->activity_productivity_unit,
											// 'all_prod' => $data3->all_productivity_unit,
						'act_prod' => $data3->actual_prod,
						'eff' => $data3->effectivness,
						'id' => $data3->id,
						'validation' => $data->validation);
					$count++;
				}
				$array['message'] = "success";
			}
			// exit();
			if($count>0){

				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			}else{
				$this->output
				->set_status_header(404)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode(array('message'=>"Not Found", 'activity'=> null), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			}

		} catch (Exception $e) {
			$result = array('message' => "Server Fault " . $e->getMessage());
			$this->output
			->set_status_header(500)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}
	}

	public function getDetailUnitInTable()
	{
		$tanggal = $this->input->post('tanggal');
		$no_unit = $this->input->post('no_unit');
		$jam = ($this->input->post('jam')!==null) ? $this->input->post('jam') : "";
		
		$filter = "date(created_date) = '$tanggal' AND no_unit='$no_unit' ";
		$filter .= ($jam!=="") ? " AND HOUR(jam_sekarang) = '$jam' " : "";
		try {
			$data_no_unit = $this->Engine->get_where("*", $filter);
			// echo $this->db->last_query();
			// var_dump($jam);
			if($data_no_unit->num_rows() > 0):
				$id_activity_operator = $data_no_unit->row()->id_activity_operator;
				$filter2 = " id_activity_operator = '$id_activity_operator'";
				// echo $filter2;
					// if(!empty($jam)||$jam!==null||$jam==""||!$jam){
					// 	$time = explode(":", $jam);
					// 	$time2 = $time[0].":59";
					// 	$filter2 .= "AND (a.jam_sekarang BETWEEN time('$jam') AND time('$time2')) ";
					// }else{
					// 	$time = time();
					// 	$jam = date("H:", $time);
					// 	$jam .= "00";
					// 	$jam2 = date("H:", $time)."59";
					// 	// $jam = String $jam;
					// 	$filter2 .= "AND (a.jam_sekarang BETWEEN time('$jam') AND time('$jam2')) ";
					// 	// echo $filter;
					// }
				$data3 = $this->unit->getDataUnitEngineByFilter($filter2,null);
						// echo $this->db->last_query();
				if($data3->num_rows() > 0){
					$data3 = $data3->row();
					$array['data'][] = array('no_unit' => $no_unit,
						'nama' => $data3->nama, 
						'act_pdty' => $data3->activity_productivity_unit,
						'all_pdty' => $data3->all_productivity_unit,
						'all_prod' => $data3->muatan,
						'act_prod' => $data3->actual_prod,
						'eff' => $data3->effectivness,
						'jenis_material' => $data3->jenis_material,
						'jenis_metode' => $data3->jenis_metode,
						'id' => $data3->id);
					// $count++;
					$array['message'] = "success";
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
				}else{
					$array['message'] = "failed. Data Not Found";
					$this->output
					->set_status_header(404)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
				}
			else:
				$array['message'] = "failed. Unit Not Found";
				$this->output
				->set_status_header(404)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			endif;
		} catch (Exception $e) {
			$array['message'] = "Error. " . $e->getMessage();
			$this->output
			->set_status_header(500)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}
		
	}

	public function getDataByNoUnit($type)
	{
		$no_unit = $this->input->post('no_unit');
		$tanggal = $this->input->post('tanggal');
		$filter = "no_unit = '$no_unit' AND date(created_date) = '$tanggal' ";
		// if($type=="status"){
		// 	$filter .= "AND a.status_now != '0'";
		// 	$group_by = "a.status_now";
		// }
		// $filter = "a.status_now";
		$result = array();
		try {
			$dataUnit = $this->db->query("SELECT * FROM engine_operator WHERE " . $filter . " GROUP BY id_activity_operator");
			// echo $this->db->last_query();
			// echo "<br>";
			if($dataUnit->num_rows() > 0){
				$status = 200;
				$key = $dataUnit->row();
				// foreach ($dataUnit->result() as $key) {
				$this->db->reset_query();
				$filter2 = "id_activity_operator = '$key->id_activity_operator' AND date(created_date) = '$tanggal' AND segmen = 'Status'";
				$data = $this->db->query("select * from engine_operator WHERE " . $filter2);
					// echo $this->db->last_query();
				if($data->num_rows() > 0){
					$result['data'] = $data->result();
					$result['response'] = "success";
				}else{
					$status = 404;
					$result['data'] = array();
					$result['response'] = "Not Found";
				}
				// }
			}else{
				$status = 404;
				$result['data'] = array();
				$result['response'] = "Not Found";
			}
			
			$this->output
			->set_status_header($status)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));	
		} catch (Exception $e) {
			$this->output
			->set_status_header(500)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode(array('response'=>"Error"), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));	
		}	
	}

	public function getProductivityByDate()
	{
		$tanggal = $this->input->post('tanggal');
		$shift = $this->input->post('shift');
		// $filter = "date(a.created_date) = '$tanggal' AND a.shift = $shift";
		$array = array();
		$statusHeader = 200;
		try {
			// $filter2= "date(a.created_date) = '$tanggal'";
			// $dataHourly = $this->unit->getHourByUnit($filter2);
			// $group = "b.no_unit";
			// $data2 = $this->unit->getUnitByFilterV2($filter2, $group);
			
			$dataHourlyArray=array();
			$datapoints = array();
			$production = array();
			$urutan=0;
			$array['color'] = array();
			$DataUnit = $this->unit->getAll();
			foreach ($DataUnit->result() as $key => $value) {
				$hex ="#";
				$color = dechex(rand(0x000000, 0xFFFFFF));
				$hex = $hex.$color;
				$array['color'][] = $hex;
			}
			
			// $array['color']=array('red', 'blue', 'yellow', 'green', 'black', 'pink', 'brown');
			$label = array();
			$select = "HOUR(jam_sekarang) as jam";
			$order = "jam ASC";
			$group = "jam";
			$where = "date(created_date) = '$tanggal'";
			$dataHourly = $this->Engine->get_where($select, $where, $order, $group);
			if($dataHourly->num_rows() > 0){
				foreach ($dataHourly->result() as $Label) {
					$array['label'][] = $Label->jam . ":00";
				}
			}
			
			// var_dump($DataUnit->result());
			// exit();
			if($DataUnit->num_rows() > 0){
				foreach ($DataUnit->result() as $unit) {
					// $resultUnit = $unit->no_unit;
					$datapoints[$unit->kode] = array();
					$select = "HOUR(jam_sekarang) as jam";
					$where = "no_unit = '$unit->kode' AND date(created_date) = '$tanggal'";
					$order = "jam ASC";
					$group = "jam";
					$dataHourly = $this->Engine->get_where($select, $where, $order, $group);
					if($dataHourly->num_rows() > 0){
						foreach ($dataHourly->result() as $hourly) {
							$select = "muatan, jam_sekarang, date(created_date) as tanggal";
							$where = " HOUR(jam_sekarang) = '$hourly->jam' AND no_unit = '$unit->kode' AND shift = '$shift' ";
							$order = "id DESC";
							$limit = 1;
							$group = NULL;
							$dataEngineHourly = $this->Engine->get_where($select, $where, $order, $group, $limit);

							if($dataEngineHourly->num_rows() > 0){
								$engineResult = $dataEngineHourly->row();
								$datapoints[$unit->kode][] = $engineResult->muatan;
							}
							
						}
						//CONFIGURE DATASET
						$array['datasets'][] = array('label'=> $unit->kode,
							'data'=> $datapoints[$unit->kode],
							'borderColor' => $array['color'][$urutan],
							'backgroundColor'=> 'rgba(0, 0, 0, 0)',
							'fill'=>false,
							'cubicInterpolationMode'=> 'monotone');
						// $array['unit'][] = $unit->kode;
						$urutan++;
					}	
				}
				$array['response'] = 'success';
			}else{
				$statusHeader = 404;
				$array['data'] = array();
				$array['response'] = "failed";
			}
		} catch (Exception $e) {
			$statusHeader = 500;
			$arrap['data'] = array();
			$array['response'] = "Failed";
		}
		$this->output
		->set_status_header($statusHeader)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));	
	}

	public function getHorizontalChart()
	{
		$tanggal = $this->input->post('tanggal');
		$jam = $this->input->post('jam');
		$filter = "date(created_date) = '$tanggal'";
		$array = array();
		$statusHeader = 200;
		try {
			// $filter2 = "date(a.created_date) = '$tanggal'".
			// 			" AND HOUR(a.created_date) = '$jam'";
			// $group = "b.no_unit";
			$select = "no_unit";
			$where = "date(created_date) = '$tanggal'".
			" AND HOUR(jam_sekarang) = '$jam'";
			$group = "no_unit";
			$order = "no_unit ASC";
			$dataUnit = $this->Engine->get_where($select, $where, $order, $group);
			// $dataUnit = $this->unit->getUnitByFilterV2($filter2, $group);
			// echo $this->db->last_query();
			if($dataUnit->num_rows() > 0){
				$array['color']=array('red', 'blue', 'yellow', 'green', 'black', 'pink', 'brown');
				foreach ($dataUnit->result() as $key1) {
					$select2 = "(activity_productivity_unit) as production, no_unit, date(created_date) AS tanggal, hour(jam_sekarang) as jam";
					$where2 = "date(created_date) = '$tanggal'".
					" AND HOUR(jam_sekarang) = '$jam'".
					" AND no_unit = '$key1->no_unit'";
					$order2 = "id DESC";
					$group2 = NULL;
					$limit2 = 1;
					$data3= $this->Engine->get_where($select2, $where2, $order2, $group2, $limit2);
					// echo $this->db->last_query();
					// exit();
					if($data3->num_rows() > 0){
						// echo "Masuk";
						$dataResult = $data3->row();
						$statusHeader = 200;
						$datapoints = array();
						$datapoints[$dataResult->no_unit][] = $dataResult->production;
						// foreach ($data3->result() as $key) {
						// 	$datapoints[$key->no_unit][] = $key->production;
						// }

						$array['label'][] = $dataResult->no_unit;
						$array['datasets'][] = array('label'=> $dataResult->no_unit,
							'data'=> $datapoints[$dataResult->no_unit],
							'borderColor' => "rgb(33, 155, 255)",
							'backgroundColor'=> "rgb(33, 155, 255)",
							'borderWidth' => 1);
						$array['data'][$dataResult->no_unit][] = $dataResult->production;

						$array['unit'][] = $dataResult->no_unit;

						$array['response'] = "success";
					}else{
						$array['label'][] = $dataResult->no_unit;
						$array['datasets'][] = array('label'=> $dataResult->no_unit,
							'data'=> NULL,
							'borderColor' => "rgb(33, 155, 255)",
							'backgroundColor'=> "rgb(33, 155, 255)",
							'borderWidth' => 1);
						$array['data'][$dataResult->no_unit][] = $dataResult->production;

						$array['unit'][] = $dataResult->no_unit;
					}
					
				}
			}else{
				$statusHeader = 404;
				$arrap['data'] = array();
				$array['response'] = "Not Found";
			}
			
			
		} catch (Exception $e) {
			$statusHeader = 500;
			$arrap['data'] = array();
			$array['response'] = "Failed";
		}
		$this->output
		->set_status_header($statusHeader)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));	
	}

	public function getUnitByDateFilterV2()
	{
		$tanggal = $this->input->post('tanggal');
		$filter = "DATE(a.created_date) = '$tanggal'";
		$result = array();
		$statusHeader = 200;
		try {
			$select = "no_unit";
			$where = "DATE(created_date) = '$tanggal'";
			$order = "no_unit ASC";
			$group = "no_unit";
			$data = $this->Engine->get_where($select, $where, $order, $group);
			
			if($data->num_rows() > 0){
				$result['data'] = $data->result_array();
				$result['response'] = "success";
				$statusHeader = 200;
			}else{
				$result['data'] = array();
				$result['response'] = "Not Found";
				$statusHeader = 404;
			}
		} catch (Exception $e) {
			$result['data'] = array();
			$result['response'] = "Error " . $e->getMessage();
			$statusHeader = 500;
		}
		$this->output
		->set_status_header($statusHeader)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));	
	}

	public function toJSON($statusHeader, $result)
	{
		$this->output
		->set_status_header($statusHeader)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));	
	}

	public function getShiftByDate()
	{
		$tanggal = $this->input->post('date');
		try {
			$data = $this->db->select("shift")
			->from('engine_operator')
			->where("DATE(created_date) = DATE('$tanggal')")
			->group_by('shift')->get();
			if($data->num_rows() > 0){
				$result['data'] = $data->result_array();
				$result['status'] = "success";
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			}else{
				$result['data'] = array();
				$result['status'] = "failed";
				$this->output
				->set_status_header(404)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			}
		} catch (Exception $e) {
			$result['data'] = array();
			$result['status'] = "error " . $e->getMessage();
			$this->output
			->set_status_header(500)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}
		
	}

	public function getDataByNoUnitV2()
	{
		$tanggal = $this->input->post('tanggal');
		$no_unit = $this->input->post('no_unit');
		try {
			$select = "*";
			$where = "no_unit = '$no_unit' AND date(created_date) = '$tanggal' ";
			$dataActivity = $this->Activity->get_where($select, $where);
			$dataEngine = $this->Engine->get_where($select, $where);
			// echo $this->db->last_query();
			// echo "<br>";
			$result = array();
			$status = 200;
			if($dataActivity->num_rows() > 0){
				$result = $this->processActivity($dataActivity, "ACT", $tanggal);
			}elseif ($dataEngine->num_rows() > 0) {
				$result = $this->processActivity($dataEngine, "ENG", $tanggal);
			}else{
				$status = 404;
				$result['data'] = array();
				$result['response'] = "failed";
			}
			
			$this->output
			->set_status_header($status)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));	
		} catch (Exception $e) {
			$this->output
			->set_status_header(500)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode(array('response'=>"Error"), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));	
		}	
	}

	private function processActivity($resultActivity, $FROM, $tanggal)
	{
		$unit = $resultActivity->row();
		$filter2 = "";
		if($FROM=="ACT"){
			
		}elseif($FROM=="ENG"){
			// $filter2 = ""
		}
		$filter2 = "a.no_unit = '$unit->no_unit' AND date(a.created_date) = '$tanggal' ";
				// $this->db->reset_query();
		
		$order = "a.id DESC";
		$data = $this->Engine->getDataEngineActivityByFilter($filter2);
		// echo $this->db->last_query();
		// exit();
		$constructData = array();
		$loop=0;
		foreach ($data->result() as $resultData) {
			$status[$loop] = $resultData->activity_now;
			$durasi[$loop] = $resultData->durasi;
			if($loop > 0){
				if($status[$loop]!==$status[$loop-1]){
					$activity = $this->Status->get_where("*", "kategori='AKTIVITAS' AND id = '$resultData->activity_now'", NULL, NULL, 1);
					// echo $this->db->last_query();
					if($activity->num_rows() > 0){
						// var_dump($activity->row());
						$ACT = $activity->row();
						$st = $ACT->aktivitas;
						
						$constructData[] = array('jam_sekarang' => $resultData->jam_sekarang,
							'no_unit' => $resultData->no_unit,
							'status' => $st,
							'durasi' => $resultData->durasi);
					}else{
						$constructData[] = array('jam_sekarang' => $resultData->jam_sekarang,
							'no_unit' => $resultData->no_unit,
							'status' => $resultData->keterangan,
							'durasi' => $resultData->durasi);
					}
					
				}
			}elseif ($loop===0) {
				$activity = $this->db->get_where("status", "kategori='AKTIVITAS' AND id = '$resultData->activity_now'");
				// echo $this->db->last_query();
				if($activity->num_rows() > 0){
					$st = $activity->row()->aktivitas;
					
					$constructData[] = array('jam_sekarang' => $resultData->jam_sekarang,
						'no_unit' => $resultData->no_unit,
						'status' => $st,
						'durasi' => $resultData->durasi);
				}else{
					$constructData[] = array('jam_sekarang' => $resultData->jam_sekarang,
						'no_unit' => $resultData->no_unit,
						'status' => $resultData->keterangan,
						'durasi' => $resultData->durasi);
				}	
			}
			// var_dump($constructData);
			$loop++;
		}
		$status = 200;
		$result = array();
		if($data->num_rows() > 0){
			$status = 200;
			$result['data'] = $constructData;
			$result['response'] = "success";
		}else{
			$status = 404;
			$result['data'] = array();
			$result['response'] = "failed";
		}
		return $result;
	}

	public function getToExcel($tanggal)
	{
		// $tanggal = $this->input->post('tanggal');
		$filter = "DATE(a.created_date) = '$tanggal' AND a.segmen != '' ";
		$result = array();
		$statusHeader = 200;
		try {
			$group = "b.no_unit";
			$data = $this->unit->getDataToExcelAllInput($filter);
			
			if($data->num_rows() > 0){
				$result['data'] = $data->result();
				$result['response'] = "success";
				$statusHeader = 200;
			}else{
				$result['data'] = array();
				$result['response'] = "Not Found";
				$statusHeader = 404;
			}
		} catch (Exception $e) {
			$result['data'] = array();
			$result['response'] = "Error " . $e->getMessage();
			$statusHeader = 500;
		}

		$this->load->view("toExcelAllInput", $result);
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment; filename=".str_replace("-", "", $tanggal).".xls");
		// header('Content-Type: application/vnd.ms-excel'); 
		//  header('Content-disposition: attachment; filename='.str_replace("-", "", $tanggal).'.xls'); 
	}

	public function getToExcelHourly($tanggal)
	{
		$filter = "DATE(a.created_date) = '$tanggal' AND a.segmen != '' ";
		$result = array();
		$statusHeader = 200;
		$fileTitle = "ExcelHourly-" . str_replace("-", "", $tanggal);
		try {
			$group = "b.no_unit";
			$data = $this->unit->getDataToExcelAllInput($filter);
			
			if($data->num_rows() > 0){
				$result['data'] = $data->result();
				$result['response'] = "success";
				$result['title'] = $fileTitle;
				$statusHeader = 200;
			}else{
				$result['data'] = array();
				$result['response'] = "Not Found";
				$statusHeader = 404;
			}
		} catch (Exception $e) {
			$result['data'] = array();
			$result['response'] = "Error " . $e->getMessage();
			$statusHeader = 500;
		}	
		$this->load->view("toExcel", $result);
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment; filename=".$fileTitle.".xls");
	}

	public function export_excel($tanggal)
	{
		$object = new PHPExcel();

		$filter = "DATE(a.created_date) = '$tanggal' AND a.segmen != '' ";
		$result = array();
		$statusHeader = 200;
		$fileTitle = "ExcelHourly-" . str_replace("-", "", $tanggal);
		try {
			$group = "b.no_unit";
			$data = $this->unit->getDataToExcelAllInput($filter);
			
			if($data->num_rows() > 0){
				$result['data'] = $data->result();
				$result['response'] = "success";
				$result['title'] = $fileTitle;
				$statusHeader = 200;
			}else{
				$result['data'] = array();
				$result['response'] = "Not Found";
				$statusHeader = 404;
			}
		} catch (Exception $e) {
			$result['data'] = array();
			$result['response'] = "Error " . $e->getMessage();
			$statusHeader = 500;
		}
		$object->setActiveSheetIndex(0);
		$table_columns = array("TANGGAL", "WAKTU", "C/N UNIT", "OPERATOR", "NRP");
		$column = 0;

		foreach($table_columns as $field){
			$object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
			$column++;
		}

		$dataResponse = $result['data'];
		$excel_row = 2;
		foreach ($dataResponse as $key) {
			$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $key->tanggal);
			$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $key->waktu);
			$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $key->no_unit);
			$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $key->operator);
			$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $key->nrp);
			$excel_row++;
		}

		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');

		header('Content-Type: application/vnd.ms-excel');
		$filename = "ExcelHourly-" . str_replace("-", "", $tanggal);
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		$object_writer->save('php://output');
	}

	public function validationData()
	{
		$komentar = $this->input->post('komentar');
		$id = $this->input->post('id');
		$user = $this->input->post('user');
		$date = date("H:i:s");
		$data = array('id_engine_operator' => $id, 'validation_status' => '1', 'comment' => $komentar, 'vpic' => $user, 'vtime' => $date);
		$ValidationData = $this->db->insert("validation_data", $data);
		if($this->db->affected_rows() > 0){
			$id_validation = $this->db->insert_id();
			$data_engine_validation = array('validation' => '1');
			$where = "id = '$id'";
			$update_engine = $this->db->update("engine_operator", $data_engine_validation, $where);
			if($this->db->affected_rows() > 0){
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode(array('response' => 'success'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			}else{
				$this->db->delete("validation_data", "id='$id_validation'");
				$this->output
				->set_status_header(300)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode(array('response' => 'Update Validation Failed. Return Change'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			}
		}else{
			$this->db->delete("validation_data", "id='$id_validation'");
			$this->output
			->set_status_header(500)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode(array('response' => 'Update Validation Failed'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			
		}
		
	}
}