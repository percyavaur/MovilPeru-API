<?php

class news
{
    private $conn;

    public $idNews;
    public $titulo;
    public $subtitulo;
    public $contenido;
    public $imagen;
    public $expoToken;
    public $idUsuario;
    public $createDate;
    public $newsAll = array();
    public $tokens = array();

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function createNews()
    {

        $query = "INSERT INTO news (titulo, subtitulo, contenido, imagen) VALUES (:titulo, :subtitulo, :contenido, :imagen)";
        $stmt = $this->conn->prepare($query);

        $this->titulo = htmlspecialchars(strip_tags($this->titulo));
        $this->subtitulo = htmlspecialchars(strip_tags($this->subtitulo));
        $this->contenido = htmlspecialchars(strip_tags($this->contenido));
        $this->imagen = htmlspecialchars(strip_tags($this->imagen));

        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':subtitulo', $this->subtitulo);
        $stmt->bindParam(':contenido', $this->contenido);
        $stmt->bindParam(':imagen', $this->imagen);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function getNews()
    {
        $query = "SELECT * FROM news";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            $num = $stmt->rowCount();
            for ($i = 0; $i < $num; $i++) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->idNews = $row['idNews'];
                $this->titulo = $row['titulo'];
                $this->subtitulo = $row['subtitulo'];
                $this->contenido = $row['contenido'];
                $this->imagen = $row['imagen'];
                $this->createDate = $row['createDate'];
                $this->news[$i] = array(
                    'idNews' => $this->idNews,
                    'titulo' => $this->titulo,
                    'subtitulo' => $this->subtitulo,
                    'contenido' => $this->contenido,
                    'imagen' => $this->imagen,
                    'createDate' => $this->createDate
                );
            }
            return true;
        } else {
            return false;
        }
    }

    function getExpoTokens()
    {
        $query = "SELECT idUsuario, expoToken FROM usuarios";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            $num = $stmt->rowCount();
            for ($i = 0; $i < $num; $i++) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->idUsuario = $row['idUsuario'];
                $this->expoToken = $row['expoToken'];

                $this->tokens[$i] = array(
                    'idUsuario' => $this->idUsuario,
                    'expoToken' => $this->expoToken
                );
            }
            return true;
        } else {
            return false;
        }
    }
}
