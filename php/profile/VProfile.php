<?php
require_once __DIR__ . "/../userAccount/UserRoles.php";

class VProfile {

    private $user;
    private $idUser;

    public function __construct($user, $idUser) {
        $this->user = $user;
        $this->idUser = $idUser;
    }

    public function viewProfile() {
        if($this->user->rowcount() === 0) {
            echo 'Error 404 user not found';
        } else {
            $row = $this->user->fetch(PDO::FETCH_ASSOC);
            ?>
                <img src="<?php echo $row['IMAGE_PATH'] ?>">

                <?php if(isset($_SESSION['userid']) && $this->idUser === $_SESSION['userid']) { ?>
                    <button class="button-regular" id="avatar-button">Change avatar</button>
                <?php } ?>

                <div id="avatar-modal" class="modal">
                    <div class="modal-content">
                        <span class="modal-close">&times;</span>
                        <form action="" method="POST" enctype="multipart/form-data" onsubmit="prepareDiv();">
                            <input type="hidden" id="uploadPhoto" name="uploadPhoto"/>
                                <input class="profile-choose-button" name="image" id="image" type="file" accept="image/*" multiple="multiple" onchange='loadFile(event); showFiles();' />
                                <input name="subButton" id="subButton" class="profile-upload-button" type="submit" value="Upload photo" />
                        </form>
                    </div>
                </div>

                <div class="user-name">
                    <h2><?php echo $row['USERNAME']; if($row['ROLE'] == UserRoles::BANNED) echo ' - BANNED'; ?> </h2>
                </div>

                <div class="user-info" id="user-points">
                    <h2><?php echo "Points: ".$row['SCORE'] ?></h2>
                </div>

                <div class="user-info" id="user-publications">
                    <h2><?php echo "Publications: " . $row['DOC'];?></h2>
                </div>

                <?php if(isset($_SESSION['userid']) && $this->idUser === $_SESSION['userid']) { ?>
                    <button class="button-regular" id="delete-user-button">Delete your account</button>
                <?php } ?>
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


<!--            <div class = "profile-photo">-->
<!--                <p>-->
<!--                <form action="" method="POST" enctype="multipart/form-data" onsubmit="prepareDiv();">-->
<!--                    <input type="image" src="--><?php //echo $row['IMAGE_PATH'] ?><!--" alt="img" class="profile-photo" id="uploadPhoto" name="uploadPhoto" >-->
<!--                    <input type="hidden" id="uploadPhoto" name="uploadPhoto"/>-->
<!--                    --><?php //if($this->idUser === $_SESSION['userid']) { ?>
<!--                        <input class="profile-choose-button" name="image" id="image" type="file" accept="image/*" multiple="multiple" onchange='loadFile(event); showFiles();' />-->
<!--                        <input name="subButton" id="subButton" class="profile-upload-button" type="submit" value="Upload photo" />-->
<!--                    --><?php //} ?>
<!--                </form>-->
<!--                </p>-->
<!--            </div>-->
<!--            <div class = "profile-stats">-->
<!--                --><?php //echo "Points: ".$row['SCORE'] ?>
<!---->
<!--                <br>-->
<!---->
<!--                <div id="profile-stats2">-->
<!--                    --><?php //echo "Publications: " . $row['DOC'];
//                    ?>
<!--                </div>-->
<!--            </div>-->
<!--            <div class = "profile-name">-->
<!--                --><?php //echo $row['USERNAME'] ?>
<!--            </div>-->
<!---->
<!--            --><?php //if($this->idUser === $_SESSION['userid']) { ?>
<!--                <form action="" method="POST" enctype="multipart/form-data" onsubmit="prepareDiv();">-->
<!--                    <input name="deleteAcc" id="deleteAcc" class="delete-button" type="submit" value="Delete account" />-->
<!--                </form>-->
<!--            --><?php //} ?>


            <script>
                var loadFile = function(event) {
                    var output = document.getElementById('uploadPhoto');
                    output.src = URL.createObjectURL(event.target.files[0]);
                    output.onload = function() {
                        URL.revokeObjectURL(output.src);
                    }
                };
            </script>
            <script>
                function prepareDiv() {
                    document.getElementById("subButton").value = document.getElementById("uploadPhoto").value;

                }

                function showFiles() {
                    var div = document.getElementById('uploadPhoto');
                    var fi = document.getElementById('image');

                    if (fi.files.length > 0) {
                        for (var i = 0; i <= fi.files.length - 1; i++) {

                            var reader = new FileReader();
                            reader.onload = function(e) {
                                var img = new Image();
                                img.src = e.target.result;

                                div.value = img.src;
                            };
                            reader.readAsDataURL(fi.files.item(i));
                        }
                    }
                }
            </script>



            <?php
        }
    }
}
?>