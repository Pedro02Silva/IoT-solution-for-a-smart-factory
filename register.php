<?php
// Inclui os arquivos de configuração e funções
require_once "./config.php";
require_once AUXILIAR_FUNCTIONS . "users_functions.php";
session_start(); // Inicio da sessão

if(isset($_SESSION['username']) ){ // Caso a sessão esteja ativa
  header("Location:index.php"); // Será redirecionado para index.php
}

$error = ""; // Inicializa a variável de erro vazia

if($_SERVER['REQUEST_METHOD'] == "POST"){ // Verifica se o método da requisição é POST
  $username = $_POST['username']; // Obtém o nome de utilizador do formulário enviado via POST
  $password = $_POST['password']; // Obtém a senha do formulário enviado via POST
  $passwordConfirm = $_POST['passwordConfirm']; // Obtém a confirmação de senha do formulário enviado via POST

  if(isset($username) && $username != ""){
      if(existUser($username)){
          $error .= "Esse utilizador já está registrado!<br>"; // Adiciona mensagem de erro caso o utilizador já exista
      }
  }else{
      $error .= "O nome do utilizador precisa de ser colocado!<br>"; // Adiciona mensagem de erro caso o nome de utilizador esteja vazio
  }

  if((isset($password) && $password != "") && (isset($passwordConfirm) && $passwordConfirm != "")){
    if($password != $passwordConfirm){
      $error .= "As passwords precisam coincidir uma com a outra!<br>"; // Adiciona mensagem de erro caso as senhas não coincidam
    }
  }else{
    $error .= "A password precisa de ser inserida e confirmada!<br>"; // Adiciona mensagem de erro caso a senha esteja vazia
  }

  // Se não houver erros, cria o arquivo de utilizador e redireciona para a página inicial
  if($error == ""){
    $password = password_hash($password, PASSWORD_ARGON2I); // Gera um hash para a senha
    createUser($username, $password); // Cria o arquivo de utilizador com o nome de usuário e a senha
    $_SESSION['username'] = $username; // Define o nome de utilizador na variável de sessão
    header('Location: index.php'); // Redireciona para a página inicial
  }
}
?>
<!doctype html>
<html lang="pt">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= WEBSITE_NAME ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
  <link rel="icon" type="image/x-icon" href="<?= IMAGES_PATH ?>favicon.png">
  <link rel="stylesheet" href="<?= CSS_PATH ?>style.css">
  <link rel="stylesheet" href="<?= CSS_PATH ?>login.css">
</head>

<body>
  <div class="container login_box">
      <div class="row justify-content-center">
        <form action="#" method="POST" class="TIform">
          <a href="index.php">
              <img src="<?= IMAGES_PATH ?>estg_h.png" alt="estg" class="img-fluid mx-auto d-block">
          </a>
          <p class="text-center fs-3 mt-3"><?= REGISTAR_TITLE ?></p>
          <div class="mt-4">
            <label for="usernameInput"><?= USERNAME_LABEL ?></label>
            <input type="text" id="usernameInput" class="form-control mt-1" placeholder="Insira o username" name="username" required>
          </div>
          <div class="mt-3">
              <label for="passwordInput"><?= PASSWORD_LABEL ?></label>
              <input type="password" id="passwordInput" class="form-control mt-1" placeholder="Insira a password" name="password" required>
          </div>
          <div class="mt-3">
              <label for="passwordConfirmInput"><?= PASSWORD_CONFIRM_LABEL ?></label>
              <input type="password" id="passwordConfirmInput" class="form-control mt-1" placeholder="Insira a password novamente" name="passwordConfirm" required>
          </div>

          <button class="btn btn-primary mt-3" type="submit"><?= REGISTAR_BOTAO ?></button>
        </form>
        <p class="text-center fs-6">Já tem conta? Faça login <a href="login.php">aqui</a></p>
        <?= showError($error); ?>
      </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
</body>
</html>