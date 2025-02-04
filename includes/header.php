<header>
  <nav class="navbar navbar-expand-lg fixed-top bg-white shadow">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand p-0 me-0 me-lg-2" href="index.php">
        <img src="./images/estg.png" alt="ESTG" width="180" title="ESTG">
      </a>
      <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header px-4">
          <h5 class="offcanvas-title" id="offcanvasNavbarLabel">ESTG</h5>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-4 pt-0 p-lg-0">
          <ul class="navbar-nav me-auto flex-row flex-wrap">
            <li class="nav-item col-12 col-lg-auto px-0 px-lg-2">
              <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="vr d-none d-lg-block"></li>
            
            <?php
              if(isset($_SESSION['username']) && isset($_SESSION['role'])){
                if(getRole($_SESSION['username']) == "admin" || getRole($_SESSION['username']) == "admin_plus"){
                  echo "
                  <li class='nav-item col-12 col-lg-auto px-0 px-lg-2'>
                  <a class='nav-link' href='dashboard.php'>Dashboard</a></li>
                  <li class='vr d-none d-lg-block'></li>";

                  if(getRole($_SESSION['username']) == "admin_plus"){
                    echo "
                    <li class='nav-item col-12 col-lg-auto px-0 px-lg-2'>
                    <a class='nav-link' href='manageUsers.php'>Gerenciar Utilizadores</a></li>
                    <li class='vr d-none d-lg-block'></li>";
                  }
                }
              }            
            ?>
          </ul>
        </div>
      </div>
      <div class="d-flex">
          <ul class="navbar-nav me-auto flex-row flex-wrap">
            <li class="nav-item col-12 col-lg-auto px-0 px-lg-2">
              <?php
                if(!isset($_SESSION['username'])){
                  echo " <a class='nav-link' href='login.php'>Login</a>";
                }else{
                  echo " <a class='nav-link' href='logout.php'>Logout</a>";
                }
              ?>
            </li>
          </ul>
      </div>
    </div>
  </nav>
</header>
<br><br><br>
