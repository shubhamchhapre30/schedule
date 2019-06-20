<?php
	//$fbs = facebook_setting();


	//echo  $fbs->facebook_api_key;  
	//echo  $fbs->facebook_secret_key;

	//$config['facebook_api_key'] = $fbs->facebook_api_key;
	//$config['facebook_secret_key'] = $fbs->facebook_secret_key;
	
	
	// $config['l_id'] = '3hb3EC3X';//login id
	// $config['t_id'] = '7gS7Grg453Bama42'; // transactionid
	// $config['sd'] = true;//sendbox
	
	
	$get_authorized_login=get_authorized_login();
	$config['l_id'] = $get_authorized_login->authorize_login_id;//login id
	$config['t_id'] = $get_authorized_login->authorize_transaction_id; // transactionid
	$config['sd'] = $get_authorized_login->authorize_sendbox;
	
	/*$config['l_id'] = '3hb3EC3X';//login id
	$config['t_id'] = '7gS7Grg453Bama42'; // transactionid
	$config['sd'] = true;//sendbox */
	
?>