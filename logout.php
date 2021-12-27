<?php

    if(!isset($_SESSION)) {
        session_start();
        unset($_SESSION['id_usuario']);
        unset($_SESSION['nome_usuario']);
        unset($_SESSION['email_usuario']);
        unset($_SESSION['senha_usuario']);
        session_destroy();
        header('location: index.php');
    }
?>