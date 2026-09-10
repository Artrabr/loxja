<?php

require_once __DIR__ . "/../../bootstrap.php";

/**
 * impede mensagens de "constante desconhecida"
 * @var string DB_HOST
 * @var string DB_NAME
 * @var string DB_CHARSET
 * @var string DB_USERNAME
 * @var string DB_PASSWORD
 */

class ConnectionTests
{
    private static $pdo;
    public static function connect()
    {
        if (!self::$pdo) {
            self::$pdo = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";charset=" . DB_CHARSET, DB_USERNAME, DB_PASSWORD);
        }
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return self::$pdo;
    }
    public static function createDBIfNeededAndConnect()
    {
        if (!self::$pdo) {
            self::connect();
        }
        self::$pdo->exec("CREATE DATABASE IF NOT EXISTS test_db_delete;");
        self::$pdo->exec("USE test_db_delete;");
    }
    public static function deleteDBIfItExists()
    {
        if (!self::$pdo) {
            self::connect();
        }

        self::$pdo->exec("DROP DATABASE IF EXISTS test_db_delete;");
    }
    public static function InitializeScheme()
    {
        self::createDBIfNeededAndConnect();
        self::$pdo->exec("
            -- ---------------------------------------------------------
            -- Tabela de Clientes
            -- ---------------------------------------------------------
            CREATE TABLE Client (
                clt_id            INT AUTO_INCREMENT PRIMARY KEY,
                clt_name          VARCHAR(30)  NOT NULL,
                clt_email         VARCHAR(50)  NOT NULL UNIQUE,
                clt_password      VARCHAR(255) NOT NULL, -- deve ser guardada como hash (ex: bcrypt)
                clt_perfilPicture VARCHAR(255),
                clt_created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );

            -- ---------------------------------------------------------
            -- Tabela de Produtos
            -- ---------------------------------------------------------
            CREATE TABLE Product (
                pdt_id          INT AUTO_INCREMENT PRIMARY KEY,
                pdt_name        VARCHAR(30)   NOT NULL,
                pdt_price       DECIMAL(10,2) NOT NULL,
                pdt_description VARCHAR(300),
                pdt_amount      INT NOT NULL DEFAULT 0,
                pdt_category    VARCHAR(50)
            );

            -- ---------------------------------------------------------
            -- Tabela do Carrinho de Compras (associativa Cliente x Produto)
            -- Cada linha representa 1 produto dentro do carrinho de 1 cliente
            -- Cardinalidade real: (1,1) para Cliente e (1,1) para Produto
            -- (o diagrama tinha um erro de digitação marcando (1,n) do lado
            --  de Produtos; o correto, como já estava implementado aqui, é (1,1))
            -- ---------------------------------------------------------
            CREATE TABLE ItensCart (
                clt_id    INT NOT NULL,
                pdt_id    INT NOT NULL,
                ic_amount INT NOT NULL DEFAULT 1,

                PRIMARY KEY (clt_id, pdt_id),

                CONSTRAINT fk_itens_cart_client
                    FOREIGN KEY (clt_id) REFERENCES Client(clt_id)
                    ON DELETE CASCADE ON UPDATE CASCADE,

                CONSTRAINT fk_itens_cart_product
                    FOREIGN KEY (pdt_id) REFERENCES Product(pdt_id)
                    ON DELETE CASCADE ON UPDATE CASCADE,

                -- garante que a quantidade nunca seja zero ou negativa
                CONSTRAINT chk_ic_amount_positive CHECK (ic_amount > 0)
            );
        ");
        return self::$pdo;
    }
}
