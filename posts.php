<?php
require("autoload.php");
session_start();

if(!isset($_SESSION["auth"])){
    header("Location: /login.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $db = new DatabaseConnection();
    $deleteStmt = $db->pdo->prepare("DELETE FROM posts WHERE id = :id AND user_id = :user_id;");
    $deleteStmt->bindValue(":id", $_POST["post_id"]);
    $deleteStmt->bindValue(":user_id", $_SESSION["user_id"]);
    $deleteStmt->execute();
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
    <nav class="navbar navbar-expand bg-dark" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/index.php">Home</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <?php echo $_SESSION["user_name"]; ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="me-auto"><a class="dropdown-item" href="/logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row my-4 align-items-center justify-content-between">
            <div class="col-4">
                <p class="h3">Meus posts:</p>
            </div>
            <div class="col-4">
                <a href="/write.php"><button class="btn btn-success text-light">Criar novo post!</button></a>
            </div>
        </div>
<?php
    $db = new DatabaseConnection();
    $pdo = $db->pdo;
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = :user_id;");
    $stmt->bindValue(":user_id", $_SESSION["user_id"]);
    $stmt->execute();

    while(true) {
        $row = $stmt->fetch(PDO::FETCH_NUM);
        if($row == FALSE){
            break;
        }
?>
        <div class="row mb-5 border border-1 border-gray">
            <div class="row p-3 m-0 border-bottom border-2 border-black align-items-center">
                <div class="col">
                    <p class="h4 p-0 m-0">
                        <?php echo $row[1]; ?>
                    </p>
                </div>
                <div class="col">
                    <div class="row justify-content-end">
                        <div class="col-auto p-1">
                            <a href="/edit.php?id=<?php echo $row[0];?>">
                                <button class="btn btn-sm btn-secondary">
                                    <i class="bi-pencil" style="color:white;"></i>
                                </button>
                            </a>
                        </div>
                        <div class="col-auto p-1">
                            <form action="posts.php" method="post">
                                <input type="hidden" name="post_id" value="<?php echo $row[0];?>"><button
                                    class="btn btn-sm btn-danger"><i class="bi-eraser" style="color: white;"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class=" row p-3 m-0">
                <p class="p-0 m-0">
                    <?php echo $row[2]; ?>
                </p>
            </div>
        </div>
<?php } ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>
