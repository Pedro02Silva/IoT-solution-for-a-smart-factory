<?php
// Inclui os arquivos de configuração e funções
require_once "../config.php";
require_once "../" . AUXILIAR_FUNCTIONS . "dashboard_functions.php";

header('Content-Type: text/html; charset=utf-8');

// Armazena o tipo de pedido recebido pela APi
$methodRequest = $_SERVER['REQUEST_METHOD'];

switch ($methodRequest) {
    case "POST":
        if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
            $data = json_decode(file_get_contents("php://input"), true);
            foreach ($data as $nome => $elemento) {
                $valor = $elemento['valor'];
                $hora = $elemento['hora'];
    
                /*
                {
                    "temperatura": {
                        "valor":24,
                        "hora": "8/6/2023 12:30:45"  
                    },
                    "luminosidade": {
                        "valor":550,
                        "hora": "8/6/2023 12:30:45"   
                    }
                }
                */
                validatePost($nome, $valor, $hora);
            }
        }else if ($_SERVER['CONTENT_TYPE'] === 'application/x-www-form-urlencoded'){
            $nome = $_POST['nome'] ?? null;
            $valor = $_POST['valor'] ?? null;
            $hora = $_POST['hora'] ?? null;
            
            //nome=temperatura&valor=210&hora=1970-01-01 01:14:46
            validatePost($nome, $valor, $hora);
        }else{
            http_response_code(400);
            die("O content-type pode ser application/json ou application/x-www-form-urlencoded");
        }

        break;
    case "GET": // Caso o método recebido seja um GET
        $nome = $_GET['nome'] ?? null; // Variável do nome recebe o valor de nome passado no GET, caso contrário, recebe null 
        $flag = $_GET['flag'] ?? null;
        if(!isset($nome)){ // Caso o utilizador não tenha enviado um nome
            echo "Pedido mal feito"; // Imprime um erro
            http_response_code(400); // Obtém o suposto diretório do sensor
            break;
        }

        $nome = strtolower($nome); // Transforma o nome em minusculas para ser mais fácil de procurar
        $src = "./files/$nome/"; // Obtém o suposto diretório do sensor

        if(!is_dir($src)) { // Verifica se o sensor passado no nome existe, caso contrário mostra uma mensagem de erro
            echo "Pedido mal feito. Opcoes de nome: temperatura | humidade | led | porta | presenca!";
            http_response_code(400);
            break;
        }


        if(getValor("../api/files/$nome") == null){
            echo "A imagem nao tem qualquer valor!";
            http_response_code(400);
            break;
        }


        if(isset($flag)){
            echo getMax("../api/files/$nome").";".getMin("../api/files/$nome").";";
        }
        echo getValor("../api/files/$nome"); // Retorna o valor do sensor
        http_response_code(200);
        break;
    default:
        echo "Método não permitido";
        http_response_code(403);
}

function validatePost($nome, $valor, $hora){
    if (!isset($nome)) {
        http_response_code(400);
        die("Pedido mal feito, falta o parâmetro do nome");
    }

    $nome = strtolower($nome);
    $src = "./files/$nome/";

    if (!is_dir($src) || $src == "imagem") {
        http_response_code(400);
        die("Pedido mal feito. Opções de nome: temperatura | humidade | led | porta | presenca | luminosidade!");
    }

    $newLog = "";
    if (isset($hora) && isset($valor)) {
        if (!is_numeric($valor)) {
            http_response_code(400);
            die("O parâmetro valor tem que ser um número!");
        }

        $src2 = "../api/files/$nome";

        if (getTipo($src2) == "digital") {
            if ($valor != 1 && $valor != 0) {
                http_response_code(400);
                die("O parâmetro tem de ser 0 ou 1 para sensores digitais!");
            }
        }

        file_put_contents($src . "/hora.txt", $hora);
        file_put_contents($src . "/valor.txt", $valor);
        $newLog .= PHP_EOL . PHP_EOL . "$hora" . ";" . getValor($src2);
        file_put_contents($src . "/log.txt", $newLog, FILE_APPEND);

        if (getTipo($src) == "digital") {
            switch ($valor) {
                case 1:
                    file_put_contents($src . "/imagem.txt", "$nome" . "_" . "Ligado.png");
                    break;
                case 0:
                    file_put_contents($src . "/imagem.txt", "$nome" . "_" . "Desligado.png");
                    break;
            }

            http_response_code(200);
            echo "Post realizado com sucesso".PHP_EOL;
            return;
        }
        
        $valor = getValor($src2);

        $valorMinimo = file_get_contents("$src/minimo.txt");
        $valorMaximo = file_get_contents("$src/maximo.txt");

        if ($valor < $valorMinimo) {
            file_put_contents($src . "/imagem.txt", "$nome" . "_" . "Baixo.png");
        } elseif ($valor > $valorMaximo) {
            file_put_contents($src . "/imagem.txt", "$nome" . "_" . "Elevado.png");
        } else {
            file_put_contents($src . "/imagem.txt", "$nome" . "_" . "Normal.png");
        }

        http_response_code(200);
        echo "Post realizado com sucesso".PHP_EOL;
    } else {
        http_response_code(400);
        if (!isset($valor)) {
            die("Faltou o parâmetro do valor");
        }

        if (!isset($hora)) {
            die("Faltou o parâmetro da hora");
        }
    }
}

?>