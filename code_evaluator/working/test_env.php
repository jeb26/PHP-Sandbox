<?php

header('Content-Type: application/json');

$test_case = array("[1,2,3]","[4,5,6]");

function create_test_assoc($cases)
{
	$index = 0;
	$json = array();

	foreach ($cases as $key) {
		$brackets = array("[","]");
		$str = $key;
		$strip = str_replace($brackets, "", $str);
		$test_array = explode(",", $strip);
		$success_case = array_pop($test_array);
		$t_params = implode(",", $test_array);
		$params = str_replace(",", " ", $t_params);

		//print_r($params);
		//echo $params . "<br />";
		
		$json[] = array(
			"params" => $params,
			"success_case" => $success_case,
			"error" => 0,
			"output" => 0
		);
		//$index++;
	}
	//print_r($json);
	return $json;
}

$json = create_test_assoc($test_case);

print_r($json);

//print_r($test_case);
create_test_assoc($test_case);


?>