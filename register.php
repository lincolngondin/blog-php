<?php

use Ramsey\Uuid\Uuid;
require("vendor/autoload.php");

$error = false;
$error_msg = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){

    require("db.php");
    require("date.php");

    $user = $_POST["usuario"];
    $pass = $_POST["senha"];

    $db = new DatabaseConnection();
    $insertUserStmt = $db->pdo->prepare("INSERT INTO users (id, name, password, creation_time) VALUES (:id, :nome, :senha, :criacao);");
    if ($insertUserStmt == false) {
        echo "Error preparing statement to database!";
    }

    

    $newUUID = Uuid::uuid4()->toString();
    $insertUserStmt->bindValue(":id", substr($newUUID, 24), PDO::PARAM_STR);
    $insertUserStmt->bindValue(":nome", $user, PDO::PARAM_STR);
    $insertUserStmt->bindValue(":senha", hash("sha256", $pass), PDO::PARAM_STR);
    $insertUserStmt->bindValue(":criacao", GetActualTime());
    try {
        $sucess = $insertUserStmt->execute();
        if($sucess == FALSE) {
            $error = true;
            $error_msg = "Nome de usuario ou senha inválidos!";
        }
        else {
            header("Location: /login.php");
        }
    } catch(PDOException $err) {
        $error_msg = "Nome de usuario já está sendo utilizado!";
        $error = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="mt-4 row">
                <p class="h3">Criar conta</p>
            </div>
            <div class="row">
                <form action="/register.php" method="post">
                    <div class="mb-3 col-md-3">
                        <label class="form-label" for="user">Usuario</label>
                        <input class="form-control" type="text" name="usuario" placeholder="Nome do usuario" id="user"
                            value="" required>
                    </div>
                    <div class="mb-3 col-md-3">
                        <label class="form-label" for="senha">Senha</label>
                        <input class="form-control" type="password" name="senha" placeholder="Sua senha" id="senha"
                            value="" required>
                    </div>
                    <?php if($error){ ?>
                    <div class="col">
                        <p class="text-danger">
                            <?= $error_msg ?>
                        </p>
                    </div>
                    <?php } ?>
                    <div class="col">
                        <input class="btn btn-primary" type="submit" value="Registrar">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>
