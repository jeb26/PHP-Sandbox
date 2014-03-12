<?php

header('Content-Type: application/json');

//constants for file creation, compile, and run
$file_name = "test_name.java";
$py_file_name = "iseven.py";
$fn_with_error = "tn.class";
$java = ".java";
$python = ".py";
$test_case1 = "2";
$test_case2 = "3";
//$cmd = 'java '. $file_name . " $test_case1 $test_case2"; 
//$cmd_error = 'java '. $fn_with_error . " $test_case1 $test_case2"; 
$cmd_java = "java";
$cmd_python = "python";
$case = "2 3";

//arrays storing results
$compile_results = array();
$json = array();

/**
 * 
 * java error in pipe 2.
 * pythons error are stored in pipe 2. 
 * Successful python compile are stored in pipe 1.
 * $array['error'] holds a number if compile not successful
  */

//function compile($file_name, $lang_type, $command, $case, &$array)
//{
	//creating the compile command
	//$cmd = $command ." ". $file_name ." ". $case;
$cmd = "python findMax.py 2 3";

	//variables nesecary to start process
	$descriptorspec = array(
		0 => array("pipe", "r"), // stdin is a pipe that the child will read from
		1 => array("pipe", "w"), // stdout is a pipe that the child will write to
		//2 => array("file","./error.log","a")
		2 => array("file", $cwd."error-output.txt", "a")
	);
	$cwd = getcwd();
	$env = array('some_option' => 'aeiou');

	//open the process
	$process = proc_open($cmd, $descriptorspec, $pipes, $cwd, $env);

	if (is_resource($process))
	{
		//$array['lang'] = $lang_type;

		//$array['command'] = $cmd;

		$compile_results['c0'] = stream_get_contents($pipes[0]);
		fclose($pipes[0]);

		$compile_results['c1'] = stream_get_contents($pipes[1]);
		fclose($pipes[1]);

		$compile_results['c2'] = stream_get_contents($pipes[2]);
		fclose($pipes[2]);

		$return_value = proc_close($process);

		//$array['error'] = $return_value;
	}
	else
	{
		echo $process;
	}

	//debugging info
	if($compile_results['crv'] != 0)
	{
		$compile_results['error'] = $return_value;
	}
	else if($compile_results['crv'] == 0)
	{
		$compile_results['error'] = 0;
	}
//}

//Compile a java program
//compile("findMax.py", $python, $cmd_python, $case, $compile_results);

print_r($compile_results);

?>