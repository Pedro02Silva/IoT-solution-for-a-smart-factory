<?php

// Verifica se o sensor existe
function existSensor($src){
    if(is_dir($src)){
        return true;
    }

    return false;
}

// Verifica se o sensor tem um botão de ligar/desligar (representado por um icone)
function getButton($src){
    if(!existSensor($src))
    {
        return false;
    }

    if(!file_exists($src."button.txt")){
        return false;
    }
    return true;
}

// Obtém o tipo do sensor
function getTipo($src){
    if(!existSensor($src))
    {
        return false;
    }

    return file_get_contents("$src/tipo.txt"); 
}

// Obtém o nome do sensor
function getNome($src){
    if(!existSensor($src))
    {
        return false;
    }

    return file_get_contents("$src/nome.txt"); 
}

// Obtém a log do sensor
function getLog($src){
    if(!existSensor($src))
    {
        return false;
    }

    return file_get_contents("$src/log.txt"); 
}

// Obtém a hora da leitura do sensor
function getHora($src){
    if(!existSensor($src))
    {
        return false;
    }

    return file_get_contents("$src/hora.txt"); 
}

// Obtém a cor a usar no card, mudando em relação ao tipo de sensor
function getCor($src){
    if(!existSensor($src))
    {
        return false;
    }

    // Verifica se o tipo do sensor é "digital". Se for, retorna uma cor laranja, caso contrário, 
    // retorna uma cor azul claro sem for "analogico" ou verde claro se for "imagem".
    return getTipo($src) == "digital" ? "#ffbf6c" : 
            (getTipo($src) == "imagem" ? "#bfff70" : "#93baff");
}

// Obtém a imagem associada ao sensor
function getImagem($src){
    if(!existSensor($src))
    {
        return false;
    }

    return file_get_contents("$src/imagem.txt"); 
    
}

// Obtém o valor do sensor
function getValor($src){
    if(!existSensor($src))
    {
        return false;
    }

    if(!file_exists("$src/valor.txt")){
        return null;
    }

    $valor = file_get_contents("$src/valor.txt");

    // Se o sensor não for digital, retorna o valor lido
    if(getTipo($src) != "digital")
    {
        return $valor;
    }

    // Se o sensor for digital, retorna o valor de acordo com a leitura (ligado/desligado)
    switch($valor){
        case "1":
            return file_get_contents("$src/valorLigado.txt");
            break;
        case "0":
            return file_get_contents("$src/valorDesligado.txt");
            break;
        default:
            return "Erro no sensor! Valor Inválido!";
    }
}

// Obtém o estado do sensor (normal, baixo, elevado, ligado ou desligado)
function getEstado($src){
    if(!existSensor($src))
    {
        return false;
    }

    if(!file_exists("$src/valor.txt")){
        return null;
    }

    $valor = file_get_contents("$src/valor.txt");
    
    // Se o sensor for digital, retorna o estado (ligado/desligado)
    if(getTipo($src) == "digital"){
        switch($valor){
            case "1":
                return "<span class='badge rounded-pill text-bg-success'>Ligado</span>";
                break;
            case "0":
                return "<span class='badge rounded-pill text-bg-secondary'>Desligado</span>";
                break;
            default:
                return "Erro no sensor! Valor Inválido!";
        }
    }

    // Se o sensor for analógico, verifica se o valor está normal, baixo ou elevado
    $valorMinimo = file_get_contents("$src/minimo.txt");
    $valorMaximo = file_get_contents("$src/maximo.txt");

    if($valor < $valorMinimo)
    {
        return "<span class='badge rounded-pill text-bg-warning'>Baixo</span>";
    }

    if($valor > $valorMaximo)
    {
        return "<span class='badge rounded-pill text-bg-danger'>Elevado</span>";
    }

    return "<span class='badge rounded-pill text-bg-primary'>Normal</span>";
}

function getMin($src){
    if(!existSensor($src))
    {
        return false;
    }

    if(!file_exists("$src/minimo.txt")){
        return null;
    }

    return file_get_contents("$src/minimo.txt");
}

function getMax($src){
    if(!existSensor($src))
    {
        return false;
    }

    if(!file_exists("$src/maximo.txt")){
        return null;
    }

    return file_get_contents("$src/maximo.txt");;
}