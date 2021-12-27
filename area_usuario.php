<!DOCTYPE html>
<html lang="pt-BR">
<?php

if (!isset($_SESSION)) {
    session_start();
    if (!isset($_SESSION['id_usuario']) && !isset($_SESSION['senha_usuario'])) {
        header("location: index.php");
        exit;
    }
}


require_once 'classe/usuario.php';
$u = new usuario();

if (isset($_POST['nome']) && isset($_POST['email']) && !empty($_POST['senha_atual']) && isset($_POST['senha_nova'])) {
    if (md5($_POST['senha_atual']) == $_SESSION['senha_usuario']) {

        $nome = addslashes($_POST['nome']);
        $email = addslashes($_POST['email']);
        $senha_nova = $_POST['senha_nova'];
        $id = $_SESSION['id_usuario'];
        $u->conectar("teste-promobit", "localhost", "root", "");
        if($u->editar($id, $nome, $email, $senha_nova)) {
            $u->msg("Dados alterados com sucesso!", 'success');      
        }
    } else {
        $u->msg("Senha atual digitada está incorreta. Digite corretamente.", 'danger');

    }
}

?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Área do Usuário</title>
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
                        <a class="nav-link active" href="area_usuario.php">Área do Usuário</a>
                    </li>
                    <!-- menu dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="" data-toggle="dropdown">Tag's</a>
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
    <br><br>
    <main class="container">
        <img src="img/usuario.jpeg" alt="" id="usuario">
        <p class="text-center"><strong class='text-success'>As melhores ofertas </strong><strong class='text-danger'> para </strong><strong class='text-primary'>você,</strong> <strong class='text-warning'><?php echo $_SESSION['nome_usuario']; ?>!</strong></p>

        <form method="POST" >
        <div class="form-group">
            <label>Nome</label>
            <input type="text" class="form-control" maxlength="30" value="<?php echo $_SESSION['nome_usuario']; ?>" name="nome">
        </div> 
        <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" name="email"  maxlength="40" value="<?php echo $_SESSION['email_usuario']; ?>">
        </div>
        <div class="form-group">    
            <label>Nova Senha</label>
            <input type="password" class="form-control" placeholder="Digite uma nova senha" name="senha_nova" maxlength="8">
        </div>
        <div class="form-group">   
            <label>Senha Atual *</label>
            <input type="password" class="form-control" placeholder="Digite a senha atual para alterar os dados." name="senha_atual" maxlength="8" required>
        <div class="form-group">
            <?php echo $u->getMsg() ?>
            <br>
            <button type="submit" name="submit" class="btn btn-primary">Alterar Dados</button>
      
        </form>
    </main>
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