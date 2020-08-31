<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('Pusher'))
{
	function Pusher($pesan)
	{
		$options = array(
		    'cluster' => 'ap1',
		    'useTLS' => true
		  );
		  $pusher = new Pusher\Pusher(
		    '0fc1d0674c91d5d25e69',
		    '45dee09857933bbf6c67',
		    '1018251',
		    $options
		  );

		  // $data['message'] = $pesan;
		  $pusher->trigger('monitoring', 'my-event', json_encode($pesan));
	}
}
if(!function_exists('response')){
	function response(int $http_code=200, $array_data=array())
	{
		$response =& get_instance();
		$response->output
			->set_status_header($http_code)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($array_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}
}