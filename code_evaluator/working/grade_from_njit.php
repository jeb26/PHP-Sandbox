<?php

header('Content-Type: application/json');

//get info to work with from back
$result = array();
getdata("listQues", $result);


$results = array();

//get get testcases, student code, and function names
$test_cases = get_cases($result);
$code = get_response($result);
$function_name = get_function_name($code);
$assoc = create_test_assoc($test_cases);

//constants for file_name creation
$PYTHON = ".py";

//python template
$pyt = "from sys import argv\n\n";
$pyt .= "filename, first, second = argv\n\n";
$pyt .= "{$code}";
$pyt .= "\nans = {$function_name}(first,second)\n";
$pyt .= "\nprint(ans)\n";
$pyt .= "#sys.stdout.flush()\n";

//part 1: Create python or java file on server
function createFile($language_type, $fname, $code)
{
	$file_name = $fname . $language_type;
	
	if(file_put_contents($file_name,$code))
	{
		return $file_name;
	}
	else 
	{
		return false;
	}
}


function getdata($request_type, &$array)
{
	$data = array( "action" => $request_type );

	//$data_string = json_encode($data);
	$crl=curl_init();
	curl_setopt($crl, CURLOPT_URL,"http://web.njit.edu/~jeb26/php_sandbox/request.php");
	curl_setopt($crl, CURLOPT_POSTFIELDS, $data );
	//curl_setopt($crl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
	$res = curl_exec($crl);

	curl_close($crl);
	$res = json_decode($res, true);

	$array = $res;
}


function get_cases($data)
{
	foreach ($data as $ques_id => $ques_content) 
	{
		if(is_array($ques_content))
		{
			foreach ($ques_content as $field_name => $field_value) 
			{
				if($field_name == "SNAME" && $field_value == 4)
				{
					$input = $ques_content['QDESC'];
					$cases = explode("\n", $input);

					return $cases;
				}
			}
		}
	}
}

function get_response($data)
{
	foreach ($data as $ques_id => $ques_content) 
	{
		if(is_array($ques_content))
		{
			foreach ($ques_content as $field_name => $field_value) 
			{
				if($field_name == "SNAME" && $field_value == 4)
				{
					$input = $ques_content['ANSWER'];

					return $input;
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

	$func_name = $func_keyword[$last_elem];

	return $func_name;

}

//part 2: Compile the python or java file and grab results for display
function compile($file_name, $lang_type, $command, $case, $iter, &$array)
{
	//creating the compile command
	$cmd = $command ." ". $file_name ." ". $case;
	//$cmd = "python" ." ". $file_name ." ". $case;

	//variables nesecary to start process
	$descriptorspec = array(
		0 => array("pipe", "r"), // stdin is a pipe that the child will read from
		1 => array("pipe", "w"), // stdout is a pipe that the child will write to
		//2 => array("file","./error.log","a")
		2 => array("pipe", "w")
	);
	$cwd = getcwd();
	$env = array('some_option' => 'aeiou');

	//open the process
	$process = proc_open($cmd, $descriptorspec, $pipes, $cwd, $env);

	if (is_resource($process))
	{
		$array[$iter]['lang'] = $lang_type;

		$array[$iter]['command'] = $cmd;
		
		fclose($pipes[0]);

		$array[$iter]['output'] = stream_get_contents($pipes[1]);
		fclose($pipes[1]);

		fclose($pipes[2]);

		$return_value = proc_close($process);

		$array[$iter]['error'] = $return_value;
	}
}

//part 3: run test test cases and campare results
function create_test_assoc($cases)
{
	$index = 0;
	$assoc = array();

	foreach ($cases as $key) {
		$brackets = array("[","]");
		$str = $key;
		$strip = str_replace($brackets, "", $str);
		$test_array = explode(",", $strip);
		$success_case = array_pop($test_array);
		$t_params = implode(",", $test_array);
		$params = str_replace(",", " ", $t_params);
		
		$assoc[] = array(
			"params" => $params,
			"success_case" => $success_case,
			"error" => 0,
			"output" => 0
		);
	}
	return $assoc;
}

function runtest($filename, $lang_ext, $lang_name, &$assoc)
{
	$index = 0;
	$tests = $assoc;
	foreach ($tests as $key => $value) {
		foreach ($value as $k => $v) {
			if($k == "params")
			{
				compile($filename, $lang_ext, $lang_name,$v, $index, $assoc);
			}
		}
		$index++;
	}
}

//BEGIN MAIN

//part 4 main function for running everything and outputing json results
function main($lang, $fn, $code, $cases, &$arr)
{
	if(!($f = createFile($lang, $fn, $code)))
	{
		die("file creation failed");
	}
	else
	{
		runtest($f, $lang, "python", $arr);

	}
}

	main($PYTHON, $function_name, $pyt, $test_cases, $assoc);

	//print_r($test_cases);
	print_r($assoc);
?>