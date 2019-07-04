<?php

class frenosa
{
    private $conn;

    public $CodFPa;
    public $Cod_Cli;
    public $Cod_Emp;
    public $Cod_Pro;
    public $CodDis;
    public $CodTCo;
    public $FEmCPC;
    public $MToCPC;
    public $CanVen;
    public $clientes = array();
    public $empleados = array();
    public $distritos = array();
    public $producto = array();

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function getCli()
    {
        $query = "SELECT Cod_Cli FROM cliente";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            $num = $stmt->rowCount();
            for ($i = 0; $i < $num; $i++) {

                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $this->Cod_Cli = $row['Cod_Cli'];
                array_push($this->clientes,$this->Cod_Cli);
            }
            return true;
        } else {
            return false;
        }
    }

    function getEmp()
    {
        $query = "SELECT Cod_Emp FROM empleado";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            $num = $stmt->rowCount();
            for ($i = 0; $i < $num; $i++) {

                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $this->Cod_Emp = $row['Cod_Emp'];
                array_push($this->empleados,$this->Cod_Emp);
            }
            return true;
        } else {
            return false;
        }
    }

    function getDis()
    {
        $query = "SELECT CodDis FROM distrito";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            $num = $stmt->rowCount();
            for ($i = 0; $i < $num; $i++) {

                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $this->CodDis = $row['CodDis'];
                array_push($this->distritos,$this->CodDis);
            }
            return true;
        } else {
            return false;
        }
    }

    function getProd()
    {
        $query = "SELECT Cod_Pro FROM productos";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            $num = $stmt->rowCount();
            for ($i = 0; $i < $num; $i++) {

                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $this->Cod_Pro = $row['Cod_Pro'];
                array_push($this->producto,$this->Cod_Pro);
            }
            return true;
        } else {
            return false;
        }
    }

    function fecha_aleatoria($formato = "Y-m-d", $limiteInferior = "2013-01-01", $limiteSuperior = "2019-01-01")
    {
        // Convertimos la fecha como cadena a milisegundos
        $milisegundosLimiteInferior = strtotime($limiteInferior);
        $milisegundosLimiteSuperior = strtotime($limiteSuperior);

        // Buscamos un número aleatorio entre esas dos fechas
        $milisegundosAleatorios = mt_rand($milisegundosLimiteInferior, $milisegundosLimiteSuperior);

        // Regresamos la fecha con el formato especificado y los milisegundos aleatorios
        return date($formato, $milisegundosAleatorios);
    }

    function create()
    {
        $query = "call createVenta(:Cod_Cli, :Cod_Emp, :CodDis, :Cod_Pro, :CanVen , :FEmCPC)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':Cod_Cli', $this->Cod_Cli);
        $stmt->bindParam(':Cod_Emp', $this->Cod_Emp);
        $stmt->bindParam(':CodDis', $this->CodDis);
        $stmt->bindParam(':Cod_Pro', $this->Cod_Pro);
        $stmt->bindParam(':CanVen', $this->CanVen);
        $stmt->bindParam(':FEmCPC', $this->FEmCPC);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
