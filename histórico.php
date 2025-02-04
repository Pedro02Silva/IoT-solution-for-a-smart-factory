<?php
session_start();
require_once "./config.php";
require_once AUXILIAR_FUNCTIONS . "users_functions.php";
require_once AUXILIAR_FUNCTIONS . "dashboard_functions.php";

if(!isset($_SESSION['username']) ){ // Caso a sessão não esteja ativa
    http_response_code(403);
    header("refresh:5;url=index.php"); // Será redirecionado após 5 segundos para index.php
    die("Acesso restrito!"); // E matar o processo da dashboard.php
}

if(getRole($_SESSION['username']) == "membro"){
    http_response_code(403);
    header("refresh:5;url=index.php"); // Será redirecionado após 5 segundos para index.php
    die("Acesso restrito!"); // E matar o processo da dashboard.php
}

$methodRequest = $_SERVER['REQUEST_METHOD'];

if($methodRequest == "GET"){
    $nome = $_GET['nome'] ?? null;
    
    if(!isset($nome)){
        http_response_code(400);
        header("refresh:5;url=index.php"); // Será redirecionado após 5 segundos para index.php
        die("Pedido GET mal feito!"); // E matar o processo da dashboard.php
    }
    
    $src = API_PATH ."files/" .$_GET['nome']."/log.txt";
    if(!file_exists($src)) {
        http_response_code(400);
        header("refresh:5;url=index.php"); // Será redirecionado após 5 segundos para index.php
        die("Pedido mal feito. Opcoes de nome: temperatura | humidade | led | porta | presenca!"); // E matar o processo da dashboard.php
    }
}else{
    http_response_code(400);
    header("refresh:5;url=index.php"); // Será redirecionado após 5 segundos para index.php
    die("Pedido GET mal feito!"); // E matar o processo da dashboard.php
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
</head>
<body>
    <?php include INCLUDES_PATH . "header.php"; ?>

    <div class="container mt-5">    
        <div class="card box">
            <div class="card-body">
                <img class="float-end img-fluid" style="width: 300px;" src="<?= IMAGES_PATH ?>estg.png" alt="logo_politecnico_leiria">
                <h1 class="card-title d-inline-block"><?= HISTORICO_TITLE ?> - <?= getNome(API_PATH."files/$nome"); ?></h1>
                <p class="card-text">Bem vindo <strong><span class="text-uppercase">
                            <?php echo $_SESSION['username'];?></span></strong>
                    <br><br><?= CADEIRA ?>
                </p>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="card mt-3">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col"><?= DATA_TH ?></th>
                            <th scope="col"><?= HORA_TH ?></th>
                            <th scope="col"><?= VALOR_TH ?></th>
                        </tr>
                    </thead>
                        <tbody>
                            <?php
                            
                            if(isset($nome) && isset($src)){
                                $log = getLog(API_PATH."files/$nome");
                                
                                $log = explode("\n", $log);
                                
                                $validLogs = 0;

                                for($i = 0; $i < count($log); $i++){
                                    $lineLog = $log[$i];

                                    if(trim($lineLog) == ""){
                                        continue;
                                    }
                                    $validLogs++;
                                    $data = substr($lineLog, 0, strpos($lineLog, " "));
                                    $hora = substr($lineLog, strpos($lineLog, " ") + 1, strpos($lineLog, ";") - strpos($lineLog, " ") - 1);
                                    $valor = substr($lineLog, strpos($lineLog, ";") + 1);
                                    echo "<tr>
                                        <td data-label='Data'>$data</td>
                                        <td data-label='Hora'>$hora</td>
                                        <td data-label='Valor'>$valor</td>
                                        </tr>";  
                                }

                                if($validLogs == 0){
                                    echo "
                                        <tr><td colspan='3' class='text-center'>
                                        <div class='alert alert-danger' role='alert'>
                                            Não existe log!
                                        </div>
                                        </td></tr>
                                    ";
                                }
                            }                            
                        ?>
                        </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
</body>
</html>