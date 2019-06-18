<?php

class viaje
{
    private $conn;

    public $idOrigen;
    public $idDestino;
    public $idViaje;
    public $Origen;
    public $Destino;
    public $departamento;
    public $distrito;
    public $direccion;
    public $destinos = array();
    public $origenes = array();

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function getOrigenes()
    {
        $query = "SELECT * FROM Origen";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            $num = $stmt->rowCount();

            for ($i = 0; $i < $num; $i++) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $this->idOrigen = $row['idOrigen'];
                $this->departamento = $row['departamento'];
                $this->distrito = $row['distrito'];
                $this->direccion = $row['direccion'];

                $this->origenes[$i] = array(
                    'idOrigen' => $this->idOrigen,
                    'departamento' => $this->departamento,
                    'distrito' => $this->distrito,
                    'direccion' => $this->direccion
                );
            }
            return true;
        }
        return false;
    }

    function getDestinos()
    {
        $query = "SELECT * FROM Destino";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            $num = $stmt->rowCount();

            for ($i = 0; $i < $num; $i++) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $this->idDestino = $row['idDestino'];
                $this->departamento = $row['departamento'];
                $this->distrito = $row['distrito'];
                $this->direccion = $row['direccion'];

                $this->destinos[$i] = array(
                    'idDestino' => $this->idDestino,
                    'departamento' => $this->departamento,
                    'distrito' => $this->distrito,
                    'direccion' => $this->direccion
                );
            }
            return true;
        }
        return false;
    }
}
