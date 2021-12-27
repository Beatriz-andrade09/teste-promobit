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

if (isset($_POST['submit']) && !empty($_POST['nome'])) {

    $u->conectar("teste-promobit", "localhost", "root", "");

    if ($u->getMsg() == '') {
        $nome = addslashes($_POST['nome']);
        if ($u->createTag($nome)) {
            header("location: listagemTag.php");
            exit;
        } else {
            $u->msg($u->getMsg(), 'danger');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Cadastro de Tag</title>
    <link rel="shortcut icon" href="img/android-chrome-192x192.png">
    <link href="style/style.css" rel="stylesheet" type="text/css" />
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
</head>

<body>

<header class="sticky-top">
        <!-- Navbar( Hamburguer) -->
        <nav class="navbar navbar-expand-sm navbar-dark bg-dark ">
            <!-- logo -->
            <a href="index.php">
                <img src="img/promobit-logo.svg" alt="promobit" width="100">
            </a>
            <button class="navbar-toggler" data-toggle="collapse" data-target="#navegacao">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- lista navegação -->
            <div class="collapse navbar-collapse pr-5" id="navegacao">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item ">
                        <a class="nav-link" href="pagina_inicial.php">Página Inicial</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="area_usuario.php">Área do Usuário</a>
                    </li>
                    <!-- menu dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="" data-toggle="dropdown">Tag's</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="cadastroTag.php" target="self">Cadastro</a>
                            <a class="dropdown-item" href="listagemTag.php" target="_self">Listagem</a>
                        </div>
                    </li>
                    <!-- menu dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="" data-toggle="dropdown">Produto's</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="cadastroProduto.php" target="self">Cadastro</a>
                            <a class="dropdown-item" href="listagemProduto.php" target="_self">Listagem</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link bg-danger text-white" href="logout.php">Sair</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <main class="container">
        <div class=" bg-imagem"></div>
        <br>
        <form method="POST">
            <div class="form-group">
                <label>Nome*</label>
                <input type="text" class="form-control" name="nome" maxlength="50" required placeholder="Digite um nome para a tag">
            </div>
            <?php echo $u->getMsg() ?>
            <button type="submit" name="submit" class="btn btn-primary">Cadastrar</button>
            <a href='listagemTag.php' class='btn btn-success'>Tags Cadastradas</a>
        </form>
    </main>
    <br><br>
    <footer class="footer fixed-bottom page-footer font-small pt-3">
        <div class="bg-dark py-4">
            <div class="container-fluid text-center">
                <a href="https://www.facebook.com/promobit" target="_blank"><i class="bi bi-facebook"></i></a>
                <a href="https://twitter.com/PromobitOficial" target="_blank"><i class="bi bi-twitter"></i></a>
                <a href="https://www.instagram.com/promobitoficial/" target="_blank"><i class="bi bi-instagram"></i></a>
                <a href="https://www.linkedin.com/company/promobit/" target="_blank"><i class="bi bi-linkedin"></i></a>
                <a href="https://t.me/cupons_desconto"><i class="bi bi-telegram"></i></a>
                <p class="text-white font-italic">Economizando na sua compra em todos os lugares!!
                </p>
                <p><span class="text-white font-italic text-bottom">2021 &#169; Todos os direitos reservados.</span>
                </p>
            </div>
        </div>
    </footer>
    <!-- Footer-->
</body>

</html>