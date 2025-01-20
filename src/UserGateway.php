<?php

class UserGateway
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAll(): array
    {
        $statement = $this->connection->query("SELECT * FROM users");
        // Si on voulait tout retourner d'un coup on pourrait utiliser fetchAll
        // return $statement->fetchAll(PDO::FETCH_ASSOC);

        // mais ici nous pouvons adapater les données
        $data = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $data[] = [
                "id" => $row["id"],
                "first_name" => $row["first_name"],
                "last_name" => $row["last_name"],
                "email" => $row["email"],
                "avatar" => $row["avatar"]
            ];
        }
        return $data;
    }

    public function getById(string $id)
    {
        $statement = $this->connection->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $statement->bindValue(":id", $id, PDO::PARAM_INT);

        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return [
                "id" => $result["id"],
                "first_name" => $result["first_name"],
                "last_name" => $result["first_name"],
                "email" => $result["email"],
                "avatar" => $result["avatar"]
            ];
        }
        return false;
    }

    public function insert(array $data): string
    {
        $statement = $this->connection->prepare("INSERT INTO users (first_name, last_name, email, avatar) VALUES (:first_name, :last_name, :email, :avatar)");
        $statement->bindValue(":first_name", $data["first_name"], PDO::PARAM_STR);
        $statement->bindValue(":last_name", $data["last_name"], PDO::PARAM_STR);
        $statement->bindValue(":email", $data["email"], PDO::PARAM_STR);
        $statement->execute();
        return $this->connection->lastInsertId();
    }


}
