<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit();
}

$conn = new mysqli('localhost','root','','note_book');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['note_id'], $_POST['title'], $_POST['note'])) {
    $note_id = intval($_POST['note_id']);
    $title = trim($_POST['title']);
    $note = trim($_POST['note']);

    $stmt = $conn->prepare("UPDATE notes SET title=?, note=? WHERE id=? AND user_id=?");
    $stmt->bind_param("ssii", $title, $note, $note_id, $_SESSION['user_id']); 
    $stmt->execute();
    $stmt->close();

    $conn->close();
    header("Location: main.php");
    exit();
}

if (!isset($_GET['note_id'])) {
    header("Location: main.php");
    exit();
}
$note_id = intval($_GET['note_id']);
$stmt = $conn->prepare("SELECT title, note FROM notes WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $note_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) die("Note not found!");
$note_data = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Note</title>
<link rel="stylesheet" href="style1.css">
</head>
<body>
<div class="edit-container">
    <form action="edit.php" method="POST" class="edit-form">
        <h1>Edit Note</h1>
        <input type="hidden" name="note_id" value="<?php echo $note_id; ?>">
        <input type="text" name="title" required maxlength="100" 
               value="<?php echo htmlspecialchars($note_data['title'], ENT_QUOTES); ?>" 
               placeholder="Enter title">
        <textarea name="note" required placeholder="Write your note here..."><?php echo htmlspecialchars($note_data['note'], ENT_QUOTES); ?></textarea>
        <button type="submit">Save Changes</button>
        <br>
        <a href="main.php" class="back-link">Back</a>
    </form>
</div>

<a href="main.php">Back</a>
</body>
</html>
