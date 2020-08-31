<?php
/**
 * 
 */
class Activity extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		$this->load->model("Activity_model", "ModelAct");
	}

	public function index()
	{
		if($this->input->get('kode')){
			return $this->getByKode();
		}else{
			return $this->getAll();
		}
	}

	public function getByKode()
	{
		$kode = $this->input->get('kode');
		try {
			$data = $this->ModelAct->getByKode($kode);
			if($data->num_rows() > 0){
				$this->output
		        ->set_status_header(200)
		        ->set_content_type('application/json', 'utf-8')
		        ->set_output(json_encode($data->row_array(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));	
			}else{
				$this->output
		        ->set_status_header(404)
		        ->set_content_type('application/json', 'utf-8')
		        ->set_output(json_encode(array('response'=>'Not Found'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));	
			}
		} catch (Exception $e) {
			$this->output
	        ->set_status_header(500)
	        ->set_content_type('application/json', 'utf-8')
	        ->set_output(json_encode(array('response'=>'Server Error'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}
	}

	public function getAll()
	{
		$data = $this->ModelAct->getAllAktivitas();
		$this->output
        ->set_status_header(200)
        ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode($data->result_array(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}

	public function getAllStatus()
	{
		$data = $this->ModelAct->getAllStatus();
		$this->output
        ->set_status_header(200)
        ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode($data->result_array(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}

	public function try_curl()
	{
		$tuCurl = curl_init();
		curl_setopt($tuCurl, CURLOPT_URL, base_url('api/material'));
		curl_setopt($tuCurl, CURLOPT_PORT , 80);
		curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, TRUE);
		$head = curl_exec($tuCurl);
        $httpCode = curl_getinfo($tuCurl, CURLINFO_HTTP_CODE);
        curl_close($tuCurl);
        response(200, json_decode($head));
	}
}