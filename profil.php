<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MEQ</title>
    <link rel="stylesheet" href="css/resource/profil.css">
    <link rel="stylesheet" href="css/style_global.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/style_search.css">
</head>
<body>
<?php
include "navbar.php"; ?>

<div class="content-container">

    <div id="profile-information">

        <img src="https://www.gravatar.com/avatar/?d=mp&s=190">

        <button class="button-regular" id="avatar-button">Change avatar</button>

        <div id="avatar-modal" class="modal">
            <div class="modal-content">
                <span class="modal-close">&times;</span>
                <form action="/action_page.php">
                    <label for="myfile">Select a file:</label><br>
                    <input type="file" id="myfile" name="myfile"><br><br>
                    <input type="submit">
                </form>
            </div>
        </div>

        <div class="user-name">
            <h2>Dorin Miron</h2>
        </div>

        <div class="user-info" id="user-points">
            <h2>8 points</h2>
        </div>

        <div class="user-info" id="user-publications">
            <h2>2 publications</h2>
        </div>

        <button class="button-regular" id="delete-user-button">Delete your account</button>

    </div>

    <div id="user-posts">
        <hr class="search-divider-bar">
        <div class="post">
            <div class="post-desc">
                <a class="post-title" href="postpage.php?id=1">Pythagorean Theorem</a>
                <div class="post-shortdesc">The Pythagorean theorem, also known as Pythagoras' theorem, is a fundamental
                    relation in Euclidean geometry among the three sides of a right triangle.
                </div>
                <div class="post-date">Posted on 2020-04-09</div>
            </div>
        </div>
        <hr class="search-divider-bar">
    </div>

    <script src="js/avatar-modal.js"></script>
</body>
</html>