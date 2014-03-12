<?php

//ini_set('display_errors',1);
//error_reporting(E_ALL);

//header('Content-Type: application/json');

//$request = array( "action" => "listUExam" );
//$result = array();

//print_r($data);

function getdata($request_type, &$result)
{
	switch ($request_type) 
	{
		case "login":
		$password = md5($_POST["password"]);
		$data = array("username" => $_POST["username"], "password" => $password, "action" => "login");
		$send = true;
		break;

		case "addUser":
		$password = md5($_POST["password"]);
		$data = array("username" => $_POST["username"], "password" => $password, "displayn" => $_POST["displayn"], "ulevel" => $_POST["ulevel"], "action" => "addUser");
		$send = true;
		break;

		case "delUser":
		$data = array("usernum" => $_POST["usernum"], "action" => "delUser");
		$send = true;
		break;

		case "listUser":
		$data = array( "action" => "listUser" );
		$send = true;
		break;

		case "addQues":
		$data = array("sname" => $_POST["sname"], "qdesc" => $_POST["qdesc"], "qtype" => $_POST["qtype"], "qworth" => $_POST["qworth"],
		"answer" => $_POST["answer"], "notes" => $_POST["notes"],
		"action" => "addQues");
		$send = true;
		break;

		case "delQues":
		$data = array("quesnum" => $_POST["quesnum"], "action" => "delQues");
		$send = true;
		break;

		case "listQues":
		$data = array( "action" => "listQues" );
		$send = true;
		break;	

		case "addExam":
		$data = array("ename" => $_POST["ename"], "eduration" => $_POST["eduration"], "cdatetime" => $_POST["cdatetime"], "mpscore" => $_POST["mpscore"],
		"action" => "addExam");
		$send = true;
		break;

		case "delExam":
		$data = array("examnum" => $_POST["examnum"], "action" => "delExam");
		$send = true;
		break;

		case "listExam":
		$data = array( "action" => "listExam" );
		$send = true;
		break;	

		case "addEQues":
		$data = array("eno" => $_POST["eno"], "qno" => $_POST["qno"], "action" => "addEQues");
		$send = true;
		break;

		case "delEQues":
		$data = array("eno" => $_POST["eno"], "eline" => $_POST["eline"], "action" => "delEQues");
		$send = true;
		break;

		case "listEQues":
		$data = array( "action" => "listEQues" );
		$send = true;
		break;	

		case "addUExam":
		$data = array("eno" => $_POST["eno"], "uno" => $_POST["uno"], "tscore" => $_POST["tscore"], "stime" => $_POST["stime"],
		"action" => "addUExam");
		$send = true;
		break;

		case "delUExam":
		$data = array("eno" => $_POST["eno"], "uno" => $_POST["uno"], "action" => "delUExam");
		$send = true;
		break;

		case "listUExam":
		$data = array( "action" => "listUExam" );
		$send = true;
		break;

		case "addUEAns":
		$data = array("eno" => $_POST["eno"], "uno" => $_POST["uno"], "eline" => $_POST["eline"], "uans" => $_POST["uans"],
		"action" => "addUEAns");
		$send = true;
		break;

		case "delUEAns":
		$data = array("eno" => $_POST["eno"], "uno" => $_POST["uno"], "eline" => $_POST["eline"], "action" => "delUEAns");
		$send = true;
		break;

		case "listUEAns":
		$data = array( "action" => "listUEAns" );
		$send = true;
		break;
	}

	/**
	 * Begin json encoding of information.
	 */
	if($send)
	{
		$data_string = json_encode($data);
		$crl=curl_init();
		//curl_setopt($crl, CURLOPT_URL,"http://web.njit.edu/~jeb26/cs490/login_system/auth.php");
		curl_setopt($crl, CURLOPT_URL,"http://web.njit.edu/~ser5/sqlaction.php");
		//curl_setopt($crl, CURLOPT_POST, 1);
		curl_setopt($crl, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($crl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
		$res = curl_exec($crl);

		curl_close($crl);
		$res = json_decode($res, true);

		$result = $res;

		//print_r($result);
	}
}

//print_r($result);

//getdata("listQues", &$temp);
//getdata("listQues", $result);
//getdata("listExam", &$temp1);
//print_r($result);

//print_r($result);

function get_cases($data)
{
	foreach ($data as $ques_id => $ques_content) 
	{
		if(is_array($ques_content))
		{
			foreach ($ques_content as $field_name => $field_value) 
			{
				//echo "field name: " . $field_name . " value: " . $field_value . "<br />";
				if($field_name == "SNAME" && $field_value == 4)
				{
					//print_r($ques_content);
					$input = $ques_content['QDESC'];
					$cases = explode("\n", $input);

					return $cases;
					//echo "<pre>$input</pre>";
					//this will change to coresspond to where the student response actually is held
					//$response = $ques_content['ANSWER'];
					//echo $cases;
				}
			}
		}
	}
}

//$test_cases = get_cases($result);
//print_r($test_cases);

function get_response($data)
{
	foreach ($data as $ques_id => $ques_content) 
	{
		if(is_array($ques_content))
		{
			foreach ($ques_content as $field_name => $field_value) 
			{
				//echo "field name: " . $field_name . " value: " . $field_value . "<br />";
				if($field_name == "SNAME" && $field_value == 4)
				{
					//print_r($ques_content);
					$input = $ques_content['ANSWER'];
					//$cases = explode("\n", $input);

					return $input;
					//return $cases;
					//echo "<pre>$input</pre>";
					//this will change to coresspond to where the student response actually is held
					//$response = $ques_content['ANSWER'];
					//echo $cases;
				}
			}
		}
	}
}

function get_function_name($code)
{
	//explode on \n
	$lines_in_code = explode("\n", $code);
	
	//take elem 1
	$first_line = $lines_in_code[0];

	//explode on (
	$func_sig = explode("(", $first_line);

	//take elem 1
	$sig  = $func_sig[0];

	//explode on " "
	$func_keyword = explode(" ", $sig);

	//take last elem
	$len = sizeof($func_keyword);
	$last_elem = $len - 1;

	//get the function name
	//$func_name = func_keyword[$last_elem];
	$func_name = $func_keyword[$last_elem];

	return $func_name;

}

//$test_cases = get_cases($result);
//$code = get_response($result);
//$function_name = get_function_name($code);

//echo $function_name;

//fuction get_filename($code,$lang_type)
//{

//}



?>