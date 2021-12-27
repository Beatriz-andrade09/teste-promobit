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

$u->conectar("teste-promobit", "localhost", "root", "");
    
if ($u->getMsg() == '') {
    $pesquisa = $_POST['busca'] ?? '';
    $cont = 0;
    $resultado = $u-> readProductByName($pesquisa);

} else {
    $u->msg($u->msgERRO, 'danger');
}


?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Listagem de Produto</title>
    <link rel="shortcut icon" href="img/android-chrome-192x192.png">
    <link href="style.css" rel="stylesheet" type="text/css" />
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
                        <a class="nav-link dropdown-toggle" href="" data-toggle="dropdown">Tag's</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="cadastroTag.php" target="self">Cadastro</a>
                            <a class="dropdown-item" href="listagemTag.php" target="_self">Listagem</a>
                        </div>
                    </li>
                    <!-- menu dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="" data-toggle="dropdown">Produto's</a>
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
    <br>
    <main class="container">
        <nav class="navbar navbar-dark ">
            <a class="navbar-brand" href="index.php"><img src="img/android-chrome-192x192.png" height="50px" alt=""></a>
            <form class="form-inline" action="listagemProduto.php" method="POST">
                <input class="form-control mr-sm-2" type="search" autofocus  maxlength="50" placeholder="Buscar por produto" name="busca">
                <button class="btn btn-primary my-2 my-sm-0" type="submit">Pesquisar  <i class="bi bi-search"></i></button>
                <br>
                <a href='cadastroProduto.php' class='btn btn-warning my-2 my-sm-0'>Cadastrar</a>
            </form>
        </nav>
        <br>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Id</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>

                <?php

                if (count($resultado)) {
                    foreach ($resultado as $linha) {
                        $id = $linha["id"];
                        $name = $linha["name"];
                        $cont++;
                ?>
                        <tr>
                            <th scope="row"><?php echo $cont ?></th>
                            <td>
                                <?php echo $id ?>
                            </td>
                            <td>
                                <?php echo $name ?>
                            </td>
                            <td width=150px>
                                <a href='edicaoProduto.php?id=<?php echo $id ?>&name=<?php echo $name?>' class='btn btn-success btn-sm'>Editar</a>
                                <!-- Button trigger modal -->
                                <a href='excluirProduto.php' class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirma" onclick="pegar_dados('<?php echo $id ?>', '<?php echo $name ?>')">
                                Excluir
                                </a>
                            </td>
                        </tr>
                <?php }
                } else {
                    echo "<tr width='100%'> <td class='text-center'colspan='4'> Produto não encontrado. </td></tr>";
                }  ?>
            </tbody>
        </table>
        <!-- Modal -->
        <div class="modal fade" id="confirma" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Confimar exclusão</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="excluirProduto.php" method="post">
                            <p>Deseja realmente excluir o produto <b id="produto"></b>?</p>
                            <input type="hidden" name="id" id="produto_code" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                        <button type="submit" class="btn btn-danger" value="excluir">Sim</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            function pegar_dados(id, nome) {
                document.getElementById('produto_code').value = id;
                document.getElementById('produto').innerHTML = nome;
            }
        </script>
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