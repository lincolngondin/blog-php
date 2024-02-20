<?php
    session_start();
    if(!isset($_SESSION["auth"])) {
        header("Location: /login.php");
    }

    require("vendor/autoload.php");
    require("autoload.php");
    use Ramsey\Uuid\Uuid;


    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $title = $_POST["title"];
        $content = $_POST["content"];


        $db = new DatabaseConnection();
        $pdo = $db->pdo;
        $stmt = $pdo->prepare("INSERT INTO posts (id, title, content, author, user_id, creation_time, last_update) VALUES (:id, :t, :c, :a, :u, :dc, :du);");
        
        $newUUID = Uuid::uuid4()->toString();
        $stmt->bindValue(":id", $newUUID);
        $stmt->bindValue(":t", $title);
        $stmt->bindValue(":c", $content);
        $stmt->bindValue(":a", $_SESSION["user_name"]);
        $stmt->bindValue(":u", $_SESSION["user_id"]);
        require("utils/date.php");
        $actualTime = GetActualTime();
        $stmt->bindValue(":dc", $actualTime);
        $stmt->bindValue(":du", $actualTime);
        $stmt->execute();
        header("Location: /posts.php");
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Escrever Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar bg-dark" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/posts.php">Home</a>
        </div>
    </nav>
    <div class="container">
        <div class="row my-3">
            <p class="h2 my-0">Criar post</p>
        </div>
        <div class="row">
            <form action="/write.php" method="post">
                <div class="my-2 col">
                    <label class="form-text" for="title">Titulo:</label>
                    <input class="form-control" type="text" name="title" id="title" required>
                </div>
                <div class="my-2 col">
                    <label class="form-text" for="content">Conteudo:</label>
                    <textarea class="form-control" id="content" name="content" cols="30" rows="10"></textarea>
                </div>
                <div class="my-2 col">
                    <input class="btn btn-primary" type="submit" value="Criar post">
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>
