<?php
// Inclui os arquivos de configuração e funções
require_once "./config.php";
require_once AUXILIAR_FUNCTIONS . "users_functions.php";
session_start(); // Inicio da sessão
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
</head>
<body>
   <?php include INCLUDES_PATH . "header.php"; ?>

   <div id="carouselExampleIndicators" class="carousel slide">
      <div class="carousel-indicators">
         <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
         <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
         <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
      </div>
      <div class="carousel-inner">
         <div class="carousel-item active">
            <img src="<?= IMAGES_PATH ?>carousel1.jpg" class="d-block w-100" alt="carousel_1">
         </div>
         <div class="carousel-item">
            <img src="<?= IMAGES_PATH ?>carousel2.jpg" class="d-block w-100" alt="carousel_2">
         </div>
         <div class="carousel-item">
            <img src="<?= IMAGES_PATH ?>carousel3.jpg" class="d-block w-100" alt="carousel_3">
         </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
      </button>
   </div>

   <br>

   <div class="container mt-5 box">
      <div class="row">
         <div class="col-md-6">
            <h1><?= BEM_VINDO ?></h1>
            <p><?= BEM_VINDO_DESCRICAO ?></p>
            <a onclick="emDesenvolvimento()" class="btn btn-primary mt-3 indexButtons"><?= BEM_VINDO_BOTAO ?></a>
         </div>
         <div class="col-md-6">
            <img src="<?= IMAGES_PATH ?>fabrica.jpg" alt="Imagem da Fábrica" class="img-fluid">
         </div>
      </div>
   </div>
   <div class="container mt-5">
      <hr>
      <h2><?= ESPECIALIDADES ?></h2>
      <hr>
      <div class="row">
         <div class="col-md-4">
            <div class="card mb-3 productCard">
               <img src="<?= IMAGES_PATH ?>vigasEstruturais.jpg" alt="Produto 1" class="card-img-top">
               <div class="card-body">
                  <h5 class="card-title">Vigas estruturais</h5>
                  <p class="card-text">Peças de aço em formato de "I" ou "H" utilizadas em construções e usado para suportar grandes cargas.</p>
                  <a onclick="emDesenvolvimento()" class="btn btn-primary">Ver mais</a>
               </div>
            </div>
         </div>
         <div class="col-md-4">
            <div class="card mb-3 productCard">
               <img src="<?= IMAGES_PATH ?>barrasAco.jpg" alt="Produto 2" class="card-img-top">
               <div class="card-body">
                  <h5 class="card-title">Barras de aço</h5>
                  <p class="card-text">Barras cilíndricas de aço utilizadas na fabricação de peças, como hastes de máquinas e edifícios..</p>
                  <a onclick="emDesenvolvimento()" class="btn btn-primary">Ver mais</a>
               </div>
            </div>
         </div>
         <div class="col-md-4">
            <div class="card mb-3 productCard">
               <img src="<?= IMAGES_PATH ?>pecasMetal.jpg" alt="Produto 1" class="card-img-top">
               <div class="card-body">
                  <h5 class="card-title">Peças fundidas em Metal</h5>
                  <p class="card-text">Peças fabricadas por processo de fundição, como parafusos, placas e porcas e peças usadas diariamente.</p>
                  <a onclick="emDesenvolvimento()" class="btn btn-primary">Ver mais</a>
               </div>
            </div>
         </div>
      </div>
   </div>

   <div class="container mt-4">
      <hr>
      <h2><?= CONTACTO ?></h2>
      <hr>
      <div class="row contactBox">
         <div class="d-flex justify-content-center questionsBox">
         <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d33859098.71777259!2d-12.328587300000013!3d-58.80400019999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xb09dff882a7809e1%3A0xb08d0a385dc8c7c7!2sAntarctica!5e0!3m2!1spt-PT!2spt!4v1682067119323!5m2!1spt-PT!2spt" width="500" height="450" style="border:2px solid grey;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            <div class="infoBox p-5">
            <p>
            <strong>Endereço:</strong> Rua da Fábrica, 123 Lisboa<br>
            <strong>Código Postal:</strong> 2405-153<br>
            <strong>Cidade:</strong> Lisboa<br>
            <strong>País:</strong> Portugal<br>
            <strong>Telefone:</strong> 918 456 576<br>
            <strong>Email:</strong> geral@fabrica.com<br>
            </p>
            </div>
         </div>
      </div>
   </div>


   <?php include INCLUDES_PATH . "footer.php"; ?>

   <script>
      function emDesenvolvimento(){
      alert("Ainda em Desenvolvimento!");
      }
   </script>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
</body>
</html>