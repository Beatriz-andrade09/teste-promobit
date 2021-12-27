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
include "classe/usuario.php";
$u = new usuario();

$u->conectar("teste-promobit", "localhost", "root", "");

if ($u->getMsg() == '') {
    $pesquisa = $_POST['busca'] ?? '';
    $cont = 0;
    $resultado = $u->readRelevancia($pesquisa);
} else {
    $u->msg($u->msgERRO, 'danger');
}

?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Página Inicial</title>
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
                        <a class="nav-link active" href="pagina_inicial.php">Página Inicial</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="area_usuario.php">Área do Usuário</a>
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
    <main class="container">
        <div class="bg-imagem">
        </div>
        <br>
        <?php
        echo " <p class='text-justify'> Bem vindo, " . $_SESSION['nome_usuario'] . "! <strong class='text-warning'>SOMOS PROMOBIT! </strong></p>";
        ?>

        <p class="text-justify text-primary">O Promobit surgiu em 2013 e hoje é a maior Comunidade de Ofertas do Brasil, ajudando quase 2 milhões de pessoas a economizar em suas compras. Fazemos parte do grupo CASH3 do Méliuz, um ecossistema completo para ajudar as pessoas a economizarem, que também conta com as empresas Picodi, Acesso Bank, iDinheiro e Melhor Plano. </p>
        <p class="text-justify text-primary">Somos uma startup de tecnologia que trabalha para oferecer as melhores ferramentas para que as pessoas possam se unir para compartilhar a economia. Por dia, recebemos mais de 700 ofertas da nossa comunidade e mais de 10 milhões de acessos de pessoas querendo encontrar boas oportunidades para economizar.</p>
        <p class="text-justify">Um time <strong class='text-danger'>apaixonado </strong> por <strong class='text-warning'>promoções</strong> e <strong class='text-success'>descontos</strong>!</p>
        <strong class='text-primary'>
            <h5><i class="bi bi-suit-heart-fill"></i> Confira nossa Relevância de Produtos <i class="bi bi-arrow-down"></i></h5>
        </strong>
        <br>
        <nav class="navbar navbar-dark ">
            <form class="form-inline" action="pagina_inicial.php" method="POST">
                <input class="form-control mr-sm-2" type="search" required  maxlength="50" autofocus placeholder="Buscar por tag" name="busca">
                <button class="btn btn-warning my-2 my-sm-0" type="submit">Pesquisar <i class="bi bi-search"></i></button>
                <br>
            </form>
        </nav>
        <br>
        <table class="table bg-light table-hover">
            <thead>
                <tr>
                    <th scope="col"><img src="img/android-chrome-192x192.png" height="30px" alt="promobit">
                    </th>
                    <th scope="col" class="text-center">Tag</th>
                    <th scope="col" class="text-center">Quantidade de Produtos</th>
                    <th scope="col" class="text-center"></i></th>
                </tr>
            </thead>
            <tbody>

                <?php
                if (count($result)) {
                    foreach ($result as $linha) {
                        $qtd_produto = $linha["qtd_produto"];
                        $id = $linha["id"];
                        $name = $linha["name"];
                        $cont++;
                ?>
                        <tr>
                            <th scope="row"><?php echo $cont ?></th>
                            <td class="text-center">
                                <?php echo $name ?>
                            </td>
                            <td class="text-center">
                                <?php echo $qtd_produto ?>
                            </td>
                            <td width=150px>
                                <a href='produtosTag.php?id=<?php echo $id ?>' class='btn btn-success btn-sm'>Ver produtos</a>
                            </td>
                        </tr>
                <?php }
                } else {
                    echo "<tr width='100%'> <td class='text-center'colspan='4'> Tag não encontrada. </td></tr>";
                }  ?>
            </tbody>
        </table>
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