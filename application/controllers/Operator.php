<?php
/**
 * 
 */
class Operator extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		header('Access-Control-Allow-Origin: *');
		date_default_timezone_set("Asia/Jakarta");
		$this->load->model("OperatorModel");
		$this->load->model("Activity_model", "act");
	}

	public function createActivity()
	{
		$no_unit = $this->input->post('noUnit');
		$nrp = $this->input->post('nrp');
		$days_of = $this->input->post('daysOf');
		$shift = $this->input->post('shift');
		$hm_start = $this->input->post('hmAwal');
		$created_by = $this->input->post('createdBy');
		$created_date = date('Y-m-d H:i:s');
		$data = array('no_unit' => $no_unit, 
			'nrp' => $nrp, 
			'days_of' => $days_of, 
			'shift' => $shift, 
			'hm_start' => $hm_start, 
			'created_by' => $created_by, 
			'created_date' => $created_date);
		$response = array();
		$statusheaader = null;
		try {
			$insert = $this->OperatorModel->createActivity($data);
			if($insert){
				$id = $this->db->insert_id();
				$filter = "a.id = ".$id;
				$data_last_activity_operator = $this->OperatorModel->getBy($filter)->row_array();
				$response['operator_start'] = $data_last_activity_operator;
				$response['message'] = 'success';
				$response['code'] = '00';
				$statusheaader = 200;
			}else{
				$response['message'] = 'failed';
				$response['code'] = '01';
				$statusheaader = 400;
			}
		} catch (Exception $e) {
			$response['message'] = 'failed because ' . $e->getMessage();
			$response['code'] = '01';
			$statusheaader = 500;
		}
		$this->output
        ->set_status_header($statusheaader)
        ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}

	public function engineInput()
	{
		$material = $this->input->post('material');
		$metode = $this->input->post('metode');
		$aktivitas = $this->input->post('aktivitas');
		$muatan = $this->input->post('muatan');
		$status = $this->input->post('status');
		$status_engine = $this->input->post('statusEngine');
		$jam_engine = $this->input->post('runningTimer');
		$jam_sekarang  = $this->input->post('jam_sekarang');
		$activity_time = $this->input->post('actTimer');
		$status_time = $this->input->post('statTimer');
		$created_by = $this->input->post('createdBy');
		$all_productivity_unit = $this->input->post('all_productivity_unit');
		$activity_productivity_unit = $this->input->post('activity_productivity_unit');
		$effectivness = $this->input->post('effectivness');
		$ritase_sebelum = $this->input->post('ritase_sebelum');
		$ritase_sekarang = $this->input->post('ritase_sekarang');
		$id_activity_operator = $this->input->post('id_activity_operator');
		$array_insert = array('id_activity_operator' => $id_activity_operator, 
							'status_engine' => $status_engine, 
							'activity_now' => $aktivitas, 
							'status_now' => $status, 
							'activity_time' => $activity_time, 
							'status_time' => $status_time, 
							'all_productivity_unit' => $all_productivity_unit,
							'activity_productivity_unit' => $activity_productivity_unit, 
							'effectivness' => $effectivness, 
							'metode' => $metode, 
							'muatan' => $muatan, 
							'ritase_sebelum' => $ritase_sebelum, 
							'ritase_sekarang' => $ritase_sekarang, 
							'jam_engine' => $jam_engine, 
							'jam_sekarang' => $jam_sekarang, 
							'created_date' => date('Y-m-d H:i:s'), 
							'created_by' => $created_by);
		$insert = $this->OperatorModel->create_engine_timeline($array_insert);
		if($insert > 0){
			$this->output
	        ->set_status_header(201)
	        ->set_content_type('application/json', 'utf-8')
	        ->set_output(json_encode(array('request' => $array_insert, 'affected' => $insert,'response' => 'success', 'code' => '00'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}else{
			$this->output
	        ->set_status_header(400)
	        ->set_content_type('application/json', 'utf-8')
	        ->set_output(json_encode(array('request' => $array_insert, 'response' => 'failed', 'code' => '01'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}
	}
}
		