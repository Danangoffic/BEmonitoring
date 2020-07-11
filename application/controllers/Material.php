<?php
/**
 * 
 */
class Material extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
	}

	public function get_all()
	{
		$data_material = $this->db->get("material");
		if($data_material->num_rows() > 0){
			$result['data'] = $data_material->result_array();
			$result['response'] = 'success';
			$this->output
		        ->set_status_header(200)
		        ->set_content_type('application/json', 'utf-8')
		        ->set_output(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));	
		}else{
			$this->output
		        ->set_status_header(404)
		        ->set_content_type('application/json', 'utf-8')
		        ->set_output(json_encode(array('response' => 'Not Found', 'data' => []), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));	
		}
	}
}