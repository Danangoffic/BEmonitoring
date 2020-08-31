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
		try {
			$data_material = $this->db->get("material");
			if($data_material->num_rows() > 0){
				$result['data'] = $data_material->result_array();
				$result['response'] = 'success';
				response(200, $result);
			}else{
				response(404, array('response' => 'Not Found', 'data' => []));
			}
		} catch (Exception $e) {
			response(500, array('response' => 'Server error ' . $e->getMessage(), 'data' => []));
		}
	}
}