<?php

class VAdmin {
    private $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function display() {
        ?>


        <div id="admin-panel">
            <div class="side-bar" id="js-side-bar">
                <form action="" method="POST" enctype="multipart/form-data">
                    <ul id="side-nav">
                        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                        <li><a href="adminPublications.php">Publications</a> </li>
                        <li><a href="adminUsers.php">Give admin</a> </li>
                        <li><a href="admin.php">Permissions</a> </li>
                    </ul>
                </form>
            </div>
            <span class="side-bar-mobile" id="js-side-bar-mobile" onclick="toggleSidebar()">
                <i class="fa fa-user-secret"></i>
            </span>
            <div class="admin-content">

                <?php
                if($this->data !== null)
                    while($row = $this->data->fetch(PDO::FETCH_ASSOC)){
                ?>
                <div class="admin-box">
                    <div class="admin-box-top"><?php echo $row['NAME']?></div>
                    <div class="admin-box-panel">
                        <div class="admin-box-panel-write">
                            <?php echo $row['DESCRIPTION']?>
                        </div>
                        <div style="display: flex">
                            <a class="view-doc" href="postpage.php?id=<?php echo $row['ID']?>"><i class="fa fa-eye"></i></a>
                            <a class="check-doc" href="adminPublications.php?decision=accepted&id=<?php echo $row['ID']?>"><i class="fa fa-check"></i></a>
                            <a class="refuse-doc" href="adminPublications.php?decision=refused&id=<?php echo $row['ID']?>"><i class="fa fa-ban"></i></a>
                        </div>
                    </div>
                </div>

                <?php
                        }
                ?>
            </div>
        </div>

    <?php
    }

    public function displayUserSearch(){
        ?>
        <div id="admin-panel">
            <div class="side-bar" id="js-side-bar">
                <form action="" method="POST" enctype="multipart/form-data">
                    <ul id="side-nav">
                        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                        <li><a href="adminPublications.php">Publications</a> </li>
                        <li><a href="adminUsers.php">Give admin</a> </li>
                        <li><a href="admin.php">Permissions</a> </li>
                    </ul>
                </form>
            </div>
            <span class="side-bar-mobile" id="js-side-bar-mobile" onclick="toggleSidebar()">
                <i class="fa fa-user-secret"></i>
            </span>
            <div class="admin-content">
                <div class="search-container">
                    <label for="fname">Username:</label>
                    <input type="text" id="searchbar-name" name="fname" placeholder="string">
                    <label for="fid">Id:</label>
                    <input type="text" id="searchbar-id" name="fid" placeholder="number">
                </div>

                <div id="search-results"></div>

                <!--<div id="admin-box">
                    <div class="admin-box-top"> </div>
                    <div class="admin-box-panel">
                        <div class="admin-box-panel-write">
                            
                        </div>
                        <div style="display: flex">
                        </div>
                    </div>
                </div>-->

            </div>
        </div>


    <?php
    }
}
?>