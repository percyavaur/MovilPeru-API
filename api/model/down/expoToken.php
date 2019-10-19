<?php

class expoToken{
    private $conn;

    public $idExpoToken;
    public $idUsuario;
    public $expoToken;
    public $expoTokens = array();

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function getExpoTokens(){
        $query = "SELECT * FROM expotokens";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            $num = $stmt->rowCount();

            for ($i = 0; $i < $num; $i++) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $this->idExpoToken = $row['idExpoToken'];
                $this->idUsuario = $row['idUsuario'];
                $this->expoToken = $row['expoToken'];

                $this->expoTokens[$i] = array(
                    'idExpoToken' => $this->idExpoToken,
                    'idUsuario' => $this->idUsuario,
                    'expoToken' => $this->expoToken
                );
            }
            return true;
            }
        return false;
    }
}
