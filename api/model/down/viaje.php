<?php

class viaje
{
    private $conn;

    public $idOrigen;
    public $idDestino;
    public $idViaje;
    public $idConductor;
    public $Origen;
    public $Destino;
    public $departamento;
    public $distrito;
    public $direccion;
    public $fechaSalida;
    public $horaSalida;
    public $precio;
    public $capacidad;
    public $cantPasajeros;
    public $conductor;
    public $placa;
    public $marca;
    public $modelo;
    public $seguro;
    public $numSeguro;
    public $SOAT;
    public $depDestino;
    public $disDestino;
    public $dirDestino;
    public $depOrigen;
    public $disOrigen;
    public $dirOrigen;
    public $destinos = array();
    public $origenes = array();
    public $viajes = array();

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

    function getViajes()
    {
        $query = "SELECT * FROM viajesInfo
        WHERE idOrigen = ? && idDestino = ? && capacidad >= ? && fechaSalida = ? ";
        $stmt = $this->conn->prepare($query);

        $this->idOrigen = htmlspecialchars(strip_tags($this->idOrigen));
        $this->idDestino = htmlspecialchars(strip_tags($this->idDestino));
        $this->cantPasajeros = htmlspecialchars(strip_tags($this->cantPasajeros));
        $this->fechaSalida = htmlspecialchars(strip_tags($this->fechaSalida));

        $stmt->bindParam(1, $this->idOrigen);
        $stmt->bindParam(2, $this->idDestino);
        $stmt->bindParam(3, $this->cantPasajeros);
        $stmt->bindParam(4, $this->fechaSalida);

        if ($stmt->execute()) {
            $num = $stmt->rowCount();

            for ($i = 0; $i < $num; $i++) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $this->idViaje = $row['idViaje'];
                $this->idOrigen = $row['idOrigen'];
                $this->Origen = $row['Origen'];
                $this->depOrigen = $row['depOrigen'];
                $this->disOrigen = $row['disOrigen'];
                $this->dirOrigen = $row['dirOrigen'];
                $this->idDestino = $row['idDestino'];
                $this->Destino = $row['Destino'];
                $this->depDestino = $row['depDestino'];
                $this->disDestino = $row['disDestino'];
                $this->dirDestino = $row['dirDestino'];
                $this->fechaSalida = $row['fechaSalida'];
                $this->horaSalida = $row['horaSalida'];
                $this->precio = $row['precio'];
                $this->capacidad = $row['capacidad'];
                $this->idConductor = $row['idConductor'];
                $this->Conductor = $row['Conductor'];
                $this->placa = $row['placa'];
                $this->marca = $row['marca'];
                $this->modelo = $row['modelo'];
                $this->seguro = $row['seguro'];
                $this->numSeguro = $row['numSeguro'];
                $this->SOAT = $row['SOAT'];

                $this->viajes[$i] = array(
                    'idViaje' => $this->idViaje,
                    'idOrigen' => $this->idOrigen,
                    'Origen' => $this->Origen,
                    'depOrigen' => $this->depOrigen,
                    'disOrigen' => $this->disOrigen,
                    'dirOrigen' => $this->dirOrigen,
                    'idDestino' => $this->idDestino,
                    'Destino' => $this->Destino,
                    'depDestino' => $this->depDestino,
                    'disDestino' => $this->disDestino,
                    'dirDestino' => $this->dirDestino,
                    'fechaSalida' => $this->fechaSalida,
                    'horaSalida' => $this->horaSalida,
                    'precio' => $this->precio,
                    'capacidad' => $this->capacidad,
                    'idConductor' => $this->idConductor,
                    'Conductor' => $this->Conductor,
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
