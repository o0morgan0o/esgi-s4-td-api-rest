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
                "last_name" => $row["first_name"],
                "email" => $row["email"],
                "avatar" => $row["avatar"]
            ];
        }
        return $data;
    }

    public function getById(string $id): bool|array
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

    public function delete(string $id): int
    {
        $statement = $this->connection->prepare("DELETE FROM users WHERE id = :id");
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        $statement->execute();
        // on retourne le nombre de lignes affectées
        // cela permet de savoir si la suppression a bien eu lieu
        return $statement->rowCount();
    }

    public function update(string $id, array $data): int
    {
        // un utilisateur n'est pas obligé de transmettre toutes les données lors de la mise à jour
        // il nous faut un moyen de laisser la flexibilité de mettre à jour uniquement les données souhaitées

        $fields = [];
        if (!empty($data["first_name"])) {
            $fields["first_name"] = [$data["first_name"], PDO::PARAM_STR];
        }
        if (!empty($data["last_name"])) {
            $fields["last_name"] = [$data["last_name"], PDO::PARAM_STR];
        }
        if (!empty($data["email"] && filter_var($data["email"], FILTER_VALIDATE_EMAIL))) {
            $fields["email"] = [$data["email"], PDO::PARAM_STR];
        }

        if (!empty($data["avatar"])) {
            $fields["avatar"] = [$data["avatar"], PDO::PARAM_STR];
        }

        $sql_prepared = "UPDATE users SET " . implode(", ", array_map(fn($key) => "$key = :$key", array_keys($fields))) . " WHERE id = :id";

        $statement = $this->connection->prepare($sql_prepared);

        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        foreach ($fields as $key => $value) {
            $statement->bindValue(":$key", $value[0], $value[1]);
        }
        $statement->execute();
        return $statement->rowCount();
    }


}
