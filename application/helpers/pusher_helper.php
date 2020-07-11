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