<?php
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $_SESSION["error"] = ""; 

    $conn = new mysqli('localhost','root','','note_book');
    if($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id, username, email, password FROM users WHERE username=? OR email=?");
    $stmt->bind_param("ss", $input, $input);
    $stmt->execute();
    $stmt->store_result(); 
    $stmt->bind_result($id, $db_username, $db_email, $db_password);
    $stmt->fetch();

    if($stmt->num_rows == 1) {
        if(password_verify($password, $db_password)) {
             $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $db_username;
            $_SESSION['email'] = $db_email;
            if(isset($_POST['remember'])) {
                $token = bin2hex(random_bytes(32));
                $hash_token = password_hash($token, PASSWORD_BCRYPT);
                $sqliToken = $conn->prepare("INSERT into  tokens(user_id,token) VALUES (?, ?)");  
                $sqliToken->bind_param("is",$id,$hash_token);  
                 $sqliToken->execute();
                setcookie("rememberme", $token, time() + (86400 * 30), "/"); 
                 $sqliToken->close();
            }
           
            $stmt->close();
            $conn->close();
          
            $_SESSION['username'] = $db_username;

            header('Location: main.php');
            exit();
        } else {
            $_SESSION["error"] = "Wrong password or username";
            $_SESSION["old_input"] = $input;
        }
    } else {
        $_SESSION["error"] = "User not found";
        $_SESSION["old_input"] = $input;
    }

    $stmt->close();
    $conn->close();
    header("Location: Login.php");
    exit();
}
?>

