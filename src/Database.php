<?php

class Database {

    public function getConnection(): PDO {
        $dsn = "sqlite:" . __DIR__ . "/../users.sqlite";
        return new PDO($dsn , null , null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION // indique que nous voulons une exception en cas d'erreur
        ]);
    }
}
