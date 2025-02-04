<?php

require_once "../config.php";
require_once "../" . AUXILIAR_FUNCTIONS . "dashboard_functions.php";

$sensoresSrc = array();
$valores = array();
$nomes = array();
$horas = array();
$imagens = array();
$tipos = array();
$estados = array();
$cores = array();
$hasButton = array();

$sensores = glob("files/*/");
foreach($sensores as $sensor){
    array_push($valores, getValor($sensor) != null ? getValor($sensor) : "---");
    array_push($nomes, getNome($sensor));
    array_push($hasButton, getButton($sensor));
    array_push($horas, getHora($sensor));
    array_push($imagens, getImagem($sensor));
    array_push($tipos, getTipo($sensor));
    array_push($estados, getEstado($sensor) != null ? getEstado($sensor) : "---");
    array_push($cores, getCor($sensor));
    array_push($sensoresSrc, explode("/", $sensor)[1]);
}

$result = [
  'sensoresSrc' => $sensoresSrc,
  'hasButton' => $hasButton,
  'valores' => $valores,
  'nomes' => $nomes,
  'horas' => $horas,
  'imagens' => $imagens,
  'tipos' => $tipos,
  'estados' => $estados,
  'cores' => $cores
];

header('Content-Type: application/json; charset=utf-8"');
echo json_encode($result);