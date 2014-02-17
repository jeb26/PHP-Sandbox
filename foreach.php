<html>
<head>
	<title>Using Foreach Loop</title>
</head>

<body>
	<?php
		$times_to_loop = array(1,2,3,4,5,6,7,8,9,10);
		
		foreach($times_to_loop as $max)
		{
			echo "max is: $max <br />";
			for($i = 0; $i < $max; $i++)
			{
				echo "iteration " . ($i + 1) . "<br />";
			}
			echo "<hr />";
		}
	?>
</body>

</html>
