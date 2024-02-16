<?php

$user = "";

require("db.php");
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $user = $_POST["usuario"];
    $senha = $_POST["senha"];
    $db = new DatabaseConnection;
    $stmt = $db->pdo->prepare("SELECT * FROM users WHERE name = :nome AND password = :senha;");
    $stmt->bindValue(":nome", $user, PDO::PARAM_STR);
    $stmt->bindValue(":senha", hash("sha256", $senha));
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_BOTH);
    if($row == FALSE) {
        $error = true;
    } else {
        session_start();
        $_SESSION["auth"] = true;
        $_SESSION["user_id"] = $row[0];
        $_SESSION["user_name"] = $row[1];
        header("Location: /posts.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar bg-dark" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/home.php">Home</a>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="mt-4 row">
                <p class="h3">Login</p>
            </div>
            <div class="row">
                <form action="/login.php" method="post">
                    <div class="mb-3 col-md-3">
                        <label class="form-label" for="user">Usuario</label>
                        <input class="form-control" type="text" name="usuario" placeholder="Nome do usuario" id="user"
                            value="<?= $user; ?>" required>
                    </div>
                    <div class="mb-3 col-md-3">
                        <label class="form-label" for="senha">Senha</label>
                        <input class="form-control" type="password" name="senha" placeholder="Sua senha" id="senha"
                            value="" required>
                    </div>
                    <?php if(isset($error)){ ?>
                    <div class="col">
                        <p class="text-danger align-self-center">Usuario ou senha incorretos!</p>
                    </div>
                    <?php } ?>
                    <div class="col">
                        <p>Não tem conta?  <a href="register.php">Registrar-se</a></p>
                    </div>
                    <div class="col">
                        <input class="btn btn-primary" type="submit" value="Login">
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
