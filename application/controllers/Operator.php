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
		// header("Access-Control-Allow-Methods: GET, OPTIONS");
		date_default_timezone_set("Asia/Jakarta");
		$this->load->model("OperatorModel");
		$this->load->model("StatusModel", "Status");
		$this->load->model("Activity_model", "act");
	}

	public function getAllOperator()
	{
		try {
			$data = $this->OperatorModel->getAllOperator();

			if($data->num_rows() > 0 ){
				$result=array('data' => $data->result_array(), 'response' => 'success');
				response(200, $result);
			}else{
				$result=array('data' => array(), 'response' => 'Not Found');
				response(404, $result);
			}
		} catch (Exception $e) {
			$result=array('data' => array(), 'response' => 'Server Error ' . $e->getMessage());
			response(500, $result);
		}
		
	}

	public function getNRPByOperator($nrp)
	{
		# code...
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
		$cekUnit = $this->OperatorModel->cekUnit($no_unit);
		$unit = null;
		if($cekUnit->num_rows() > 0){
			$unit = $no_unit;
		}else{
			$inset_unit = $this->db->insert("unit", array('kode' => $no_unit));
			$unit = $no_unit;
		}
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
				$response['id_created'] = $id;
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
		try {
			if(!empty($this->input->post('data'))){
				$response_insert = $this->act->insert_data_engine();
				if($response_insert['response'] == "success"){
					response(201, $response_insert);
				}else{
					response(400, $response_insert);
				}
			}else{
				$data_response = array('request' => null, 'response' => 'failed', 'message' => 'data not found to be inserted');
				response(404, $data_response);
			}
		} catch (Exception $e) {
			$data_response = array('request' => null, 'response' => 'failed', 'message' => 'server error ' . $e->getMessage());
			response(500, $data_response);
		}
	}

	public function engineUpdateActivity()
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
		$actual_prod = $this->input->post('actual_prod');
		$array_insert = array('status_engine' => $status_engine,
							'activity_time' => $activity_time, 
							'status_time' => $status_time, 
							'all_productivity_unit' => $all_productivity_unit,
							'activity_productivity_unit' => $activity_productivity_unit, 
							'effectivness' => $effectivness, 
							'metode' => $metode, 
							'muatan' => $muatan, 
							'actual_prod' => $actual_prod,
							'ritase_sebelum' => $ritase_sebelum, 
							'ritase_sekarang' => $ritase_sekarang, 
							'jam_engine' => $jam_engine, 
							'jam_sekarang' => $jam_sekarang, 
							'created_date' => date('Y-m-d H:i:s'), 
							'created_by' => $created_by);

		$where = array('id_activity_operator' => $id_activity_operator, 'activity_now' => $aktivitas);
		$last_data = $this->db->select("id")->from("engine_operator")->where($where)->order_by("id", "DESC")->get();
		$id = $last_data->row()->id;
		$Update= $this->db->update("engine_operator", $array_insert, "id = '$id'");
		echo $this->db->last_query();
		if($Update->affected_rows() > 0){
			$this->output
	        ->set_status_header(200)
	        ->set_content_type('application/json', 'utf-8')
	        ->set_output(json_encode(array('request' => "Update Activity",'response' => 'success', 'code' => '00'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}else{
			$this->output
	        ->set_status_header(400)
	        ->set_content_type('application/json', 'utf-8')
	        ->set_output(json_encode(array('request' => "Update Activity",'response' => 'failed', 'code' => '01'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}
	}

	public function updateActivityHMAkhir()
	{
		$hm = $this->input->post('hm');
		$id = $this->input->post('id');
		$by = "id = '$id'";
		$data = array('hm_stop' => $hm);
		try {
			$update = $this->act->update($by, $data);
			if($update){
				$this->output
		        ->set_status_header(201)
		        ->set_content_type('application/json', 'utf-8')
		        ->set_output(json_encode(array('request' => $data,'response' => 'success', 'code' => '00'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			}else{
				$this->output
		        ->set_status_header(400)
		        ->set_content_type('application/json', 'utf-8')
		        ->set_output(json_encode(array('request' => $data,'response' => 'failed', 'code' => '01'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			}
		} catch (Exception $e) {
			$this->output
		        ->set_status_header(500)
		        ->set_content_type('application/json', 'utf-8')
		        ->set_output(json_encode(array('request' => $data,'response' => 'error ' . $e->getMessage(), 'code' => '99'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}
	}

	public function create_operator_user()
	{
		$this->load->library('session');
		$data_operator = $this->db->get("divisi");
		$this->db->reset_query();
		$data_level = $this->db->get("user_level");
		$data_array = array(
			'title' => "Create Operator User",
			'data_operator' => $data_operator,'data_level' => $data_level);
		$this->load->view("create_operator_user", $data_array);
	}

	public function create_user()
	{
		$this->load->library('session');
		$nama = $this->input->post('nama');
		$nrp = $this->input->post('nrp');
		$divisi = $this->input->post('sebagai');
		$level = $this->input->post('level');
		$this->db->reset_query();
		$this->db->where("nrp", $nrp);
		$data = $this->db->get("detail_user");
		if($data->num_rows() > 0){
			$this->session->set_flashdata("error", 'Data User '.$nama.', '.$nrp.' Sudah');
			redirect(base_url("Operator/create_operator_user"));
		}else{
			$app_user = array('UNAMA' => $nrp, 'UDISTRIK' => $divisi, 'ULEVEL' => $level, 'UPASS' => $nrp, 'rowguid' => '1', 'rowguid7' => '1');
			$INSERT_APP_USER = $this->INSERT_USER($app_user);
			if($INSERT_APP_USER){
				$id_app_user = $this->db->insert_id();
				$detail_user = array('nama' => $nama, 'nrp' => $nrp, 'UID' => $id_app_user, 'divisi' => $divisi);
				$INSERT_DETAIL = $this->INSERT_DETAIL($detail_user);
				if($INSERT_DETAIL){
					$this->session->set_flashdata("success", 'Data Created ' . $nrp);
					redirect(base_url("Operator/create_operator_user"));
				}else{
					$this->session->set_flashdata("error", 'Data User Gagal Terbuat');
					redirect(base_url("Operator/create_operator_user"));
				}
			}else{
				$this->session->set_flashdata("error", 'Data Gagal');
				redirect(base_url("Operator/create_operator_user"));
			}
		}
	}

	private function cek_nrp_user($nrp)
	{
		
		if($data->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	private function INSERT_USER($data)
	{
		$this->db->reset_query();
		return $this->db->insert("app_user", $data);
	}

	private function INSERT_DETAIL($data)
	{
		$this->db->reset_query();
		return $this->db->insert("detail_user", $data);
	}
}
		