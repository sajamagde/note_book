<?php
session_start();
if (isset($_COOKIE['rememberme'])) {
    header('Location: main.php');
    exit();
}
if (!isset($_SESSION["error"])) {
    $_SESSION["error"] = "";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
    
    </header>
    <main>
        <section>
            <h1>Note Book</h1>
            <h2>Login</h2>

            <?php if (!empty($_SESSION["error"])): ?>
                <p style="color:red;"><?php echo $_SESSION["error"]; ?></p>
                <?php $_SESSION["error"] = ""; endif; ?>

            <form action="LogControl.php" method="POST">
                <div class="username-container">
                    <label for="username">Username/Email:</label>
                    <input type="text" id="username" name="username" placeholder="Username/Email" required
                        maxlength="20" value="<?php
                        if (isset($_SESSION['old_input'])) {
                            echo htmlspecialchars($_SESSION['old_input']);
                            unset($_SESSION['old_input']); 
                        } elseif (isset($_SESSION['username'])) {
                            echo htmlspecialchars($_SESSION['username']);
                        } elseif (isset($_SESSION['email'])) {
                            echo htmlspecialchars($_SESSION['email']);
                        }
                        ?>">
                </div>
                <div class="password-container">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Password" required maxlength="30"
                        minlength="10">
                </div>

                <div class="remember-me-container">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember Me</label>
                </div>

                <p>Don't have an account? <a href="Register.php">Register</a></p>
                <button type="submit">Login</button>
            </form>
        </section>
    </main>
</body>

</html>