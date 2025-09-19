<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit();
}

$conn = new mysqli('localhost','root','','note_book');
if ($conn->connect_error) die("Connection failed: ".$conn->connect_error);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['note'])) {
    $title = trim($_POST['title']);
    $note = trim($_POST['note']);
    $stmt = $conn->prepare("INSERT INTO notes(user_id, title, note, create_date) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iss", $_SESSION['user_id'], $title, $note);
    $stmt->execute();
    $stmt->close();
    header("Location: main.php");
    exit();
}


$search = '';
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = "%".trim($_GET['search'])."%";
    $stmt = $conn->prepare("SELECT id, title, note, create_date FROM notes WHERE user_id=? AND title LIKE ? ORDER BY create_date DESC");
    $stmt->bind_param("is", $_SESSION['user_id'], $search);
} else {
    $stmt = $conn->prepare("SELECT id, title, note, create_date FROM notes WHERE user_id=? ORDER BY create_date DESC");
    $stmt->bind_param("i", $_SESSION['user_id']);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Note Book</title>
<link rel="stylesheet" href="style1.css">
</head>
<body>
<header>
    <h1>Note Book</h1>
    <nav>
        <form method="GET" action="main.php" class="search-form">
            <input type="text" name="search" placeholder="Search notes..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Search</button>
        </form>
        <a class="logout-btn" href="Logout.php">Logout</a>
    </nav>
</header>

<main>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
    <h2>Add New Note</h2>
    <form action="main.php" method="POST" class="note-form">
        <input type="text" name="title" placeholder="Title" required maxlength="100"><br>
        <textarea name="note" placeholder="Your note..." required></textarea><br>
        <button type="submit">Add Note</button>
    </form>

    <h2>Your Notes</h2>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="note">
            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
            <p><?php echo nl2br(htmlspecialchars($row['note'])); ?></p>
            <small>Created at: <?php echo $row['create_date']; ?></small><br>
            <a href="edit.php?note_id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
            <a href="delete.php?note_id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
        </div>
    <?php endwhile; ?>
</main>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
