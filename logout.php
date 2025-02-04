<?php
// Inicia a sessão
session_start();

// Remove todas as variáveis de sessão
session_unset();

// Destrói a sessão atual
session_destroy(); 

// Redireciona para a página inicial
header("location:index.php" );    
?>