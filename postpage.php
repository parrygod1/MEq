<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post</title>
    <link rel="stylesheet" type = "text/css" href="css/postpage.css" />
    <link rel="stylesheet" type = "text/css" href="css/comment.css" />
    <link rel="stylesheet" type = "text/css" href="css/global.css" />
    <link rel="stylesheet" type = "text/css" href="css/navbar.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="content">
    <?php include 'php/posts/content_query.php'; ?>
        
        <p id="comment-title">Comments</p>
        <hr class="section-divider-bar">
        
        <div class="comment-wrapper">
            <?php include 'php/comments/comments.php'; ?>
			
            <p>Post comment:</p>
            <div class="container-boxwrapper">
                <div class="comment-boxwrapper">
                    <form  method="POST" action="postpage.php">
                        <div class="comment-boxwrapper">
                            <textarea class="textinput" placeholder="Comment" name="comment"></textarea>
                        </div>
                        <div id="comment-preview"></div>
                        <div style="text-align: center; align-content: center;">
                            <input name="actiune" class="button-regular" type="submit" value="Post Comment" />
                            <button style="text-align:center" class="button-regular" onclick="enablePreview(); return false;">Preview</button>
                        </div> 
                    </form>
                    
                </div>
            </div>
        
        </div>
        
    </div>
    <script src="js/navbar.js"></script>
    <script type="text/javascript" src="http://latex.codecogs.com/latexit.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
    <script src="js/mathjax-reset.js"></script>

</body>