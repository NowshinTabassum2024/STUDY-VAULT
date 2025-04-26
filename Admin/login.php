<?php
include '../components/connect.php'
    ?>

<?php

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);

    $pass = $_POST['pass'];

    $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email=? LIMIT 1");
    $select_tutor->execute([$email]);
    $row = $select_tutor->fetch(PDO::FETCH_ASSOC);

    if ($select_tutor->rowCount() > 0 ) {
        setcookie('tutor_id', $row['id'], time() + 60 * 60 * 24 * 30, '/');
        header('location: dashboard.php');
        
    } else {
        $message[] = 'incorrect email or password';
    }
}


?>


<style type="text/css">
    <?php include '../css/admin_style.css'; ?>
</style>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin login</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body>
    <?php

    if (isset($message)) {
        foreach ($message as $message) {
            echo '
              <div class="message">
              <span>' . $message . '</span>
              <i class="bx bx-x" onclick="this.parentElement.remove();"></i> 
              </div> 
        ';
        }
    }
    ?>

    <div class="form-container">
        <img src="../image/fun.jpg" class="form-img" style="left: 5%;">
        <form action="" method="post" enctype="multipart/form-data" class="login">
            <h3>Login Now</h3>
            <p>Your Email <span>*</span></p>
            <input type="email" name="email" placeholder="Enter your email" maxlength="30" required class="box">
            <p>Your Password <span>*</span></p>
            <input type="password" name="pass" placeholder="Confirm your password" maxlength="20" required class="box">

            <p class="link">do not have an account ? <a href="register.php">register now</p>
            <input type="submit" name="submit" class="btn" value="login now">
        </form>
    </div>
</body>

</html>