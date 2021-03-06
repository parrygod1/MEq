<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="project, infoiasi, web">
    <title>Home</title>
    <link rel="stylesheet" type = "text/css" href="css/navpanel.css" />
    <link rel="stylesheet" type = "text/css" href="css/global.css" />
    <link rel="stylesheet" type = "text/css" href="css/search.css" />
    <link rel="stylesheet" type = "text/css" href="css/navbar.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body>
<?php include 'navbar.php';
?>
    <div id="content-panel">
            <nav class="side-bar" id="js-side-bar">
                <ul id="side-nav">
                    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                    <li><a href="javascript:searchNew()">New</a> </li>
                    <li><a href="javascript:searchTop()">Top</a> </li>
                    <li><a href="javascript:enableSearch()">Search</a></li>
                    <li><a href="javascript:enableQuizSearch()">Quizzes</a></li>
                </ul>
            </nav>
            <span class="side-bar-mobile" id="js-side-bar-mobile" onclick="toggleSidebar()">
                <i class="fa fa-list-ul"></i>
            </span>
            <div class="main-content">
                <div id="search-container">
                    <input type="text" id="searchbar" name="fname" placeholder="Search for a publication...">
                    <input type="text" id="searchbar-quiz" placeholder="Search for a quiz...">
                </div>

                <div id="content"></div>
                <div id="pages"></div>

            </div>
    </div>


<script src="js/navbar.js"></script>
<script src="https://latex.codecogs.com/latexit.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
<script src="js/posts/doc_ajaxsearch.js"></script>
<script src="js/sidePanel.js"></script>

</body>
</html>