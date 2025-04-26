<?php
include '../components/connect.php'
    ?>

<?php
function check_email_exists($conn, $email) {
    $stmt = $conn->prepare("SELECT * FROM `tutors` WHERE `email` = ?");
    $stmt->execute([$email]);
    return $stmt;
}

if (isset($_POST['submit'])) {
    $id = unique_id();

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);

    $profession = $_POST['profession'];
    $profession = filter_var($profession, FILTER_SANITIZE_STRING);

    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);

    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);

    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);

    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = unique_id() . '.' . $ext;
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_files/' . $rename;

    $select_tutor = check_email_exists($conn, $email);

    if ($select_tutor -> rowCount()>0) {
        $message[] = 'email already exist';
    } else {
        if ($pass != $cpass) {
            $message[] = 'confirm password not matched';
        } else {
            $insert_tutor = $conn->prepare("INSERT INTO `tutors`(id, name, profession, email, password, image) 
         VALUES (?, ?, ?, ?, ?, ?)");
            $insert_tutor->execute([$id, $name, $profession, $email, $cpass, $rename]);
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'new tutor registered! you can login now';
        }
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
        <img src="../image/fun.jpg" class="form-img" style="left: -1%;">
        <form action="" method="post" enctype="multipart/form-data" class="register">
            <h3>Register Now</h3>
            <div class="flex">
                <div class="col">
                    <p>Your Name <span>*</span></p>
                    <input type="text" name="name" placeholder="Enter your name" maxlength="50" required class="box">

                    <p>Your Profession <span>*</span></p>
                    <select name="profession" required class="box">
                        <option value="" disabled selected>--Select your profession--</option>
                        <option value="developer">Developer</option>
                        <option value="designer">Designer</option>
                        <option value="musician">Musician</option>
                        <option value="biologist">Biologist</option>
                        <option value="teacher">Teacher</option>
                        <option value="engineer">Engineer</option>
                        <option value="lawyer">Lawyer</option>
                        <option value="accountant">Accountant</option>
                        <option value="doctor">Doctor</option>
                        <option value="journalist">Journalist</option>
                        <option value="photographer">Photographer</option>
                        <option value="software developer">Software Developer</option>
                    </select>
                    <p>Your Email <span>*</span></p>
                    <input type="email" name="email" placeholder="Enter your email" maxlength="30" required class="box">
                </div>
                <div class="col">
                    <p>Your Password <span>*</span></p>
                    <input type="password" name="pass" placeholder="Enter your password" maxlength="20" required
                        class="box">
                    <p>Your Password <span>*</span></p>
                    <input type="password" name="cpass" placeholder="Confirm your password" maxlength="20" required
                        class="box">
                    <p>Select Picture<span>*</span></p>
                    <input type="file" name="image" accept="image/*" required class="box">
                </div>
            </div>
            <p class="link">already have and account ? <a href="login.php">login now</p>
            <input type="submit" name="submit" class="btn" value="register now">
        </form>
    </div>
</body>

</html>