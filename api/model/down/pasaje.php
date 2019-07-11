<?php

class pasaje
{
    private $conn;

    public $idPasaje;
    public $idVenta;
    public $idUsuario;
    public $comprador;
    public $idCliente;
    public $tipoDocumento;
    public $numDocumento;
    public $apellidos;
    public $nombres;
    public $idaOrigen;
    public $idaDestino;
    public $vueltaOrigen;
    public $vueltaDestino;
    public $fechaCompra;
    public $idaFecha;
    public $idaHora;
    public $vueltaFecha;
    public $vueltaHora;
    public $conductor;
    public $pasajesa = array();

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getPasajesUser()
    {

        $query = "SELECT * FROM pasajesInfo WHERE idUsuario = ? ORDER BY fechaCompra DESC";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->idUsuario);

        if ($stmt->execute()) {

            $num = $stmt->rowCount();

            for ($i = 0; $i < $num; $i++) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                // assign values to object properties
                $this->idPasaje = $row['idPasaje'];
                $this->idVenta = $row['idVenta'];
                $this->idUsuario = $row['idUsuario'];
                $this->comprador = $row['comprador'];
                $this->idCliente = $row['idCliente'];
                $this->tipoDocumento = $row['tipoDocumento'];
                $this->numDocumento = $row['numDocumento'];
                $this->apellidos = $row['apellidos'];
                $this->nombres = $row['nombres'];
                $this->idaOrigen = $row['idaOrigen'];
                $this->idaDestino = $row['idaDestino'];
                $this->vueltaOrigen = $row['vueltaOrigen'];
                $this->vueltaDestino = $row['vueltaDestino'];
                $this->fechaCompra = $row['fechaCompra'];

                $this->pasajesa[$i] = array(
                    'idPasaje' => $this->idPasaje,
                    'idVenta' => $this->idVenta,
                    'idUsuario' => $this->idUsuario,
                    'comprador' => $this->comprador,
                    'idCliente' => $this->idCliente,
                    'tipoDocumento' => $this->tipoDocumento,
                    'numDocumento' => $this->numDocumento,
                    'apellidos' => $this->apellidos,
                    'nombres' => $this->nombres,
                    'idaOrigen' => $this->idaOrigen,
                    'idaDestino' => $this->idaDestino,
                    'vueltaOrigen' => $this->vueltaOrigen,
                    'vueltaDestino' => $this->vueltaDestino,
                    'fechaCompra' => $this->fechaCompra
                );
            }
            return true;
        }
        return false;
    }

    public function tripInfoPassenger()
    {

        $query = "SELECT * FROM tripInfoPassenger WHERE idVenta = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->idVenta);

        if ($stmt->execute()) {

            $num = $stmt->rowCount();

            for ($i = 0; $i < $num; $i++) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                // assign values to object properties
                $this->idPasaje = $row['idPasaje'];
                $this->idUsuario = $row['idUsuario'];
                $this->comprador = $row['comprador'];
                $this->idCliente = $row['idCliente'];
                $this->tipoDocumento = $row['tipoDocumento'];
                $this->numDocumento = $row['numDocumento'];
                $this->apellidos = $row['apellidos'];
                $this->nombres = $row['nombres'];
                $this->idaOrigen = $row['idaOrigen'];
                $this->idaDestino = $row['idaDestino'];
                $this->vueltaOrigen = $row['vueltaOrigen'];
                $this->vueltaDestino = $row['vueltaDestino'];
                $this->fechaCompra = $row['fechaCompra'];
                $this->idaFecha = $row['idaFecha'];
                $this->idaHora = $row['idaHora'];
                $this->vueltaFecha = $row['vueltaFecha'];
                $this->vueltaHora = $row['vueltaHora'];
                $this->conductor = $row['conductor'];

                $this->pasajesa[$i] = array(
                    'idPasaje' => $this->idPasaje,
                    'idVenta' => $this->idVenta,
                    'idUsuario' => $this->idUsuario,
                    'comprador' => $this->comprador,
                    'idCliente' => $this->idCliente,
                    'tipoDocumento' => $this->tipoDocumento,
                    'numDocumento' => $this->numDocumento,
                    'apellidos' => $this->apellidos,
                    'nombres' => $this->nombres,
                    'idaOrigen' => $this->idaOrigen,
                    'idaDestino' => $this->idaDestino,
                    'vueltaOrigen' => $this->vueltaOrigen,
                    'vueltaDestino' => $this->vueltaDestino,
                    'fechaCompra' => $this->fechaCompra,
                    'idaFecha' => $this->idaFecha,
                    'idaHora' => $this->idaHora,
                    'vueltaFecha' => $this->vueltaFecha,
                    'vueltaHora' => $this->vueltaHora,
                    'conductor' => $this->conductor,
                );
            }
            return true;
        }
        return false;
    }

    public function getPasajesGeneral()
    {

        $query = "SELECT * FROM pasajesInfo ORDER BY fechaCompra DESC";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->idUsuario);

        if ($stmt->execute()) {

            $num = $stmt->rowCount();

            for ($i = 0; $i < $num; $i++) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                // assign values to object properties
                $this->idPasaje = $row['idPasaje'];
                $this->idVenta = $row['idVenta'];
                $this->idUsuario = $row['idUsuario'];
                $this->comprador = $row['comprador'];
                $this->idCliente = $row['idCliente'];
                $this->tipoDocumento = $row['tipoDocumento'];
                $this->numDocumento = $row['numDocumento'];
                $this->apellidos = $row['apellidos'];
                $this->nombres = $row['nombres'];
                $this->idaOrigen = $row['idaOrigen'];
                $this->idaDestino = $row['idaDestino'];
                $this->vueltaOrigen = $row['vueltaOrigen'];
                $this->vueltaDestino = $row['vueltaDestino'];
                $this->fechaCompra = $row['fechaCompra'];

                $this->pasajesa[$i] = array(
                    'idPasaje' => $this->idPasaje,
                    'idVenta' => $this->idVenta,
                    'idUsuario' => $this->idUsuario,
                    'comprador' => $this->comprador,
                    'idCliente' => $this->idCliente,
                    'tipoDocumento' => $this->tipoDocumento,
                    'numDocumento' => $this->numDocumento,
                    'apellidos' => $this->apellidos,
                    'nombres' => $this->nombres,
                    'idaOrigen' => $this->idaOrigen,
                    'idaDestino' => $this->idaDestino,
                    'vueltaOrigen' => $this->vueltaOrigen,
                    'vueltaDestino' => $this->vueltaDestino,
                    'fechaCompra' => $this->fechaCompra
                );
            }
            return true;
        }
        return false;
    }
}
