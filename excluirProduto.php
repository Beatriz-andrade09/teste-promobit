<?php

    if (!isset($_SESSION)) {
        session_start();
        if (!isset($_SESSION['id_usuario']) && !isset($_SESSION['senha_usuario'])) {
            header("location: index.php");
            exit;
        }
    }

    include_once 'classe/usuario.php';
    $u = new usuario();

    $id = $_POST['id'];
    if(!empty($id)) {
        $u->conectar("teste-promobit", "localhost", "root", "");
        $u->deleteProduct($id);
        header("Refresh: 0; url=listagemProduto.php");
        exit;
    }
?>