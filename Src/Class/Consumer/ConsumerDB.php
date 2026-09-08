<?php

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

    public function getConsumerByID(int $id): ?Consumer
    {
        $stmt = $this->pdo->prepare("SELECT clt_email,clt_name,clt_id FROM consumers WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Consumer($row['clt_id'], $row['clt_name'], $row['clt_email']);
        }

        return null;
    }
    public function getConsumerByEmail(string $email): ?Consumer
    {
        $stmt = $this->pdo->prepare("SELECT clt_email,clt_name,clt_id FROM consumers WHERE clt_email = ?");
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Consumer($row['clt_id'], $row['clt_name'], $row['clt_email']);
        }

        return null;
    }
    public function getConsumerByName(string $name): ?Consumer
    {
        $stmt = $this->pdo->prepare("SELECT clt_email,clt_name,clt_id FROM consumers WHERE clt_name = ?");
        $stmt->execute([$name]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Consumer($row['clt_id'], $row['clt_name'], $row['clt_email']);
        }

        return null;
    }
    public function createConsumer(string $name, string $email, string $password): ?Consumer
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        try {
            $stmt = $this->pdo->prepare("INSERT INTO Client (clt_name,clt_email,clt_password) VALUES (:name,:email,:password)");
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'password' => $hashedPassword]);
            $id = $this->pdo->lastInsertId();

            return new Consumer($id, $name, $email);
        } catch (PDOException $e) {
            //error for when the email already exists
            if ($e->getCode() == '23000') {
                throw new DuplicateEmail("Email already used", 409);
            }
            throw $e;
        }

        return null;
    }
}

