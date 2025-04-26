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

<header class="header">

    <section class="flex">
        <a href="dashboard.php"><img src="../image/studyvault.png" width="130px"> </a>
        <!-- <form action="search_page.php" method="post" class="search-form">
            <input type="text" name="search" placeholder="search here.." required maxlength="100"> <button type="submit"
                class="bx bx-search-alt-2" name="search_btn"></button>
        </form> -->

        <form action="search_page.php" method="post" class="search-form">
            <input type="text" name="search" placeholder="search here.." required maxlength="100">
            <button type="submit" name="search_btn">
                <i class="bx bx-search-alt-2"></i>
            </button>
        </form>


        <div class="icons">
            <div id="menu-btn" class="bx bx-list-plus"></div>
            <div id="search-btn" class="bx bx-search-alt-2-plus"></div>
            <div id="user-btn" class="bx bxs-user"></div>
        </div>

        <div class="profile">
            <?php
            $select_profile = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
            $select_profile->execute([$tutor_id]);
            if ($select_profile->rowCount() > 0) {
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                ?>
                <img src="../uploaded_files/<?= $fetch_profile['image']; ?>">
                <h3><?= $fetch_profile['name']; ?></h3>
                <h1><?= $fetch_profile['profession']; ?><br></h1>

                <div id="flex-btn">
                    <a href="profile.php" class="btn">view profile</a>
                    <a href="../components/admin_logout.php" onclick="return confrim('logout from thsi website?');"
                        class="btn">logout</a>
                </div>

                <?php
            } else {
                ?>
                <h3> Pleasae Login or Register </h3>
                <div id="flex-btn">
                    <a href="login.php" class="btn">login</a>
                    <a href="register.php" class="btn">register</a>
                </div>
            <?php } ?>
            <!-- <script src="../js/admin_script.js"></script> -->
        </div>
    </section>
</header>


<div class="side-bar">
    <div class="profile">
        <?php
        $select_profile = $conn->prepare("SELECT * FROM `tutors` WHERE id =?");
        $select_profile->execute([$tutor_id]);
        if ($select_profile->rowCount() > 0) {
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>

            <img src="../uploaded_files/<?= $fetch_profile['image']; ?>">
            <h3><?= $fetch_profile['name']; ?></h3>
            <p><?= $fetch_profile['profession']; ?></p>
            <a href="profile.php" class="btn">view profile</a>

        <?php } else { ?>
            <h3>please login or register</h3>
            <div id="flex-btn">
                <a href="login.php" class="btn">login</a>
                <a href="register.php" class="btn">register</a>
            </div>

        <?php } ?>

    </div>

    <nav class="navbar">
        <a href="dashboard.php"><i class="bx bxs-home-heart"></i><span>Home</span></a>
        <a href="playlists.php"><i class="bx bxs-receipt"></i><span>Playlist</span></a>
        <a href="contents.php"><i class="bx bxs-graduation"></i><span>Content</span></a>
        <a href="comments.php"><i class="bx bxs-home-heart"></i><span>Comments</span></a>
        <a href="../comments/admin_logout.php" onclick="return confirm('logout from this website?');">
            <i class="bx bx-log-in-circle"></i><span>Logout</span></a>
    </nav>

    <script src="../js/admin_script.js"></script>
</div>