<?php

class venta
{
    private $conn;

    public $idVenta;
    public $idCliente;
    public $idUsuario;
    public $idViajeIda;
    public $idViajeVuelta;
    public $cantAdultos;
    public $cantNinos;
    public $cantBebes;
    public $passangersData = array();
    public $nombres;
    public $apellidos;
    public $tipoDocumento;
    public $numDocumento;
    public $idTipoPasaje;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function registerVenta()
    {

        // query para insertar un nuevo usuario con tipo especificado
        $query = "call registerVenta(:idUsuario, :idViajeIda, :idViajeVuelta, :cantAdultos, :cantNinos, :cantBebes)";
        // $query = "INSERT INTO " . $this->table_name . " (firstname, lastname, username, password, tipo) 
        //             VALUES (:firstname, :lastname, :username, :password, :tipo)";

        // preaparando el query
        $stmt = $this->conn->prepare($query);

        // limpieza
        // htmlspecialchars --> ELiminar todo rastro html
        // strip_tags --> Su funcionalidad es la de eliminar o limpiar las etiquetas HTML y PHP de una cadena string
        $this->idUsuario = htmlspecialchars(strip_tags($this->idUsuario));
        $this->idViajeIda = htmlspecialchars(strip_tags($this->idViajeIda));
        $this->idViajeVuelta = htmlspecialchars(strip_tags($this->idViajeVuelta));
        $this->cantAdultos = htmlspecialchars(strip_tags($this->cantAdultos));
        $this->cantNinos = htmlspecialchars(strip_tags($this->cantNinos));
        $this->cantBebes = htmlspecialchars(strip_tags($this->cantBebes));

        // bindParam reemplaza las variables de el query por las variables de php
        $stmt->bindParam(':idUsuario', $this->idUsuario);
        $stmt->bindParam(':idViajeIda', $this->idViajeIda);
        $stmt->bindParam(':idViajeVuelta', $this->idViajeVuelta);
        $stmt->bindParam(':cantAdultos', $this->cantAdultos);
        $stmt->bindParam(':cantNinos', $this->cantNinos);
        $stmt->bindParam(':cantBebes', $this->cantBebes);

        // ejecutando el query y condicionarlo
        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->idVenta = $row['idVenta'];
            return true;
        } else {
            return false;
        }
    }

    function registerPasajes()
    {
        $query = "call registerPasajes(:idVenta, :nombres, :apellidos, :tipoDocumento, :numDocumento, :idTipoPasaje)";
        $stmt = $this->conn->prepare($query);

        $this->nombres = htmlspecialchars(strip_tags($this->nombres));
        $this->apellidos = htmlspecialchars(strip_tags($this->apellidos));
        $this->tipoDocumento = htmlspecialchars(strip_tags($this->tipoDocumento));
        $this->numDocumento = htmlspecialchars(strip_tags($this->numDocumento));
        $this->idTipoPasaje = htmlspecialchars(strip_tags($this->idTipoPasaje));
        
        $array = [];
        $array["idVenta"] = $this->idVenta;
        $array["nombres"] =  $this->nombres;
        $array["apellidos"] =  $this->apellidos;
        $array["tipoDocumento"] =  $this->tipoDocumento;
        $array["numDocumento"] =  $this->numDocumento;
        $array["idTipoPasaje"] =  $this->idTipoPasaje;
        echo json_encode($array);
        $stmt->bindParam(':idVenta', $this->idVenta);
        $stmt->bindParam(':nombres', $this->nombres);
        $stmt->bindParam(':apellidos', $this->apellidos);
        $stmt->bindParam(':tipoDocumento', $this->tipoDocumento);
        $stmt->bindParam(':numDocumento', $this->numDocumento);
        $stmt->bindParam(':idTipoPasaje', $this->idTipoPasaje);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
