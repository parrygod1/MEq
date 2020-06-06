<?php
require_once __DIR__ . "/../userAccount/UserRoles.php";

class VProfile {

    private $user;
    private $idUser;
    private $publications;
    private $maxPerPage;

    public function __construct($parameters) {
        $this->user = $parameters[0];
        $this->idUser = $parameters[1];
        $this->publications = $parameters[2];
        $this->maxPerPage = $parameters[3];
    }

    public function viewProfile() {
        $row = $this->user->fetch(PDO::FETCH_ASSOC);
        if($row['ID'] == null) {
            echo 'Error 404 user not found';
        } else {
            $publications = $this->publications->fetchAll(PDO::FETCH_ASSOC);
            $pages = ceil($row['DOC'] / $this->maxPerPage);
            ?>
                <img src="<?php echo $row['IMAGE_PATH'] ?>">
                <?php if(isset($_SESSION['userid']) && $this->idUser == $_SESSION['userid']) { ?>
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
                    <h2><?php echo "Points: ".$row['SCORE']; ?></h2>
                </div>

                <div class="user-info" id="user-publications">
                    <h2><?php echo "Publications: " . $row['DOC']; ?></h2>
                </div>

                <?php if(isset($_SESSION['userid']) && $this->idUser == $_SESSION['userid']) { ?>
                    <button class="button-regular" id="delete-user-button" onclick='location.href="profilepage.php?action=sendDelMail"'>Delete your account</button>
                <?php } ?>
            </div>

            <div id="publications-list">
                <?php foreach($publications as $publication): ?>
                    <div class="publication">
                        <a class="publication-title" href="postpage.php?id=<?php echo $publication['ID']; ?>">
                            <h2><?php echo $publication['NAME']; ?></h2>
                        </a>
                        <p><?php echo $publication['DESCRIPTION']; ?></p>
                    </div>
                <?php endforeach; ?>
                <div class="pagination">
                    <?php if(!empty($_GET['page']) && $_GET['page'] > 1) { ?>
                        <a href="?id=<?php echo $this->idUser; ?>&page=<?php echo $_GET['page'] - 1; ?>">&lt;</a>
                    <?php } if((empty($_GET['page']) ? 1 : $_GET['page']) < $pages) { ?>
                        <a href="?id=<?php echo $this->idUser; ?>&page=<?php echo (empty($_GET['page']) ? 1 : $_GET['page']) + 1; ?>">&gt;</a>
                    <?php } ?>
                </div>
            </div>

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

    public function viewMailConfirmation(){
    ?>
        <div>A confirmation email was sent to your address!</div>
    <?php
    }
}
?>