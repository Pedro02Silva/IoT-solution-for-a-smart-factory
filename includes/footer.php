<br>
<footer class="d-flex flex-wrap justify-content-between align-items-center py-3 px-5">
  <p class="col-md-4 mb-0 text-body-secondary">&copy; 2023 <?= WEBSITE_NAME ?>, Inc</p>
  <a href="/" class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
    <svg class="bi me-2" width="40" height="32">
        <use xlink:href="#bootstrap"/>
    </svg>
  </a>
  <ul class="nav col-md-4 justify-content-end">
    <li class="nav-item"><a href="index.php" class="nav-link px-2 text-body-secondary">Home</a></li>

    <?php
        if(isset($_SESSION['username']) && isset($_SESSION['role'])){
          if(getRole($_SESSION['username']) == "admin" || getRole($_SESSION['username']) == "admin_plus"){
            echo "
            <li class='nav-item'><a href='dashboard.php' class='nav-link px-2 text-body-secondary'>Dashboard</a></li>";

            if(getRole($_SESSION['username']) == "admin_plus"){
            echo "
              <li class='nav-item'><a href='manageUsers.php' class='nav-link px-2 text-body-secondary'>Gerenciar Utilizadores</a></li>
              ";
            }
          }
        }
      ?> 
  </ul>
</footer>
