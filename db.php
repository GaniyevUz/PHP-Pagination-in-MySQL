<?php

$host = 'localhost';        // Change as required
$username = 'username';     // Change as required
$password = 'password';     // Change as required
$database = 'database';     // Change as required

$db = mysqli_connect($host, $username, $password, $database) or die(mysqli_connect_error());
