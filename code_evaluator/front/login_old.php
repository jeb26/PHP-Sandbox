<?php

	//header("Content-Type: application/json");

	//COMMENT OR UNCOMMENT THESE LINES IF YOU WANT TO SEE ERROR MESSAGES TO HELP WITH DEBUGGING
	ini_set('display_errors',1);
	error_reporting(E_ALL);

	//CHANGE THESE TO POST VARIABLES BASED ON YOUR LOGIN INPUT FORM
	$username = "testname";
	$password = "testpass";

	//FUNCTION TAKES THE USER NAME AND PASSWORD AND RETURNS AN ARRAY WITH THE RESULTS OF THE ULEVEL
	function get_login_results($user, $pass)
	{
		$data = http_build_query(array(
		"username" => $user,
		"password" => $pass,
		'action' => "login"
		));

		$crl=curl_init();
		curl_setopt($crl, CURLOPT_URL,"http://web.njit.edu/~jeb26/php_sandbox/request.php");
		//curl_setopt($crl, CURLOPT_POST, 1);
		curl_setopt($crl, CURLOPT_POSTFIELDS, $data );
		//curl_setopt($crl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
		$res = curl_exec($crl);
		curl_close($crl);

		$json = json_decode($res,true);

		return $json;
	}

	$login_results = get_login_results($username,$password);

	//LOOP THROUGH THE ARRAY OF RESULTS AND REDIRECT
	foreach ($login_results as $key => $value) {
		//echo "key: " . $key . " value: " . $value . "<br />";
		if($key == "ulevel" and $value == "2")
		{
			//PUT REDIRECT HERE FOR PROFESSOR
			echo "you are a professor!";
		}
		elseif ($key == "ulevel" and $value == "3") {
			//PUT REDIRECT HERE FOR STUDENT
			echo "you are a student!";
		}
		elseif ($key == "ulevel" and $value == "1") {
			//PUT REDIRECT HERE FOR ADMIN EVENTUALLY
			echo "your are an admin!";
		}
	}
?>
