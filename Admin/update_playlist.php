<?php

include '../components/connect.php';
if (isset($_COOKIE['tutor_id'])) {
    $tutor_id = $_COOKIE['tutor_id'];
} else {
    $tutor_id = "";
    header('location:login.php');
}


if (isset($_GET['get_id'])) {
    $get_id = $_GET['get_id'];
} else {
    $get_id = '';
    header('location: playlist.php');
}

if (isset($_POST['update'])) {
    // Sanitize and assign title
    $title = $_POST['title'];
    $title = filter_var($title, FILTER_SANITIZE_STRING);

    // Sanitize and assign description
    $description = $_POST['description'];
    $description = filter_var($description, FILTER_SANITIZE_STRING);

    // Sanitize and assign status
    $status = $_POST['status'];
    $status = filter_var($status, FILTER_SANITIZE_STRING);

    // Update the playlist in the database
    $update_playlist = $conn->prepare("UPDATE `playlist` SET title = ?, desciption = ?, status = ? WHERE id = ?");
    $update_playlist->execute([$title, $description, $status, $get_id]);

    // Handle image update
    $old_image = $_POST['old_image'];
    $old_image = filter_var($old_image, FILTER_SANITIZE_STRING);

    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = unique_id() . '.' . $ext;
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_files/' . $rename;

    if (!empty($image)) {
        if ($image_size > 2000000) {
            $message[] = 'Image size is too large!';
        } else {
            // Update the image in the database
            $update_image = $conn->prepare("UPDATE `playlist` SET thumb = ? WHERE id = ?");
            $update_image->execute([$rename, $get_id]);

            // Move the new image to the designated folder
            move_uploaded_file($image_tmp_name, $image_folder);

            // Delete the old image if it exists and is different from the new one
            if ($old_image != '' && $old_image != $rename) {
                unlink('../uploaded_files/' . $old_image);
            }
        }
    }
    $message[] = 'Playlist updated';
}
    if (isset($_POST['delete'])){
       $delete_id = $_POST['playlist_id'];
       $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);
       $delete_playlist_thumb = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? LIMIT 1");
        $delete_playlist_thumb->execute([$delete_id]);
        $fetch_thumb = $delete_playlist_thumb->fetch(PDO::FETCH_ASSOC);
        if (!empty($fetch_thumb['thumb'])) {
            unlink('../uploaded_files/' . $fetch_thumb['thumb']);
        }

        // Delete bookmarks related to the playlist
        $delete_bookmark = $conn->prepare("DELETE FROM `bookmark` WHERE playlist_id = ?");
        $delete_bookmark->execute([$delete_id]);

        // Delete the playlist
        $delete_playlist = $conn->prepare("DELETE FROM `playlist` WHERE id = ?");
        $delete_playlist->execute([$delete_id]);
        header('location:playlists.php');
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
        <h1 class="heading">update playlist</h1>


        <?php
        $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE id=?");
        $select_playlist->execute([$get_id]);
        if ($select_playlist->rowCount() > 0) {
            while ($fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC)) {
                $playlist_id = $fetch_playlist['id'];

                // Count the videos in the playlist
                $count_videos = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
                $count_videos->execute([$playlist_id]);
                $total_videos = $count_videos->rowCount();

                // Add logic here to use $total_videos or display the playlist
        
                ?>


                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="playlist_id" value="<?= $playlist_id; ?>">
                    <input type="hidden" name="old_image" value="<?= $fetch_playlist['thumb']; ?>">
                    <p>Playlist Status<span>*</span></p>
                    <select name="status" class="box">
                        <option value="" <?= $fetch_playlist['status']; ?> selected disabled><?= $fetch_playlist['status']; ?>
                        </option>
                        <option value="active">Active</option>
                        <option value="deactive">Deactive</option>
                    </select>

                    <p>Playlist Title<span>*</span></p>
                    <input type="text" name="title" maxlength="150" required placeholder="Enter playlist title"
                        value=<?= $fetch_playlist['title']; ?> class="box">

                    <p>Playlist Description<span>*</span></p>
                    <textarea name="description" class="box" placeholder="Write description" maxlength="1000" cols="30"
                        rows="10"><?= $fetch_playlist['desciption']; ?></textarea>

                    <p>Playlist Thumbnail<span>*</span></p>
                    <div class="thumb">
                        <span><?= $total_videos; ?></span>
                        <img src="../uploaded_files/<?= $fetch_playlist['thumb']; ?>">
                    </div>
                    <input type="file" name="image" accept="image/*"class="box">
                    <div class="flex-btn">
                        <input type="submit" name="update" value="update playlist" class="btn">
                        <input type="submit" name="delete" value="delete playlist" class="btn" onclick="
                            return confirm('delete this playlist');">
                        <a href="view_playlist.php?get_id=<?= $playlist_id; ?>"class="btn">view playlist</a>
                    </div>
                </form>
                <?php
            }
        }else{
            echo '<p class = "empty">no playlist added yet! </p>';
        }
        ?>

    </section>

    <?php include '../components/footer.php'; ?>


    <script type="text/javascript" src="../components/admin_script.js">

</body >
</html >