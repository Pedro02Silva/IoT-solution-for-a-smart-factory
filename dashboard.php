<?php

// Inclui os arquivos de configuração e funções
require_once "./config.php";
require_once AUXILIAR_FUNCTIONS . "users_functions.php";
require_once AUXILIAR_FUNCTIONS . "dashboard_functions.php";
session_start(); // Inicio da sessão
    
if(!isset($_SESSION['username']) ){ // Caso a sessão não esteja ativa
    header("refresh:5;url=index.php"); // Será redirecionado após 5 segundos para index.php
    die("Acesso restrito!"); // E matar o processo da dashboard.php
}else{
    if(getRole($_SESSION['username']) == "membro"){ // Se o usuário for um membro, redireciona para a página de login após 5 segundos
        header("refresh:5;url=index.php"); // Será redirecionado após 5 segundos para index.php
        die("Acesso restrito!"); // E matar o processo da dashboard.php
    }
}
    
$src = API_PATH."files";

// Define arrays para armazenar os valores dos sensores
$sensoresSrc = array();
$valores = array();
$nomes = array();
$horas = array();
$imagens = array();
$tipos = array();
$estados = array();
$cores = array();

// Obtém a lista de sensores
$sensores = glob(API_PATH."files/*/");

// Itera sobre cada sensor e obtém as informações necessárias
foreach($sensores as $sensor){
    array_push($valores, getValor($sensor) != null ? getValor($sensor) : "---");
    array_push($nomes, getNome($sensor));
    array_push($horas, getHora($sensor));
    array_push($imagens, getImagem($sensor));
    array_push($tipos, getTipo($sensor));
    array_push($estados, getEstado($sensor) != null ? getEstado($sensor) : "---");
    array_push($cores, getCor($sensor));
    array_push($sensoresSrc, explode("/", $sensor)[3]);
}
?>

<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= WEBSITE_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">
        <link rel="icon" type="image/x-icon" href="<?= IMAGES_PATH ?>favicon.png">
    <link rel="stylesheet" href="<?= CSS_PATH ?>style.css">
</head>

<body>
    <?php include "includes/header.php"; ?>

    <div class="container mt-5">    
        <div class="card box">
            <div class="card-body">
                <img class="float-end img-fluid" style="width: 300px;" src="<?= IMAGES_PATH ?>estg.png" alt="logo_politecnico_leiria">
                <h1 class="card-title d-inline-block"><?= DASHBOARD_TITLE ?></h1>
                <p class="card-text">Bem vindo <strong><span class="text-uppercase">
                            <?php echo $_SESSION['username'];?></span></strong>
                    <br><br><?= CADEIRA ?>
                </p>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row my-2 d-flex justify-content-center">
            <?php 
                for($i = 0; $i < count($sensores); $i++){
                    echo "
                    <div class='col-sm-4 mt-2 sensorCard'>
                        <div class='card text-center'>
                            <div class='card-header' style='background-color:". $cores[$i] .";'>
                            </div>
                            <div class='card-body'>
                                <img class='img-fluid dashboard_img' src='". IMAGES_PATH . "$imagens[$i]' alt='$nomes[$i]'>
                            </div>
                            <div class='card-footer'></div>
                        </div>
                    </div>";
                }
            ?>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <strong><?= DASHBOARD_TABELA_SENSORES_TITLE ?></strong>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col"><?= DISPOSITIVO_TH ?></th>
                                    <th scope="col"><?= VALOR_TH ?></th>
                                    <th scope="col"><?= DATA_TH ?></th>
                                    <th scope="col"><?= ESTADOS_TH ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for($i = 0; $i < count($sensores); $i++){
                                    echo "
                                    <tr class='sensorLine'>
                                        <td data-label='Nome'></td>
                                        <td data-label='Valor'></td>
                                        <td data-label='Data de Atualização'></td>
                                        <td data-label='Estado'></td>
                                    </tr>";
                                }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <canvas id="meuGrafico"></canvas>
            </div>
        </div>
    </div>

    <?php include INCLUDES_PATH . "footer.php"; ?>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Obtenha uma referência ao elemento canvas
        const canvas = document.getElementById('meuGrafico');

        // Crie um contexto 2D para o elemento canvas
        const ctx = canvas.getContext('2d');

        const dataCounter = [
            <?php
                $counter = array(0,0,0);
                foreach($tipos as $tipo){
                    if($tipo == "analogico")
                        $counter[0]++;
                    else if($tipo == "digital")
                        $counter[1]++;
                    else if($tipo == "imagem")
                        $counter[2]++;
                }    
                echo "$counter[0], $counter[1], $counter[2]";
            ?>]; 

            // Defina os dados do gráfico
            const dados = {
                labels: ['Analógico', 'Digital', 'Imagem'],
                datasets: [{
                    label: 'Tipo de Sensores',
                    data: dataCounter
                }]
            };

            // Defina as opções do gráfico
            const options = {
                aspectRatio: 1.5, // Define a proporção largura/altura desejada
                plugins: {
                    title: {
                        display: true,
                        text: 'Tipo de Sensores',
                        padding: {
                            top: 10,
                            bottom: 30
                        }
                    },
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            };

            // Crie o gráfico usando a biblioteca Chart.js
            const meuGrafico = new Chart(ctx, {
                type: 'pie', // Tipo de gráfico (exemplo: bar, line, pie)
                data: dados, // Dados do gráfico
                options: options // Opções de configuração do gráfico (pode ser deixado vazio)
            });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <script>
        function getValues() {
            axios.get('./api/get_values.php')
            .then(function (response) {
                let data = response.data;
                // Atualizar cartões de sensores
                let sensorCards = document.getElementsByClassName("sensorCard")
                
                for (let index = 0; index < sensorCards.length; index++) {
                    let sensorCardsChildren = sensorCards[index].childNodes;

                    for (let indexCol = 0; indexCol < sensorCardsChildren.length; indexCol++) {
                        if (indexCol !== 1) continue;

                        let sensorCardsSeparator = sensorCardsChildren[indexCol].childNodes;

                        for (let indexColBreaks = 0; indexColBreaks < sensorCardsSeparator.length; indexColBreaks++) {
                            switch (indexColBreaks) {
                                case 1:
                                    sensorCardsSeparator[indexColBreaks].innerHTML = `<strong>${data.nomes[index]}${data.nomes[index] === "Imagem" ? "" : `: ${data.valores[index]}`}</strong>`;
                                    break;
                                case 3:
                                    sensorCardsSeparator[indexColBreaks].innerHTML = `<img class='img-fluid dashboard_img' src='<?= IMAGES_PATH ?>${data.imagens[index]}' alt='$nomes[$i]'>`;
                                    break;
                                case 5:
                                    sensorCardsSeparator[indexColBreaks].innerHTML = `<strong>Atualização:</strong> ${data.horas[index]} - <a href='./histórico.php?nome=${data.sensoresSrc[index]}'>Histórico</a>`;
                                    break;
                            }
                        }
                    }
                }

                // Atualizar a tabela de sensores
                let sensorLines = document.getElementsByClassName("sensorLine")

                for(let index=0;index < sensorLines.length;index++){
                    let sensorColumns = sensorLines[index].childNodes;

                    for(let indexCol=0;indexCol < sensorColumns.length;indexCol++){
                        switch(indexCol){
                            case 1:
                                sensorColumns[indexCol].innerText = data.nomes[index];
                                break;
                            case 3:
                                sensorColumns[indexCol].innerText = data.valores[index];
                                break;
                            case 5:
                                sensorColumns[indexCol].innerText = data.horas[index];
                                break;
                            case 7:
                                let dataToHtml = data.estados[index];
                                if(data.hasButton[index]){
                                    dataToHtml+=` <button type='button' onclick='sendPost("`+data.sensoresSrc[index]+`","`+data.valores[index]+`")' class='btn btn-primary btn-sm'><i class='bi bi-arrow-repeat'></i></button>`;
                                }
                                sensorColumns[indexCol].innerHTML = dataToHtml;
                                break;
                        }
                    }
                }
            })
            .catch(function (error) {
                console.log(error);
            });
        }

        function sendPost(name, value) {
            if (value == "Ligado" || value == "Aberta") {
                value = 0;
            } else {
                value = 1;
            }

            const config = {
                headers: {
                'Content-Type': 'application/json'
                }
            };

            const data = {
                [name]: {
                valor: value,
                hora: new Date().toLocaleString()
                }
            };
            
            axios.post('./api/api.php', data, config)
                .catch(function (error) {
                console.log(error);
            });
        }

        setInterval(getValues, 2000);
        getValues();
    </script>
</body>
</html>