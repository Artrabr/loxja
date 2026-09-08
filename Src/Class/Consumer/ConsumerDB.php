<?php

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
    public function createConsumer(string $name,string $email, string $password): ?Consumer
    {
        $stmt = $this->pdo->prepare("SELECT clt_email,clt_name,clt_id FROM consumers WHERE clt_email = ?");
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Consumer($row['clt_id'], $row['clt_name'], $row['clt_email']);
        }

        return null;
    }
}