<?php

include '../components/connect.php';
if (isset($_COOKIE['tutor_id'])) {
    $tutor_id = $_COOKIE['tutor_id'];
} else {
    $tutor_id = "";
    header('location:login.php');
}

if (isset($_POST['submit'])) {
    $id = unique_id();
    $title = $_POST['title'];
    $title = filter_var($title, FILTER_SANITIZE_STRING);
    $description = $_POST['description'];
    $description = filter_var($description, FILTER_SANITIZE_STRING);
    $status = $_POST['status'];
    $status = filter_var($status, FILTER_SANITIZE_STRING);

    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = unique_id() . '.' . $ext;

    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_files/' . $rename;


    $add_playlist = $conn->prepare("INSERT INTO `playlist` (id, tutor_id, title, desciption, thumb, status) VALUES(?,?,?,?,?,?)");

    $add_playlist->execute([$id, $tutor_id, $title, $description, $rename, $status]);
    move_upLoaded_file($image_tmp_name, $image_folder);
    $message[] = 'new playlist created';
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
    <title>add playlists</title>

    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

</head>

<body>

    <?php include '../components/admin_header.php'; ?>

    <section class="playlist-form">
        <h1 class="heading">create playlist</h1>


        <form action="" method="post" enctype="multipart/form-data">
            <p>Playlist Status<span>*</span></p>
            <select name="status" class="box">
                <option value="" selected disabled>--- Select Status ---</option>
                <option value="active">Active</option>
                <option value="deactive">Deactive</option>
            </select>

            <p>Playlist Title<span>*</span></p>
            <input type="text" name="title" maxlength="150" required placeholder="Enter playlist title" class="box">

            <p>Playlist Description<span>*</span></p>
            <textarea name="description" class="box" placeholder="Write description" maxlength="1000" cols="30"
                rows="10"></textarea>

            <p>Playlist Thumbnail<span>*</span></p>
            <input type="file" name="image" accept="image/*" required class="box">
            <input type="submit" name="submit" value="create playlist" class="btn">
        </form>

    </section>

    <?php include '../components/footer.php'; ?>


    <script type="text/javascript" src="../components/admin_script.js">

</body >
</html >