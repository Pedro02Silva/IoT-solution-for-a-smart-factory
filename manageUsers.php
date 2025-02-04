<?php
// Inclui os arquivos de configuração e funções
require_once "./config.php";
require_once AUXILIAR_FUNCTIONS . "users_functions.php";
session_start(); // Inicio da sessão

if(!isset($_SESSION['username']) ){ // Caso a sessão não esteja ativa
    header("refresh:5;url=index.php"); // Será redirecionado após 5 segundos para index.php
    die("Acesso restrito!"); // E matar o processo do manageUsers.php
}else{
    if(getRole($_SESSION['username']) != "admin_plus"){ // Se o usuário for um membro, redireciona para a página de login após 5 segundos
        header("refresh:5;url=index.php"); // Será redirecionado após 5 segundos para index.php
        die("Acesso restrito!"); // E matar o processo da manageUsers.php
    }
}

// Obtém a lista de utilizadores
$usersSrc = glob("./users/*/");

// Criar um array para armazenar o nome de utilizadores
$users = array();

// Percorre a lista de utilizadores
foreach ($usersSrc as $user) {
  $username = explode("/", $user)[2]; // Separar a string por / e obter o nome do utilizador

  if(getRole($username) != "admin_plus"){ // Se não for admin_plus, pode ser editado
    $users[] = $username;
  }
}
unset($usersSrc);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">
    <link rel="icon" type="image/x-icon" href="<?= IMAGES_PATH ?>favicon.png">
    <link rel="stylesheet" href="<?= CSS_PATH ?>style.css">
    <title><?= WEBSITE_NAME ?></title>
</head>
<body>
  <?php include INCLUDES_PATH."header.php"; ?>
  
  <div class="container mt-5">    
        <div class="card box">
            <div class="card-body">
                <img class="float-end img-fluid" style="width: 300px;" src="<?= IMAGES_PATH ?>estg.png" alt="logo_politecnico_leiria">
                <h1 class="card-title d-inline-block"><?= MANAGE_USERS_TITLE ?></h1>
                <p class="card-text">Bem vindo <strong><span class="text-uppercase">
                            <?php echo $_SESSION['username'];?></span></strong>
                    <br><br><?= CADEIRA ?>
                </p>
            </div>
        </div>
    </div>

  <div class="container mt-5">
    <div class="card">
      <div class="card-body">
        <table class="table">
          <thead>
            <tr>
              <th scope="col"><?= NOME_TH ?></th>
              <th scope="col"><?= CARGO_TH ?></th>
              <th scope="col"><?= OPCOES_TH ?></th>
            </tr>
          </thead>
          <tbody>
            <?php
              if(count($users) > 0){
                foreach($users as $username){ 
                  if(getRole($username) != "admin_plus")
                  {
                    echo "
                      <tr>
                        <td data-label='". NOME_TH . "'>$username</td>
                        <td data-label='". CARGO_TH . "'>",getRole($username),"</td>
                        <td data-label='". OPCOES_TH . "'>
                          <button type='button' onclick='openModal(\"$username\")' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#staticBackdrop'><i class='bi bi-pencil'></i></button>
                          <button type='button' onclick='openModal(\"$username\")' class='btn btn-primary ms-4' data-bs-toggle='modal' data-bs-target='#staticBackdrop2'><i class='bi bi-trash-fill '></i></button>
                        </td>
                      </tr>
                      ";
                  }
                }
              }else{
                echo "
                  <tr><td colspan='3' class='text-center'>
                    <div class='alert alert-danger' role='alert'>
                      Não existem utilizadores!
                    </div>
                  </td></tr>
                ";
              }
              ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel2" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel2">Apagar Usuário</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Tem a certeza que quer apagar o usuario?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" onclick="deleteUser(document.querySelector('.username').textContent)" class="btn btn-primary">Confirmar</button>
        </div>
      </div>
    </div>
  </div>       

  <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Editar Usuário</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

        <div class="username d-none"></div>

        <select class="form-select" aria-label="Default select example">
          <option value="null" selected>Escolha um cargo</option>
          <option value="membro">Membro</option>
          <option value="admin">Admin</option>
        </select>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" id="confirm" onclick="editRole(document.getElementsByClassName('form-select')[0].value, document.querySelector('.username').textContent)" class="btn btn-primary">Confirmar</button>
        </div>
      </div>
    </div>
  </div>

  <script>
  function openModal(username) {
    const selectedUsername = document.querySelector('.username');
    selectedUsername.textContent = username;
  }


  function editRole(cargo, user) {
    if(cargo != "null")
      window.location.href = `editUser.php?request=edit&usernameEdit=${user}&roleEdit=${cargo}`;
  }

  function deleteUser(user) {
    window.location.href = `editUser.php?request=delete&usernameEdit=${user}`;
  }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
</body>
</html>