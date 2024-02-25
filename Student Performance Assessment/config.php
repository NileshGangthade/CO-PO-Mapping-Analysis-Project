<?php
$host = 'localhost';
$user = 'root';
$password = 'root';
$dbname = 'co_login';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
