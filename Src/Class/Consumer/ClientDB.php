<?php

require_once __DIR__ . "./Client.php";

class DuplicateEmail extends Exception
{
}

class ConsumerDB
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getClientByID(int $id): ?Client
    {
        $stmt = $this->pdo->prepare("SELECT clt_email,clt_name,clt_id FROM Client WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Client($row['clt_id'], $row['clt_name'], $row['clt_email']);
        }

        return null;
    }
    public function getClientByEmail(string $email): ?Client
    {
        $stmt = $this->pdo->prepare("SELECT clt_email,clt_name,clt_id FROM Client WHERE clt_email = ?");
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Client($row['clt_id'], $row['clt_name'], $row['clt_email']);
        }

        return null;
    }
    public function getClientByName(string $name): ?Client
    {
        $stmt = $this->pdo->prepare("SELECT clt_email,clt_name,clt_id FROM Client WHERE clt_name = ?");
        $stmt->execute([$name]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Client($row['clt_id'], $row['clt_name'], $row['clt_email']);
        }

        return null;
    }
    public function createClient(string $name, string $email, string $password): ?Client
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        try {
            $stmt = $this->pdo->prepare("INSERT INTO Client (clt_name,clt_email,clt_password) VALUES (:name,:email,:password)");
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'password' => $hashedPassword]);
            $id = $this->pdo->lastInsertId();

            return new Client($id, $name, $email);
        } catch (PDOException $e) {
            //error for when the email already exists
            if ($e->getCode() == '23000') {
                throw new DuplicateEmail("Email already used", 409);
            }
            throw $e;
        }
    }
    public function updateClient(Client $client)
    {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE Client
                SET clt_name = ':name'
                SET clt_email = ':email'
                WHERE user_id = :id;
                ");
            $stmt->execute([
                'name' => $client->getName(),
                'email' => $client->getEmail(),
                'id' => $client->getId()
            ]);
        } catch (PDOException $e) {
            //error for when the email already exists
            if ($e->getCode() == '23000') {
                throw new DuplicateEmail("Email already used", 409);
            }
            throw $e;
        }
    }
    public function isRightPassword(string $email, string $password): bool
    {
        $stmt = $this->pdo->prepare("SELECT clt_password FROM Client WHERE clt_email = ?");
        $stmt->execute([$email]);

        $right_password = $stmt->fetchColumn();
        if (password_verify($password, $right_password)) {
            return true;
        }
        return false;
    }
}
