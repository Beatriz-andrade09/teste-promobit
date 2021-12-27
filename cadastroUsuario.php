<?php

if (!isset($_SESSION)) {
    session_start();
    if (isset($_SESSION['id_usuario']) && isset($_SESSION['senha_usuario'])) {
        header("location: pagina_inicial.php");
        exit;
    }
}

require_once 'classe/usuario.php';
$u = new usuario();


if (isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['confirmar_senha'])) {

    $nome = addslashes($_POST['nome']);
    $email = addslashes($_POST['email']);
    $senha = addslashes($_POST['senha']);
    $confirmar_senha = addslashes($_POST['confirmar_senha']);

    if (!empty($nome) && !empty($email) && !empty($senha) && !empty($confirmar_senha)) {

        $u->conectar("teste-promobit", "localhost", "root", "");

        if ($u->getMsg() == '') {
            if ($senha == $confirmar_senha) {
                if ($u->cadastrar($nome, $email, $senha)) {
                    $u->msg("Cadastro realizado com sucesso! Acesse a página de login.", 'success');
                } else {
                    $u->msg("Email já cadastrado.<br> Acesse a página de login.", 'info');
                }
            } else {
                $u->msg("Senhas não conferem.", 'warning');
            }
        } else {
            $u->msg($u->getMsg(), 'danger');
        }
    } else {
        $u->msg("Preenche todos os campos!", 'warning');
    }
}


?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Cadastro</title>
    <link rel="shortcut icon" href="img/android-chrome-192x192.png">
    <!-- CDN CSS Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <!-- CDN Icones Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <!-- CDN JS JQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <!-- CDN JS Bundle Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous">
    </script>

    <!-- Custom styles for this template -->
    <link href="style/signin.css" rel="stylesheet">
</head>

<body class="text-center">

    <form class="form-signin" method="POST">
        <img class="mb-4" src="img/android-chrome-192x192.png" alt="promobit" width="70">
        <h1 class="h3 mb-3 text-primary font-weight-normal">Cadastre-se, é grátis!</h1>
        <input type="text" name="nome" class="form-control" maxlength="30" placeholder="Seu nome" required autofocus>
        <input type="email" name="email" class="form-control" maxlength="40" placeholder="Seu email" required>
        <input type="password" name="senha" class="form-control" maxlength="8" placeholder="Informe uma senha" required>
        <input type="password" name="confirmar_senha" class="form-control" maxlength="8" placeholder="Confirme a senha" required>
        <?php echo $u->getMsg() ?>
        <button class="btn btn-lg btn-success btn-block" type="submit">Cadastrar</button>
        <a href="index.php">Já é um membro?<strong> Acesse a sua conta!</strong></a>
        <p class="mt-5 mb-3 text-center text-muted">&copy; 2021</p>
    </form>

</body>

</html>