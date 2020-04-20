<?php
/**
 * 
 */
class Unit extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		header('Access-Control-Allow-Origin: *');
		$this->load->model("UnitModel", "unit");
	}

	public function getAll()
	{
		try {
			$data = $this->unit->getAll();
			$this->output
	        ->set_status_header(200)
	        ->set_content_type('application/json', 'utf-8')
	        ->set_output(json_encode($data->result_array(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		} catch (Exception $e) {
			$this->output
	        ->set_status_header(404)
	        ->set_content_type('application/json', 'utf-8')
	        ->set_output(json_encode(array('response'=>'Not Found'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}

	}

	public function getAllUnitActivitiesByDateAndHour()
	{
		$tanggal = $this->input->post('tanggal');
		$jam = $this->input->post('jam');
		if ($jam===null||$jam==="") {
			$jam = date('H').":00:00";
		}
		try {
			$filter = "a.created_date like '" . $tanggal . "%' AND a.jam_sekarang <= '" . $jam . "'";
			$data = $this->unit->getByFilter($filter);
			// echo $this->db->last_query();
			if($data->num_rows() > 0){
				$this->output
		        ->set_status_header(200)
		        ->set_content_type('application/json', 'utf-8')
		        ->set_output(json_encode($data->result_array(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			}else{
				$this->output
		        ->set_status_header(404)
		        ->set_content_type('application/json', 'utf-8')
		        ->set_output(json_encode(array('response'=>"Not Found"), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			}
		} catch (Exception $e) {
			$this->output
	        ->set_status_header(500)
	        ->set_content_type('application/json', 'utf-8')
	        ->set_output(json_encode(array('response' => 'Server Exhausted'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}
	}
}