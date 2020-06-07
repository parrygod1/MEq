<?php require_once __DIR__ . "/php/userAccount/UserRoles.php"; ?>

<div class="nav-container">
    <nav class="navbar">
        <span class="navbar-toggle" id="js-navbar-toggle">
            <i class="fa fa-bars"></i>
        </span>
        <a href="index.html" class="logo"><span>M&Sigma;q<sup>x</sup></span></a>
        <ul class="main-nav" id="js-menu">
            <li>
                <a href="index.html" class="nav-links">Home</a>
            </li>

<?php
  session_start();
  if ( isset($_SESSION["loggedin"]) && isset($_SESSION["userid"]) ) { ?>
                <?php 
                    if (isset($_SESSION['role']) && $_SESSION['role'] != UserRoles::BANNED){
                ?>
                    <li>
                        <a href="uploadpage.php" class="nav-links">Upload</a>
                    </li>
                <?php } ?>

                <?php 
                    if (isset($_SESSION['role']) && $_SESSION['role'] == UserRoles::ADMIN){
                ?>
                    <li>
                        <a href="adminPublications.php" class="nav-links" style="color:gold">Admin panel</a>
                    </li>
                <?php } ?>

            <li>
                <a href="profilepage.php?id=<?php echo $_SESSION["userid"]?>" class="nav-links">Profile</a>
            </li>

            <li>
                <a href="php/userAccount/logout.php" class="nav-links">Logout</a>
            </li>

            

        </ul>
    </nav>
  </div>

<?php  } else { ?>
            <li>
              <a href="php/userAccount/login.php" class="nav-links">Login</a>
            </li>
          </ul>
    </nav>
  </div> 
  <?php } ?>