<?php
session_start();
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $_SESSION["error"] = "";
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $cpassword =trim($_POST['cpassword']);
     if($password != $cpassword){
        $_SESSION["error"] = "Password not matched";
         $_SESSION["old_username"] = $username;
         $_SESSION["old_email"] = $email;
        header("Location: Register.php");
        exit();
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $_SESSION["error"] = "Invalid email";
         $_SESSION["old_username"] = $username;
         $_SESSION["old_email"] = $email;
        header("Location: Register.php");
        exit();
    }
        $conn = new mysqli('localhost','root','','note_book');
        if($conn->connect_error){
            die('Connection Failed : '.$conn->connect_error);
        }else{
            $hash_password = password_hash($password, PASSWORD_BCRYPT);
            $sqliCheack = $conn->prepare("select username,email from users where username=? or email=?");
            $sqliCheack->bind_param("ss",$username,$email);
            $sqliCheack->execute();
            if( $sqliCheack->get_result()->num_rows > 0){
                      $_SESSION["error"] = "Username or email already exists";
                       $_SESSION["old_username"] = $username;
                       $_SESSION["old_email"] = $email;
                     header("Location: Register.php");
                     exit();
                }else{
            $sqliCheack->close();
            $token = bin2hex(random_bytes(15)); 
            $stmt = $conn->prepare("insert into users(username,email,password) values(?,?,?)");
            $stmt->bind_param("sss",$username,$email,$hash_password);
            $stmt->execute();

            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $stmt->close();
            $conn->close();
            header('location:main.php');
            exit();
        }}
  
    
}

?>