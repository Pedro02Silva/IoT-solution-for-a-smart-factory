<?php
session_start();

// Inclui os arquivos de configuração e funções
require_once "../config.php";
require_once "../" . AUXILIAR_FUNCTIONS . "dashboard_functions.php";
require_once "../" .  AUXILIAR_FUNCTIONS . "users_functions.php";

header('Content-Type: text/html; charset=utf-8');

// Armazena o tipo de pedido recebido pela APi
$methodRequest = $_SERVER['REQUEST_METHOD'];
    
switch($methodRequest){
    case "POST": // Caso o método recebido seja um POST
        $hora = $_POST['hora'] ?? null;

        if(!isset($_FILES)){    
            echo "Pedido mal feito! Falta a imagem!";
            http_response_code(400);
            break;
        }

        if(!isset($hora)){
            http_response_code(400);
            die("Faltou o parametro da hora");
        }

        $imagem = $_FILES["imagem"];

        // Verificar a extensão do arquivo
        $extensoesPermitidas = ["jpg", "png"];
        $extensao = strtolower(pathinfo($imagem["name"], PATHINFO_EXTENSION));
        if (!in_array($extensao, $extensoesPermitidas)) {
            echo "Apenas imagens com as extensões .jpg ou .png são permitidas!";
            http_response_code(400);
            break;
        }
        
        //Verficar tamanho da imagem
        $tamanhoMaximo = 1000 * 1024; // Em bytes
        if ($imagem["size"] > $tamanhoMaximo) {
            echo "A imagem excede o tamanho máximo de 1000kb!";
            http_response_code(400);
            break;
        }

        $src = "./files/imagem/";
        $newLog = "";

        $newLog = PHP_EOL.PHP_EOL."$hora".";"."---";
        file_put_contents($src."/hora.txt",$hora);

        file_put_contents($src."/log.txt",$newLog, FILE_APPEND);

        move_uploaded_file($imagem["tmp_name"], "." . IMAGES_PATH . "webcam.jpg");
        http_response_code(200);
        break;
    default:
        echo "Método não permitido";
        http_response_code(403);
}
?>