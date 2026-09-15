<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require "db.php";

$id = intval($_GET["id"] ?? 0);

if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

header("Location: index.php");
exit;