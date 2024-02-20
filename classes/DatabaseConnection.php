<?php declare(strict_types=1);
    class DatabaseConnection {
        public PDO $pdo;
        public function __construct(){
            $this->pdo = new PDO("pgsql:host=localhost;dbname=blog", "postgres", "lincoln");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }
