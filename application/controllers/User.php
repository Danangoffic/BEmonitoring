<?php
/**
 * 
 */
class User extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Jakarta");
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
		header( "Access-Control-Allow-Credentials: true" );
		$this->load->model("UserModel", "User");
	}

	public function index()
	{
		$arrayName = array('message' => 'failed');
		echo json_encode($arrayName);
	}

	public function doLogin()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$arrayName = null;
		try {
			$cek = $this->User->checkUserValidity($username, $password);
			$dateLogin = date('dmyHis');
			// echo $this->db->last_query();
			// exit();
			if($cek->num_rows() > 0){
				$arrayName = array('dataUser' => $cek->row_array(), 'message'=>'success', 'code'=>'00', 'dateLogin' => $dateLogin);
				
			}else{
				$arrayName = array('dataUser' => null, 'message'=>'failed', 'code'=>'01');
			}
		} catch (Exception $e) {
			$arrayName = array('dataUser' => null, 'message'=>'failed with' . $e->getMessage(), 'code'=>'02');
		}
		
		echo json_encode($arrayName, JSON_PRETTY_PRINT);
	}
}