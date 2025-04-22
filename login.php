<?php
include 'connect.php';
    session_start();

    if(isset($_POST['submit'])){

        $user = $_POST['username'];
        $pass = $_POST['password'];

        $select = mysqli_query($conn, "SELECT * FROM `users` WHERE username = '$user' AND pass = '$pass'");
        if(mysqli_num_rows($select) > 0){
            $row = mysqli_fetch_assoc($select);
            $_SESSION['user'] = $row['user_id'];

            if($row['role_id'] == 1){
                header('location:productPage.php');
            }elseif($row['role_id'] == 2){

                header('location:admin.php');
            }
            
            mysqli_close($conn);
        } elseif(empty($user) && empty($pass)){
            $message = "Please input username or password.";
        
        } else{
            $message = "Incorrect Username or Password";
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p>
    <?php
        if(isset($message)){
            echo $message;
        }
                ?>
    </p>
    <form action="" method="post" class="flex flex-col gap-4">
        <br>
        <input name="username" type="username" placeholder="Username" class="p-2 rounded-lg border">
        <input name="password" type="password" placeholder="Password" class="p-2 rounded-lg border">
        <button name="submit" class="bg-[#CCC3E9] rounded-xl border text-[#502BD1]">Login</button>
        <br>
    </form>
</body>
</html>