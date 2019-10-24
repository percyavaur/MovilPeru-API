<?php

class vehiculo
{
    private $conn;

    public $idVehiculo;
    public $idEstado;
    public $capacidad;
    public $placa;
    public $marca;
    public $modelo;
    public $seguro;
    public $numSeguro;
    public $SOAT;
    public $vehiculos;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function getVehiculos()
    {
        $query = "SELECT idVehiculo, idEstado, capacidad, placa, marca, modelo, seguro, numSeguro, SOAT FROM `vehiculos`";

        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            $num = $stmt->rowCount();
            for ($i = 0; $i < $num; $i++) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $this->idVehiculo = $row['idVehiculo'];
                $this->idEstado = $row['idEstado'];
                $this->capacidad = $row['capacidad'];
                $this->placa = $row['placa'];
                $this->marca = $row['marca'];
                $this->modelo = $row['modelo'];
                $this->seguro = $row['seguro'];
                $this->numSeguro = $row['numSeguro'];
                $this->SOAT = $row['SOAT'];

                $this->vehiculos[$i] = array(
                    'idVehiculo' => $this->idVehiculo,
                    'idEstado' => $this->idEstado,
                    'capacidad' => $this->capacidad,
                    'placa' => $this->placa,
                    'marca' => $this->marca,
                    'modelo' => $this->modelo,
                    'seguro' => $this->seguro,
                    'numSeguro' => $this->numSeguro,
                    'SOAT' => $this->SOAT
                );
            }
            return true;
        }
        return false;
    }
}
