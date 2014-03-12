<?php

session_start();

unset($_SESSION['auth']);
unset($_SESSION['uno']);

header('Location: login.html');

?>