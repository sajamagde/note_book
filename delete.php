<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit();
}

if (!isset($_GET['note_id'])) {
    header("Location: main.php");
    exit();
}

$conn = new mysqli('localhost','root','','note_book');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$note_id = intval($_GET['note_id']);
$stmt = $conn->prepare("DELETE FROM notes WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $note_id, $_SESSION['user_id']);
$stmt->execute();
$stmt->close();
$conn->close();

header("Location: main.php");
exit();
?>
