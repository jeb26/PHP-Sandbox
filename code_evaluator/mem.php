<?php


$res = array();

$command = "python findMax.py 1 2";

	$descriptorspec = array(
		0 => array("pipe", "r"), // stdin is a pipe that the child will read from
		1 => array("pipe", "w"), // stdout is a pipe that the child will write to
		//2 => array("file","./error.log","a")
		2 => array("pipe", "w")
	);
	$cwd = getcwd();
	$env = array('some_option' => 'aeiou');

	//open the process
	$process = proc_open($command, $descriptorspec, $pipes, $cwd, $env);

	if (is_resource($process))
	{
		//$res['lang'] = $lang_type;

		$res['command'] = $command;

		$res['c0'] = stream_get_contents($pipes[0]);
		fclose($pipes[0]);

		$res['c1'] = stream_get_contents($pipes[1]);
		fclose($pipes[1]);

		$res['c2'] = stream_get_contents($pipes[2]);
		fclose($pipes[2]);

		$return_value = proc_close($process);

		//$array['error'] = $return_value;
	}

print_r($res);

?>