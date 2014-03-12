<?php

require_once("functions.php");

//header("Content-Type: application/json");

ini_set('display_errors',1);
ini_set('memory_limit','128M');
error_reporting(E_ALL);

$user_number = "1";
$exam_number = "1";

//VARIABLES FOR OPEN ENDED GRADING
$exam_data = get_test($user_number,$exam_number);
$cases = get_cases($exam_data);
$student_code = get_response($exam_data);
$function_name = get_function_name($student_code);
$assoc = create_test_assoc($cases);
$total_grade = 0;
$output = array();

//constants for file_name creation and student code template
$PYTHON = ".py";

//python template
$pyt = "from sys import argv\n\n";
$pyt .= "filename, first, second = argv\n\n";
$pyt .= "{$student_code}";
$pyt .= "\nans = {$function_name}(first,second)\n";
$pyt .= "\nprint(ans)\n";
$pyt .= "#sys.stdout.flush()\n";

//create the file on server
$filename = $function_name . $PYTHON;
$res = array();


//echo $filename;

$iter = 0;
if(file_put_contents($filename, $student_code))
{
	echo "file created!<br />compiling....<br />";
	foreach ($assoc as $key => $value) {
		foreach ($value as $k => $v) {
			//echo "key is: " . $k . " value is " . $v . "<br />";
			if($k == "params")
			{
				//echo "v is: " . $v. "<br />";
				$command = "python ".$function_name .".py ". $v;
				//echo $command . "<br />";
				//$process = proc_open($command, $descriptorspec, $pipes, $cwd, $env);

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

				//debugging info
				//if($res['crv'] != 0)
				//{
					//$res['error'] = $return_value;
				//}
				//else if($res['crv'] == 0)
				//{
					//$res['error'] = 0;
				//}




			}
			
			//$test_case = $v;
			//echo $test_case ."<br />";
		}
		$iter++;
	}
}
else
{
	echo "file not created!";
}


//compile the comple and get results with test cases

//print_r($exam_data);
//echo "<br /><br />";
//print_r($cases);
//echo "<br /><br />";
//print_r($student_code);
//echo "<br /><br />";
//print_r($function_name);
//echo "<br /><br />";
//print_r($assoc);
print_r($res);


?>