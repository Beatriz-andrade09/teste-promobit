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


if (isset($_POST['email']) && isset($_POST['senha'])) {

    $email = addslashes($_POST['email']);
    $senha = addslashes($_POST['senha']);

    if (!empty($email) && !empty($senha)) {
        $u->conectar("teste-promobit", "localhost", "root", "");

        if ($u->getMsg() == '') {
            if ($u->logar($email, $senha)) {
                header("location: pagina_inicial.php");
            } else {
                $u->msg("Email e/ou senha inválida.", 'danger');
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
    <title>Login</title>
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
        <h1 class="h3 mb-3 text-primary font-weight-normal">Login</h1>
        <p class="h6 mb-6 text-left text-muted font-weight-normal">Email*</p>
        <input type="email" class="form-control" name="email" maxlength="40" placeholder="email@exemplo.com" required autofocus />
        <p class="h6 mb-6 text-left text-muted font-weight-normal">Senha*</p>
        <input type="password" class="form-control" name="senha" maxlength="8" placeholder="********" required />
        <?php echo $u->getMsg() ?>
        <input type="submit" class="btn btn-lg btn-primary btn-block" name="" value="Acessar" />
        <a href="cadastroUsuario.php">Ainda não é membro?<strong> Cadastre-se!</strong></a>
        <p class="mt-5 mb-3 text-muted">&copy; 2021</p>
    </form>

</body>

</html>