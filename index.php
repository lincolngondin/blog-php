<?php
include("autoload.php");
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Página inicial</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand bg-dark" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/index.php">Home</a>
            <?php if(!isset($_SESSION["auth"])){ ?>
            <a class="nav-link active text-white" href="/login.php">Login</a>
            <?php } else { ?>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <?php echo $_SESSION["user_name"]; ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="me-auto"><a class="dropdown-item" href="/posts.php">Meus posts</a></li>
                            <li class="me-auto"><a class="dropdown-item" href="/logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <?php } ?>
        </div>
    </nav>
    <div class="container">
        <div class="row my-4 align-items-center">
            <div class="col">
                <p class="h3">Posts:</p>
            </div>
        </div>
<?php
    require("utils/date.php");
    $db = new DatabaseConnection();
    $stmt = $db->pdo->prepare("SELECT * FROM posts ORDER BY creation_time DESC LIMIT 100;");
    $stmt->execute();

    while(true) {
        $row = $stmt->fetch(PDO::FETCH_NUM);
        if($row == FALSE){
            break;
        }
?>
        <div class="row mb-5 border border-1 border-gray">
            <div class="row p-3 m-0 border-bottom border-2 border-black">
                <div class="col">
                    <p class="h4 p-0 m-0">
                        <?php echo $row[1]; ?>
                    </p>
                </div>
                <div class="col">
                    <p>Postado por <a href="#">
                            <?php echo $row[3]; ?>
                        </a> há
                        <?php
                            echo GetInterval($row[5]);
                        ?>
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
