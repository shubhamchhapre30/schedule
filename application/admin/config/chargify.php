<?php
	$payment_setting = payment_setting();

	$config['API_key'] = $payment_setting->API_key;
	$config['subdomain'] = $payment_setting->subdomain;
	
	
?>