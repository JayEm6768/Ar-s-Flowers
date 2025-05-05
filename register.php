<?php

include 'connect.php';

if (isset($_REQUEST['submit'])) {

    $name = $_POST['fullName'];
    $email = $_POST['email'];
    $contact = $_POST['mobileNum'];
    $user = $_POST['userName'];
    $pass = $_POST['password'];
    $role = 1;

    $select = mysqli_query($conn, "SELECT * FROM `users` WHERE username = '$user'");

    $query = "INSERT INTO `users`(`name`, `email`, `phone`, `role_id`, `username`, `pass`) VALUES ('$name', '$email','$contact','$role', '$user','$pass')";

    if (mysqli_num_rows($select) > 0) {
        $message = 'Username already exist';
    } elseif (empty($name) && empty($contact) && empty($user) && empty($pass)) {
        $message = 'Please input the empty spaces.';
    } else {
        $insert = mysqli_query($conn, $query);
        if ($insert) {
            $message = "Registered Successfully";
            mysqli_close($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="./output.css" rel="stylesheet">
</head>

<body>

    <section class="bg-[#CCC3E9] min-h-screen flex items-center justify-center bg-img">
        <!-- container -->
        <div class="bg-[#EAE4FE] flex rounded-lg shadow-lg max-w-3xl p-5">
            <!-- form -->
            <div class="sm:w-1/2 px-16">
                <h2 class="text-[#502BD1] text-2xl font-bold">Register</h2>
                <p class="text-[#502BD1] text-sm mt-4">Already have an account?</p><a href="login.php" class="text-[#502BD1] italic font-bold">Login Now!</a>
                <p class="text-red-600 text-xl mt-4"><?php
                                                        if (isset($message)) {
                                                            echo $message;
                                                        }
                                                        ?></p>
                <form action="register.php" method="post" class="flex flex-col gap-4">
                    <br>
                    <input name="fullName" type="text" placeholder="Full name" class="p-2 rounded-lg border">
                    <input name="email" type="text" placeholder="Email" class="p-2 rounded-lg border">
                    <input name="mobileNum" type="text" placeholder="Mobile Number" class="p-2 rounded-lg border">
                    <input name="userName" type="username" placeholder="Username" class="p-2 rounded-lg border">
                    <input name="password" type="password" placeholder="Password" class="p-2 rounded-lg border">
                    <button name="submit" class="bg-[#CCC3E9] rounded-xl border text-[#502BD1]">Register</button>
                    <br>
                </form>
            </div>

            <!-- img -->
            <div class="sm:block hidden">
                <img src="blog.webp" alt="img" class="rounded-lg">
            </div>
        </div>
    </section>

</body>

</html>