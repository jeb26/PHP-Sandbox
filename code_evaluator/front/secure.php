<?php

session_start();

if (!isset($_SESSION['auth']))
{
	header('Location: login.html');
}

echo "welcome to student or teacher page." . "<br />";

echo "uno is:". $_SESSION['uno']."<br />";

echo "<a href='logout.php'>Logout</a>";

?>