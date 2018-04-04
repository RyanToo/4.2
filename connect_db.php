<?php
function Connect_BD(){
$dbname = "tsibulnik";
$user_root = "tsibulnik";
$sql_pass = 'neto0412';
		$pdo = new PDO("$db = new PDO('mysql:host=localhost;dbname=$db_name", "");
		$pdo->exec('SET NAMES utf-8');
		$pdo->exec("SET CHARACTER SET 'utf8';");
		$pdo->exec("SET SESSION collation_connection = 'utf8_general_ci';");
		return($pdo);
		}

