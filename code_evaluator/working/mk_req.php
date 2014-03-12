<?php
	$data = array( "action" => "listQues" );

	//$data_string = json_encode($data);
	$crl=curl_init();
	curl_setopt($crl, CURLOPT_URL,"http://web.njit.edu/~jeb26/php_sandbox/request.php");
	curl_setopt($crl, CURLOPT_POSTFIELDS, $data );
	//curl_setopt($crl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
	$res = curl_exec($crl);

	curl_close($crl);
	$res = json_decode($res, true);

	print_r($res);

?>