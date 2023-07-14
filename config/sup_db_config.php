<?php
@ob_start();
session_start();
ini_set('display_errors', FALSE);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "server";

// to check whether pin is updated or not

$sup_db = mysqli_connect('localhost', 'root', '', 'server');
//$mysqli = new mysqli('localhost', 'ashams001', 'iqHired@123', 'sg_supplier');
$sup_mysqli = new mysqli('localhost', 'root', '', 'server');

date_default_timezone_set("America/chicago");

$sitename = "pn_2023";

$scriptName = "https://localhost/pn_2023/";

?>