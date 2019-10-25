<?php
class conductor
{
    private $conn;
    
    public $idConductor;
    public $idPersonalData;
    public $idUsuario;
    public $categoriaLicencia;
    public $numLicencia;
    public $nombres;
    public $apellidos;
    public $tipoDocumento;
    public $numDocumento;
    public $correoElectronico;
    public $direccion;
    public $telefono;
    public $fecNac;
    public $genero;
    public $estadoCivil;
    public $conductores = array();

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function getConductores()
    {
        $query = "SELECT `idConductor`, 
        `idPersonalData`, `idUsuario`, 
        `categoriaLicencia`, `numLicencia`, 
        `nombres`, `apellidos`, `tipoDocumento`, 
        `numDocumento`, `correoElectronico`, 
        `direccion`, `telefono`, `fecNac`,
        `genero`, `estadoCivil` 
        FROM `conductoresinfo`";

        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            $num = $stmt->rowCount();
            for ($i = 0; $i < $num; $i++) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $this->idConductor = $row['idConductor'];
                $this->idPersonalData = $row['idPersonalData'];
                $this->idUsuario = $row['idUsuario'];
                $this->categoriaLicencia = $row['categoriaLicencia'];
                $this->numLicencia = $row['numLicencia'];
                $this->nombres = $row['nombres'];
                $this->apellidos = $row['apellidos'];
                $this->tipoDocumento = $row['tipoDocumento'];
                $this->numDocumento = $row['numDocumento'];
                $this->correoElectronico = $row['correoElectronico'];
                $this->direccion = $row['direccion'];
                $this->telefono = $row['telefono'];
                $this->fecNac = $row['fecNac'];
                $this->genero = $row['genero'];
                $this->estadoCivil = $row['estadoCivil'];

                $this->vehiculos[$i] = array(
                    'idConductor' => $this->idConductor,
                    'idPersonalData' => $this->idPersonalData,
                    'idUsuario' => $this->idUsuario,
                    'categoriaLicencia' => $this->categoriaLicencia,
                    'numLicencia' => $this->numLicencia,
                    'nombres' => $this->nombres,
                    'apellidos' => $this->apellidos,
                    'tipoDocumento' => $this->tipoDocumento,
                    'numDocumento' => $this->numDocumento,
                    'correoElectronico' => $this->correoElectronico,
                    'direccion' => $this->direccion,
                    'telefono' => $this->telefono,
                    'fecNac' => $this->fecNac,
                    'genero' => $this->genero,
                    'estadoCivil' => $this->estadoCivil
                );
            }
            return true;
        }
        return false;
    }
}
