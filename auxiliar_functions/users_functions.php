<?php

// Função para exibir mensagens de erro em um alerta
function showError($err){
    if($err == "")
    {
        return "";
    }

    return "<div class='alert alert-danger mx-2' role='alert'>
            $err
          </div>";
}

// Função para criar um novo utilizador no sistema
function createUser($user, $password){
    if(existUser($user))
    {
        return false;
    }

    // Cria um novo diretório para o utilizador
    mkdir(USERS_PATH . $user);
    // Armazena a senha do utilizador em um arquivo txt dentro do diretório
    file_put_contents(USERS_PATH . "$user/password.txt", $password);
    // Armazena o cargo do utilizador em um arquivo txt dentro do diretório
    file_put_contents(USERS_PATH . "$user/role.txt", "membro");
}

// Função para verificar se um utilizador já existe no sistema
function existUser($user){
    if(file_exists(USERS_PATH . "$user/password.txt")){
        return true;
    }

    return false;
}

// Função para obter a senha de um utilizador
function getPassword($user){
    if(!existUser($user))
    {
        return false;
    }
    
    return file_get_contents(USERS_PATH . "$user/password.txt");
}

// Função para obter o cargo de um utilizador no sistema
function getRole($user){
    if(!existUser($user))
    {
        return false;
    }

    return file_get_contents(USERS_PATH . "$user/role.txt");
}

// Função para verificar se o utilizador está logado
function isLogged(){
    return isset($_SESSION['username']);
}

// Função para atualizar o cargo de um utilizador
function updateRole($username, $role){
    if(!existUser($username))
    {
        return false;
    }

    // Atualiza o cargo do utilizador
    file_put_contents(USERS_PATH . "$username/role.txt", $role);
}

// Função para excluir um utilizador do sistema
function deleteUser($username){
    if(!existUser($username))
    {
        return false;
    }

    // Remove todos os arquivos dentro do diretório do utilizador
    $userPath = USERS_PATH . $username;
    $files = glob("$userPath/*");
    foreach($files as $file){
        unlink($file);
    }

    // Remove o diretório do utilizador
    rmdir($userPath);

    return true;
}
?>