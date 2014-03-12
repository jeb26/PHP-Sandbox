<?php

	$data = array( "action" => "listUser" );

	$data_string = json_encode($data);
	$crl=curl_init();
	//curl_setopt($crl, CURLOPT_URL,"http://web.njit.edu/~jeb26/cs490/login_system/auth.php");
	curl_setopt($crl, CURLOPT_URL,"http://web.njit.edu/~jeb26/php_sandbox/request.php");
	//curl_setopt($crl, CURLOPT_POST, 1);
	curl_setopt($crl, CURLOPT_POSTFIELDS, $data_string );
	curl_setopt($crl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($crl);

	curl_close($crl);
	$result = json_decode($result, true);

	print_r($result);


?>
