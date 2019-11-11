<?php

class viaje
{
    private $conn;

    public $idOrigen;
    public $idDestino;
    public $idViaje;
    public $idConductor;
    public $idVehiculo;
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
    public $created;
    public $updated;
    public $departureDate;
    public $arriveDate;
    public $departure;
    public $arrive;
    public $destinos = array();
    public $origenes = array();
    public $viajes = array();

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function getOrigenes()
    {
        $query = "SELECT * FROM places";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            $num = $stmt->rowCount();

            for ($i = 0; $i < $num; $i++) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $this->idOrigen = $row['idPlace'];
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
        $query = "SELECT * FROM places";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            $num = $stmt->rowCount();

            for ($i = 0; $i < $num; $i++) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $this->idDestino = $row['idPlace'];
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
        WHERE idOrigen = ? && idDestino = ? && capacidad >= ? && DATE(departureDate)= ? ";
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
                $this->departureDate = $row['departureDate'];
                $this->arriveDate = $row['arriveDate'];

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
                    'precio' => $this->precio,
                    'capacidad' => $this->capacidad,
                    'idConductor' => $this->idConductor,
                    'Conductor' => $this->Conductor,
                    'placa' => $this->placa,
                    'marca' => $this->marca,
                    'modelo' => $this->modelo,
                    'seguro' => $this->seguro,
                    'numSeguro' => $this->numSeguro,
                    'SOAT' => $this->SOAT,
                    'departureDate' => $this->departureDate,
                    'arriveDate' => $this->arriveDate
                );
            }
            return true;
        }
        return false;
    }

    function getAllTrips()
    {
        $idViaje_set = !empty($this->idViaje) ? " && idViaje != ? LIMIT 0,1" : "";
        $query = "SELECT * FROM viajesInfo {$idViaje_set}";
        $stmt = $this->conn->prepare($query);


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
                $this->created = $row['created'];
                $this->updated = $row['updated'];
                $this->departureDate = $row['departureDate'];
                $this->arriveDate = $row['arriveDate'];
                $this->departure = $row['departure'];
                $this->arrive = $row['arrive'];

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
                    'precio' => $this->precio,
                    'capacidad' => $this->capacidad,
                    'idConductor' => $this->idConductor,
                    'Conductor' => $this->Conductor,
                    'placa' => $this->placa,
                    'marca' => $this->marca,
                    'modelo' => $this->modelo,
                    'seguro' => $this->seguro,
                    'numSeguro' => $this->numSeguro,
                    'SOAT' => $this->SOAT,
                    'created' => $this->created,
                    'updated' => $this->updated,
                    'departureDate' => $this->departureDate,
                    'arriveDate' => $this->arriveDate,
                    'departure' => $this->departure,
                    'arrive' => $this->arrive
                );
            }
            return true;
        }
        return false;
    }

    function createViaje()
    {
        $query = "call registrarViaje(:idConductor, :idVehiculo, :idOrigen, :idDestino, :precio, :departureDate, :arriveDate)";
        $stmt = $this->conn->prepare($query);

        $this->idConductor = htmlspecialchars(strip_tags($this->idConductor));
        $this->idVehiculo = htmlspecialchars(strip_tags($this->idVehiculo));
        $this->idOrigen = htmlspecialchars(strip_tags($this->idOrigen));
        $this->idDestino = htmlspecialchars(strip_tags($this->idDestino));
        $this->precio = htmlspecialchars(strip_tags($this->precio));
        $this->departureDate = htmlspecialchars(strip_tags($this->departureDate));
        $this->arriveDate = htmlspecialchars(strip_tags($this->arriveDate));

        $stmt->bindParam(':idConductor', $this->idConductor);
        $stmt->bindParam(':idVehiculo', $this->idVehiculo);
        $stmt->bindParam(':idOrigen', $this->idOrigen);
        $stmt->bindParam(':idDestino', $this->idDestino);
        $stmt->bindParam(':precio', $this->precio);
        $stmt->bindParam(':departureDate', $this->departureDate);
        $stmt->bindParam(':arriveDate', $this->arriveDate);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
