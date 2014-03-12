<?php

//header('Content-Type: application/json');

require_once("getdata.php");

//get info to work with from back
$result = array();
getdata("listQues", $result);
$results = array();

//get get testcases, student code, and function names
$test_cases = get_cases($result);
$code = get_response($result);
$function_name = get_function_name($code);

//constants for file_name creation
$PYTHON = ".py";
$JAVA = ".java";

//constants for whitespace and delimiters
$TAB = "\t";
$CR = "\n";

//java template
$str = "public class {$function_name} { $CR";
$str .= "$TAB public static void main(String[] args) { $CR";
$str .= "$TAB $TAB int test = {$function_name}(Integer.parseInt(args[1]), Integer.parseInt(args[2])); $CR";
$str .= "$TAB $TAB System.out.println(test); $CR";
$str .= "$TAB } $CR";
$str .= $TAB . $code . $CR;
$str .= "} $CR";

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
	
	//return $file_name;
	//echo $file_name;
	//echo $fname;
	//echo $language_type;
	
	if(file_put_contents($file_name,$code))
	{
		return $file_name;
	}
	else 
	{
		return false;
	}
}

//part 2: Compile the python or java file and grab results for display
function compile($file_name, $lang_type, $command, $case, &$array)
{
	//creating the compile command
	$cmd = $command ." ". $file_name ." ". $case;

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
		$array['lang'] = $lang_type;

		$array['command'] = $cmd;

		$array['c0'] = stream_get_contents($pipes[0]);
		fclose($pipes[0]);

		$array['c1'] = stream_get_contents($pipes[1]);
		fclose($pipes[1]);

		$array['c2'] = stream_get_contents($pipes[2]);
		fclose($pipes[2]);

		$return_value = proc_close($process);

		//$array['error'] = $return_value;
	}

	//debugging info
	if($array['crv'] != 0)
	{
		$array['error'] = $return_value;
	}
	else if($array['crv'] == 0)
	{
		$array['error'] = 0;
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

		//print_r($params);
		//echo $params . "<br />";
		
		$assoc[] = array(
			"params" => $params,
			"success_case" => $success_case,
			"error" => 0,
			"output" => 0
		);
		//$index++;
	}
	//print_r($json);
	return $assoc;
}

//part 4 main function for running everything and outputing json results
function main($lang, $fn, $code, $cases, &$arr)
{
	//createFile($JAVA, $file_name, $str);
	//$fn = createFile($JAVA, $file_name, $str);

	if(!($f = createFile($lang, $fn, $code)))
	{
		//following code runs if file creation fails
		die("file creation failed");
	}
	else
	{
		//following code run if file creation suceeds
		//compileCode($f, $lang);
		echo "file creation success!" . "<br />";
		echo "filename: " . $f . "<br />";
		echo "code added to file: " . "<pre>" . $code . "</pre>". "<br /><hr />";
		echo "compile results: <br />";
		compile($f, $PYTHON, "python", "2 3", $arr);
		$json = create_test_assoc($cases);
		print_r($json);
	}
	
	//echo $fn;
	
}
?>

<?php

	main($PYTHON, $function_name, $pyt, $test_cases, $results);

	//print_r($test_cases);
?>
