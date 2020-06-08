<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="project, infoiasi, web">
    <title>Profile</title>
    <link rel="stylesheet" type="text/css" href="css/global.css">
    <link rel="stylesheet" type="text/css" href="css/navbar.css">
    <link rel="stylesheet" type="text/css" href="css/profilepage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<?php
include "navbar.php"; ?>
<div class="content-container">
    <div id="profile-information">
        <?php
            include "./php/profile/profile.php";
        ?>
    </div>
</div>

<script src="js/navbar.js"></script>
<script type="text/javascript" src="https://latex.codecogs.com/latexit.js"></script>
<script src="js/mathjax-reset.js"></script>

</body>
</html>
