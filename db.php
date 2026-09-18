<?php

$host = "localhost";
$db_user = "seyram.apreku";
$db_pass = "@Seyram123.";
$db_name = "ecommerce_2026A_seyram_apreku";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}