<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['guru_id'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_event = (int)$_GET['id'];
    $stmt = $conn->prepare("DELETE FROM kalender_akademik WHERE id_event = ?");
    $stmt->bind_param("i", $id_event);
    if ($stmt->execute()) {
        header("Location: manajemen_kalender.php");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
} else {
    header("Location: manajemen_kalender.php");
}
?>