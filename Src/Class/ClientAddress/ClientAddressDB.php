<?php

require_once __DIR__ . "/ClientAddress.php";

class ClientAddressDB
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAddressByClientID(int $clientId): ?ClientAddress
    {
        $stmt = $this->pdo->prepare(
            "SELECT clt_id, ld_number, ld_road, ld_neighborhood, ld_city,
            ld_state, ld_country, ld_cep
            FROM LocationData   
            WHERE clt_id = :id"
        );
        $stmt->execute(['id' => $clientId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }
        return $this->fromRow($row);
    }

    public function createAddress(
        int $clientId,
        int $number,
        string $road,
        string $neighborhood,
        string $city,
        string $state,
        string $country,
        int $cep
    ): ClientAddress {
        $stmt = $this->pdo->prepare(
            "INSERT INTO LocationData
            (clt_id, ld_number, ld_road, ld_neighborhood, ld_city, ld_state, ld_country, ld_cep)
            VALUES (:clientId, :number, :road, :neighborhood, :city, :state, :country, :cep)"
        );
        $stmt->execute([
            'clientId' => $clientId,
            'number' => $number,
            'road' => $road,
            'neighborhood' => $neighborhood,
            'city' => $city,
            'state' => $state,
            'country' => $country,
            'cep' => $cep,
        ]);

        return new ClientAddress($clientId, $number, $road, $neighborhood, $city, $state, $country, $cep);
    }

    public function updateAddress(ClientAddress $address): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE LocationData
             SET ld_number = :number, ld_road = :road, ld_neighborhood = :neighborhood,
                 ld_city = :city, ld_state = :state, ld_country = :country, ld_cep = :cep
             WHERE clt_id = :clientId"
        );
        $stmt->execute([
            'number' => $address->getNumber(),
            'road' => $address->getRoad(),
            'neighborhood' => $address->getNeighborhood(),
            'city' => $address->getCity(),
            'state' => $address->getState(),
            'country' => $address->getCountry(),
            'cep' => $address->getCEP(),
            'clientId' => $address->getId(),
        ]);
    }

    private function fromRow(array $row): ClientAddress
    {
        return new ClientAddress(
            (int) $row['clt_id'],
            (int) $row['ld_number'],
            $row['ld_road'],
            $row['ld_neighborhood'],
            $row['ld_city'],
            $row['ld_state'],
            $row['ld_country'],
            (int) $row['ld_cep']
        );
    }
}