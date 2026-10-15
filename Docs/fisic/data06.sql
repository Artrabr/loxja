-- =========================================================
-- loxjaDataBase - Script de criação do banco
-- =========================================================

DROP DATABASE   loxjadatabase;
CREATE DATABASE loxjaDataBase;
USE             loxjaDataBase;

-- ---------------------------------------------------------
-- Tabela de Clientes
-- ---------------------------------------------------------
CREATE TABLE Client (
    clt_id            INT AUTO_INCREMENT PRIMARY KEY,
    clt_name          VARCHAR(30)  NOT NULL,
    clt_email         VARCHAR(50)  NOT NULL UNIQUE,
    clt_password      VARCHAR(255) NOT NULL, -- deve ser guardada como hash (ex: bcrypt)
    clt_perfilPicture VARCHAR(255),
    clt_image		  VARCHAR(255),
    clt_created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------------
-- Tabela de Produtos
-- ---------------------------------------------------------
CREATE TABLE Product (
    pdt_id          INT AUTO_INCREMENT PRIMARY KEY,
    pdt_name        VARCHAR(30)   NOT NULL,
    pdt_price       DECIMAL(10,2) NOT NULL,
    pdt_description VARCHAR(500),
    pdt_amount      INT NOT NULL DEFAULT 0,
    pdt_category    VARCHAR(50),
    pdt_image		VARCHAR(255)
);

-- ---------------------------------------------------------
-- Tabela de itens no carrinho
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

-- ---------------------------------------------------------
-- Tabela de localização
-- ---------------------------------------------------------

CREATE TABLE LocationData (
    clt_id            INT NOT NULL,
    ld_cep            INT,
	ld_number         INT,
    ld_road           VARCHAR(70),
    ld_neighborhood   VARCHAR(70),
    ld_city           VARCHAR(70),
    ld_state          VARCHAR(70),
    ld_country         VARCHAR(70),
    
    CONSTRAINT fk_client_location
        FOREIGN KEY (clt_id) REFERENCES Client(clt_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- ---------------------------------------------------------
-- Tabela de relatorios (CORRIGIDA)
-- ---------------------------------------------------------

CREATE TABLE saleReport (
    saleReport_id         INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    clt_id                INT NOT NULL,
    pdt_id                INT NOT NULL,
    saleReport_amount     INT NOT NULL DEFAULT 1,
    saleReport_method     VARCHAR(20) NOT NULL,
    saleReport_price      DECIMAL(10,2) NOT NULL,
    saleReport_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- Removida a segunda PRIMARY KEY para evitar conflito

    CONSTRAINT fk_salereport_client
        FOREIGN KEY (clt_id) REFERENCES Client(clt_id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_salereport_product
        FOREIGN KEY (pdt_id) REFERENCES Product(pdt_id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    -- Corrigido para validar o nome correto da coluna desta tabela
    CONSTRAINT chk_salereport_amount_positive CHECK (saleReport_amount > 0)
);
