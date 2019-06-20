<?php
if(defined('STDIN'))
{
	if(isset($argv[1]))
	{
		if($argv[1] == 'dev')
		{
			$url = "https://dev.schedullo.com";
		}
		else if($argv[1] == 'test')
		{
			$url = "https://test.schedullo.com";
		}
		else if($argv[1] == 'prod')
		{
			$url = "https://app.schedullo.com";
		}
		else{
			$url = "http://localhost/schedullo";
		}
	}
	else{
		$url = "http://localhost/schedullo";
	}
}
else{
	$url = "http://localhost/schedullo";
}

$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $url.'/TestMin'
));
// Send the request & save response to $resp
echo $resp = curl_exec($curl);
// Close request to clear up some resources
curl_close($curl);

?>