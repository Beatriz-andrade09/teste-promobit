<?php
class Usuario
{

    private $pdo;
    public $msgERRO = "";

    public function conectar($nome, $host, $usuario, $senha)
    {

        global $pdo;
        global $msgERRO;

        try {
            $pdo = new PDO("mysql:dbname=" . $nome . ";host=" . $host, $usuario, $senha);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $msgERRO = $e->getMessage();
        }
    }

    public function cadastrar($nome, $email, $senha)
    {

        global $pdo;
        $sql = $pdo->prepare("SELECT id FROM user WHERE email = :e");
        $sql->bindValue(":e", $email);
        $sql->execute();

        if ($sql->rowCount() > 0) :

            return false;

        else :

            $sql = $pdo->prepare("INSERT INTO user (nome, email, senha) VALUES (:n, :e, :s)");
            $sql->bindValue(":n", $nome);
            $sql->bindValue(":e", $email);
            $sql->bindValue(":s", md5($senha));

            $sql->execute();

            return true;

        endif;
    }

    public function logar($email, $senha)
    {

        global $pdo;
        global $msgERRO;
        try {

            $sql = $pdo->prepare("SELECT id, nome, email, senha FROM user WHERE email = :e AND senha = :s");

            $sql->bindValue(":e", $email);
            $sql->bindValue(":s", md5($senha));
            $sql->execute();

            if ($sql->rowCount() > 0) :
                $dado = $sql->fetch();
                if (!isset($_SESSION)) {
                    session_start();
                }
                $_SESSION['id_usuario'] = $dado['id'];
                $_SESSION['nome_usuario'] = $dado['nome'];
                $_SESSION['email_usuario'] = $dado['email'];
                $_SESSION['senha_usuario'] = $dado['senha'];
                return true;

            else :
                return false;

            endif;
        } catch (PDOException $e) {
            $msgERRO = $e->getMessage();
        }
    }

    public function editar($id, $nome, $email, $senha)
    {

        global $pdo;

        $sql = $pdo->prepare("SELECT id, nome, email, senha FROM user where id = :i");
        $sql->bindValue(":i", $id);
        $sql->execute();
        if ($sql->rowCount() > 0) {
            $cont = 0;
            $consulta = "UPDATE `user` SET ";

            if ($sql->rowCount() > 0) {
                $dado = $sql->fetch();
             
                if (!isset($_SESSION)) {
                    session_start();
                }

                if ($dado['nome'] !== $nome) {
                    $consulta = $consulta . " nome = '$nome'";
                    $cont++;
                    $_SESSION['nome_usuario'] = $nome;
                }


                if ($dado['email'] !== $email && !empty($email)) {
                    if ($cont == 0) {
                        $consulta =  $consulta . " email = '$email'";
                        $cont++;
                    } else {
                        $consulta = $consulta . " , email = '$email' ";
                        $cont++;
                    }
                    $_SESSION['email_usuario'] = $email;
                }


                if (!empty($senha)) {
                    $senha = md5($senha);
                    if ($cont == 0) {
                        $consulta = $consulta . "  senha = '$senha'";
                        $cont++;
                    } else {
                        $consulta =  $consulta . ", senha = '$senha' ";
                        $cont++;
                    }
                    $_SESSION['senha_usuario'] = $senha;
                }

                $consulta = $consulta . " WHERE id = :i ";
                $sqlUpdate = $pdo->prepare($consulta);
                $sqlUpdate->bindValue(":i", $id);
              
                $sqlUpdate->execute();
                return true;

            }
        } else {
           return false;
        }
    }


    public function msg($msg, $tipo)
    {
        global $msgERRO;

        $msgERRO = "<div class='alert text-center alert-$tipo'>$msg</div>";
    }

    public function getMsg()
    {
        global $msgERRO;
        return $msgERRO;
    }

    public function createTag($nome)
    {

        global $pdo;
        global $msgERRO;

        $sql = $pdo->prepare("SELECT id FROM tag WHERE name = :e");
        $sql->bindValue(":e", $nome);
        $sql->execute();

        if ($sql->rowCount() > 0) :
            $msgERRO = "Tag ".$nome." já existe.";
            return false;

        else :

            $sql = $pdo->prepare("INSERT INTO tag (name) VALUES (:n);");
            $sql->bindValue(":n", $nome);
            $sql->execute();

            return true;

        endif;
    }

    public function createProduto($nome, $tags)
    {
        global $msgERRO;
        global $pdo;

        $sql = $pdo->prepare("SELECT id FROM product WHERE name = :e");
        $sql->bindValue(":e", $nome);
        $sql->execute();

        if ($sql->rowCount() > 0) :
            $msgERRO = "Produto ".$nome." já existe.";
            return false;

        else :

            $sql = $pdo->prepare("INSERT INTO product (name) VALUES (:n);");
            $sql->bindValue(":n", addslashes($nome));
            $sql->execute();

            $id = $pdo->lastInsertId();
            $sql = $pdo->prepare("INSERT INTO product_tag (product_id, tag_id) VALUES (:p, :t);");

            for ($i = 0; $i < count($tags); $i++) {
                $sql->bindValue(":p", addslashes($id));
                $sql->bindValue(":t", addslashes($tags[$i]));
                $sql->execute();
            }
            return true;

        endif;
    }


    public function readTagbyName($nome)
    {
        global $pdo;
        global $result;

        $sql = $pdo->prepare("SELECT * FROM tag WHERE name LIKE ? ");
        $sql->bindValue(1, addslashes("$nome%"));
        $sql->execute();
        $result = $sql->fetchAll();
        return $result;
    }

    public function readProductByName($nome)
    {
        global $pdo;
        global $result;

        $sql = $pdo->prepare("SELECT * FROM product WHERE name LIKE ? order by name");
        $sql->bindValue(1, addslashes("$nome%"));
        $sql->execute();
        $result = $sql->fetchAll();
        return $result;
    }

    public function readRelevancia($nome)
    {
        global $pdo;
        global $result;

        $sql = $pdo->prepare("SELECT ta.name,  ta.id, COUNT(tp.product_id) as qtd_produto
        from tag ta 
        LEFT JOIN product_tag tp on ta.id = tp.tag_id WHERE ta.name LIKE ? GROUP by 1");
        $sql->bindValue(1, addslashes("$nome%"));
        $sql->execute();
        $result = $sql->fetchAll();
        return $result;
    }
    public function readTag()
    {
        global $pdo;
        global $result;

        $sql = $pdo->prepare("SELECT * FROM tag ");
        $sql->execute();
        $result = $sql->fetchAll();
        return $result;
    }

    public function readTagbyId($id)
    {
        global $pdo;
        global $result;

        $stmt = $pdo->prepare("SELECT * FROM tag wHERE id = ? ");
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    public function readProductbyTag($id)
    {
        global $pdo;
        global $result;

        $stmt = $pdo->prepare("SELECT p.name FROM product p INNER JOIN product_tag pt on pt.product_id = p.id
        where pt.tag_id = ? order by 1 ");
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    public function readProductbyId($id)
    {
        global $pdo;
        global $result;

        $stmt = $pdo->prepare("SELECT p.id, p.name,t.tag_id FROM product p INNER JOIN product_tag t on t.product_id = p.id
        WHERE p.id = ?");
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    public function readChecked($id)
    {
        global $pdo;
        global $result;

        $stmt = $pdo->prepare("SELECT ta.name,ta.id,
        case when ta.id in (select id from tag t inner join product_tag pt on t.id = pt.tag_id where pt.product_id = ?) then 'checked' else '' end as checkbox
        from tag ta ");
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    public function editeTag($nome, $id)
    {
        global $pdo;
        global $msgERRO;
        
        $sql = $pdo->prepare("SELECT id FROM tag WHERE name = :e and id <> :i");
        $sql->bindValue(":e", $nome);
        $sql->bindValue(":i", $id);
        $sql->execute();

        if ($sql->rowCount() > 0) :
             $msgERRO = "Tag ".$nome." já existe.";
            return false;

        else :

            $sql = $pdo->prepare("UPDATE tag  SET  name = :n WHERE id = :i");
            $sql->bindValue(":n", $nome);
            $sql->bindValue(":i", $id);
            $sql->execute();

            return true;

        endif;
    }


    public function editProduto($nome, $id, $tags)
    {
        global $msgERRO;
        global $pdo;
        try {

            $sqlSelect = $pdo->prepare("SELECT id FROM product WHERE name = :e and id <> :i");
            $sqlSelect->bindValue(":e", $nome);
            $sqlSelect->bindValue(":i", $id);
            $sqlSelect->execute();

            if ($sqlSelect->rowCount() == 0) {

                $sql = $pdo->prepare("UPDATE product  SET  name = :n  WHERE id = :i");
                $sql->bindValue(":n", $nome);
                $sql->bindValue(":i", $id);
                $sql->execute();

                $sqlDelete = $pdo->prepare("DELETE FROM product_tag WHERE product_id = :i;");
                $sqlDelete->bindValue(":i", $id);
                $sqlDelete->execute();

                $sqlInsert = $pdo->prepare("INSERT INTO product_tag (product_id, tag_id) VALUES (:p, :t);");

                for ($i = 0; $i < count($tags); $i++) {
                    $sqlInsert->bindValue(":p", addslashes($id));
                    $sqlInsert->bindValue(":t", addslashes($tags[$i]));
                    $sqlInsert->execute();
                }
                return true;
            } else {
                $msgERRO = "Produto já existe.";
            }
        } catch (PDOException $e) {
            $msgERRO = $e->getMessage();
            return false;
        }
    }

    public function deleteTag($id)
    {
        global $pdo;
        global $msgERRO;

        try {

            $sqlTag= $pdo->prepare("DELETE FROM tag wHERE id = :i");
            $sqlTag->bindValue(":i", $id);
            $sqlTag ->execute();

            $sql = $pdo->prepare("DELETE FROM product_tag wHERE tag_id	= :i");
            $sql->bindValue(":i", $id);
            $sql->execute();

        } catch (PDOException $e) {
            $msgERRO = $e->getMessage();
        }
    }

    public function deleteProduct($id)
    {
        global $pdo;
        global $msgERRO;

        try {

            $sql = $pdo->prepare("DELETE FROM product wHERE id = :i");
            $sql->bindValue(":i", $id);
            $sql->execute();
         
            $sql = $pdo->prepare("DELETE FROM product_tag  wHERE product_id  = :p");
            $sql->bindValue(":p", $id);
            $sql->execute();
   
        } catch (PDOException $e) {
            $msgERRO = $e->getMessage();
        }
    }
}
