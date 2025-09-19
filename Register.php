<?php
session_start();

if(!isset($_SESSION["error"])){
    $_SESSION["error"] = "";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>

    </header>
    <main>
        <section>
            <h1>Note Book</h1>
            <h2>Register</h2>
            <?php if(!empty($_SESSION["error"])): ?>
        <p style="color:red;"><?php echo $_SESSION["error"]; ?></p>
        <?php $_SESSION["error"] = "";?>
    <?php endif; ?>

            <form action="RegControl.php" method="POST">
                <div class="username-container">
                    <label for="username">Username:</label>
                    <br>
                    <input type="text" id="username" name="username" placeholder="Username" required maxlength="20" 
                     value="<?php 
                   if (isset($_SESSION['old_username'])) {
                       echo htmlspecialchars($_SESSION['old_username']); 
                       unset($_SESSION['old_username']);
                   } elseif (isset($_SESSION['username'])) {
                       echo htmlspecialchars($_SESSION['username']);
                   }; ?>">
                </div>
                <div class="email-container">
                    <label for="email">Email:</label>
                    <br>
                    <input type="email" id="email" name="email" placeholder="Email" required  value="<?php 
                   if (isset($_SESSION['old_email'])) {
                       echo htmlspecialchars($_SESSION['old_email']); 
                       unset($_SESSION['old_email']);
                   } elseif (isset($_SESSION['email'])) {
                       echo htmlspecialchars($_SESSION['email']);
                   }; ?>">
                </div>
                <div class="password-container">
                    <label for="password">Password:</label>
                    <br>
                    <input type="password" id="password" name="password" placeholder="Password" required maxlength="30"
                        minlength="10">
                </div>
                <div class="confirm-password-container">
                    <label for="confirm-password">Confirm Password:</label>
                    <br>
                    <input type="password" id="confirm-password" name="cpassword" placeholder="Confirm Password"
                        required>
                </div>
                <p>Already have an account? <a href="Login.php">Login</a></p>
                <button type="submit">Register</button>

            </form>


        </section>
    </main>
</body>

</html>
