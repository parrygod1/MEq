<?php

class VAdmin
{
    private $data;
    private $maxPerPage;
    private $docCount;

    public function __construct($data, $param)
    {
        $this->data = $data;
        $this->maxPerPage = $param[0];
        $this->docCount = $param[1];
    }

    public function display()
    {
        $page = empty($_GET['page']) ? 1 : $_GET['page'];
        $this->maxPerPage;
        $this->docCount = $this->docCount->fetch(PDO::FETCH_ASSOC)['docCount'];
        $publications = $this->data->fetchAll(PDO::FETCH_ASSOC);
        $pages = ceil($this->docCount / $this->maxPerPage); ?>

        <div id="content-panel">
            <div class="side-bar" id="js-side-bar">
                <form action="" method="POST" enctype="multipart/form-data">
                    <ul id="side-nav">
                        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                        <li><a href="adminPublications.php">Publications</a></li>
                        <li><a href="adminUsers.php">Users</a></li>
                        <li><a href="../Docs/">Docs</a></li>
                    </ul>
                </form>
            </div>

            <span class="side-bar-mobile" id="js-side-bar-mobile" onclick="toggleSidebar()">
                <i class="fa fa-user-secret"></i>
            </span>

            <div class="main-content">
                <button onclick='window.location = "php/admin/publications.php?action=exportDocs"'>Export Publications
                    CSV
                </button>
                <?php foreach ($publications as $publication): ?>
                    <div class="admin-box">
                        <div class="admin-box-top"><?php echo $publication['NAME'] ?></div>
                        <div class="admin-box-panel">
                            <div class="admin-box-panel-write">
                                <?php echo $publication['DESCRIPTION'] ?>
                            </div>
                            <div style="display: flex">
                                <a class="view-doc" href="postpage.php?id=<?php echo $publication['ID'] ?>"><i
                                            class="fa fa-eye"></i></a>
                                <a class="check-doc"
                                   href="adminPublications.php?decision=accepted&id=<?php echo $publication['ID'] ?>"><i
                                            class="fa fa-check"></i></a>
                                <a class="refuse-doc"
                                   href="adminPublications.php?decision=refused&id=<?php echo $publication['ID'] ?>"><i
                                            class="fa fa-ban"></i></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div id="pages">
                    <?php for ($x = 1; $x <= $pages; $x++): ?>
                        <button <?php if ((int)$page === $x) {
                            echo 'class="selected"';
                        } ?> onclick="window.location.href='?page=<?php echo $x; ?>';"><?php echo $x; ?></button>
                    <?php endfor; ?>
                </div>

            </div>
        </div>

        <?php
    }

    public function displayUserSearch()
    {
        ?>
        <div id="content-panel">
            <div class="side-bar" id="js-side-bar">
                <form action="" method="POST" enctype="multipart/form-data">
                    <ul id="side-nav">
                        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                        <li><a href="adminPublications.php">Publications</a></li>
                        <li><a href="adminUsers.php">Users</a></li>
                        <li><a href="../Docs/">Docs</a></li>
                    </ul>
                </form>
            </div>
            <span class="side-bar-mobile" id="js-side-bar-mobile" onclick="toggleSidebar()">
                <i class="fa fa-user-secret"></i>
            </span>
            <div class="main-content">
                <div class="util-container">
                    <label for="fname">Username:</label>
                    <input type="text" id="searchbar-name" name="fname" placeholder="string">
                    <label for="fid">Id:</label>
                    <input type="text" id="searchbar-id" name="fid" placeholder="number">
                    <button id="search-button">Search</button>
                    <button id="export-button"
                            onclick='window.location = "php/admin/adminUsers.php?action=exportUsers"'>Export Users CSV
                    </button>
                    <div style="clear: both;"></div>
                </div>

                <div id="search-results"></div>
                <div id="pages"></div>
            </div>
        </div>


        <?php
    }
}

?>