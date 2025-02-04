<?php
// Inclui os arquivos de configuração e funções
require_once "./config.php";
require_once AUXILIAR_FUNCTIONS . "users_functions.php";
session_start(); // Inicio da sessão

if(!isset($_SESSION['username']) ){ // Caso a sessão não esteja ativa
    header("refresh:5;url=manageUsers.php"); // Será redirecionado após 5 segundos para index.php
    die("Acesso restrito!"); // E matar o processo do manageUsers.php
}else{
    if(getRole($_SESSION['username']) != "admin_plus"){// Caso o utilizador nao tenha permissao para alterar membros
        header("refresh:5;url=manageUsers.php");
        die("Acesso restrito!");
    }
}

if($_SERVER['REQUEST_METHOD'] != "GET"){ // Caso o pedido não seja um GET
    header("refresh:5;url=manageUsers.php");
    die("Pedido mal feito! Apenas GET aceitado!");
}

if (!isset($_GET['usernameEdit']) || !isset($_GET['request'])) { // Caso não existam as variáveis usernameEdit e request no metodo GET
    header("refresh:5;url=manageUsers.php");
    die("Pedido mal feito! (É necessário um valor para o request, usernameEdit!)");
}

$usernameEdit = $_GET['usernameEdit'];
$request = $_GET['request'];

if($request != "edit" && $request != "delete"){ // Caso a variavel request nao tome valores iguais a edit ou delete
    header("refresh:5;url=manageUsers.php");
    die("Pedido mal feito! (A variavel request tem de ter o valor de edit ou delete)");
}

if(!existUser($usernameEdit)){ // Caso o utilizador passado na variavel nao exista
    header("refresh:5;url=manageUsers.php");
    die("O utilizador selecionado não existe!");
}

if(getRole($usernameEdit) == "admin_plus"){ // Caso o utilizador passado na variavel tenha um cargo igual a admin_plus
    header("refresh:5;url=manageUsers.php");
    die("O utilizador selecionado não pode ser alterado por ser um admin_plus!");
}

if($request == "edit"){ // Se a variavel request tem o valor edit
    if (!isset($_GET['roleEdit'])) { // Caso não exista a variável de roleEdit no metodo GET
        header("refresh:5;url=manageUsers.php"); 
        die("Pedido mal feito! (É necessário um valor para o roleEdit!)"); 
    }

    $roleEdit = $_GET['roleEdit'];

    if($roleEdit != "membro" && $roleEdit != "admin"){ // Caso a variavel roleEdit nao tome valores iguais a membro ou admin
        header("refresh:5;url=manageUsers.php"); 
        die("Pedido mal feito! (A variavel roleEdit tem de ter o valor de membro ou admin)");
    }

    updateRole($usernameEdit, $roleEdit); // Chama a função para editar o cargo do utilizador
}else if($request == "delete"){ // Se a variavel request tem o valor delete
    deleteUser($usernameEdit); // Chama a função para apagar o utilizador
}

header("location:manageUsers.php"); // redireciona para o manageUsers.php