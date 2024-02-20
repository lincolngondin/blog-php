<?php
require("autoload.php");
require("utils/date.php");
session_start();

if(!isset($_SESSION["auth"])){
    header("Location: /login.php");
}

$db = new DatabaseConnection();

if($_SERVER["REQUEST_METHOD"] == "GET"){
    if(!isset($_GET["id"])){
        header("HTTP/1.1 404 Not Found");
        exit();
    }
    $post_id = $_GET["id"];
    if($post_id == ""){
        header("HTTP/1.1 404 Not Found");
        exit();
    }

    $stmt = $db->pdo->prepare("SELECT * FROM posts WHERE id = :id AND user_id = :user_id;");
    $stmt->bindValue(":id", $post_id, PDO::PARAM_STR);
    $stmt->bindValue(":user_id", $_SESSION["user_id"], PDO::PARAM_STR);
    try{
        $stmt->execute();
    } 
    catch(PDOException $err) {
        header("HTTP/1.1 404 Not Found");
        exit();
    }
    $row = $stmt->fetch(PDO::FETCH_NUM);
    if($row == FALSE){
        header("HTTP/1.1 404 Not Found");
        exit();
    }

} else if($_SERVER["REQUEST_METHOD"] == "POST"){
    $newTitle = $_POST["title"];
    $newContent = $_POST["content"];
    $post_id = $_POST["post_id"];
    $updateStmt = $db->pdo->prepare("UPDATE posts SET title = :title, content = :content, last_update = :du WHERE id = :id AND user_id = :user_id;");
    $updateStmt->bindValue(":title", $newTitle);
    $updateStmt->bindValue(":content", $newContent);
    $updateStmt->bindValue(":du", GetActualTime(), PDO::PARAM_STR);
    $updateStmt->bindValue(":id", $post_id);
    $updateStmt->bindValue(":user_id", $_SESSION["user_id"]);
    $updateStmt->execute();
    header("Location: /posts.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Meus posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>

    <nav class="navbar bg-dark" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/posts.php">Home</a>
        </div>
    </nav>
    <div class="container">
        <div class="row my-3">
            <p class="h2 my-0">Editar Post</p>
        </div>
        <div class="row">
            <form action="/edit.php" method="post">
                <div class="my-2 col">
                    <label class="form-text" for="title">Titulo:</label>
                    <input class="form-control" type="text" name="title" id="title" value="<?php echo $row[1];?>"
                        required>
                </div>
                <div class="my-2 col">
                    <label class="form-text" for="content">Conteudo:</label>
                    <textarea class="form-control" id="content" name="content" cols="30" rows="10">
                        <?php echo $row[2]; ?>
                    </textarea>
                </div>
                <div class="my-2 col">
                    <input class="btn btn-primary" type="submit" value="Editar Post">
                </div>
                <input type="hidden" value="<?php echo $post_id; ?>" name="post_id">
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>
