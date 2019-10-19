<?php
class user
{
    private $conn;

    //constantes del objeto usuario
    public $username;
    public $password;
    public $created;
    public $modified;
    public $idUsuario;
    public $idEstado;
    public $estado;
    public $idRol;
    public $rol;
    public $nombres;
    public $apellidos;
    public $genero;
    public $fecNac;
    public $tipoDocumento;
    public $numDocumento;
    public $correoElectronico;
    public $telefono;
    public $imagen;
    public $estadoCivil;
    public $direccion;
    public $expoToken;
    public $users = array();


    public function __construct($db)
    {
        $this->conn = $db;
    }

    function create()
    {
        // query para insertar un nuevo usuario con tipo especificado
        $query = "call registerUser(:idEstado, :idRol, :username, :password, :nombres, :apellidos, :genero, :fecNac, :tipoDocumento, :numDocumento, :correoElectronico, :telefono)";
        // $query = "INSERT INTO " . $this->table_name . " (firstname, lastname, username, password, tipo) 
        //             VALUES (:firstname, :lastname, :username, :password, :tipo)";

        // preaparando el query
        $stmt = $this->conn->prepare($query);

        // limpieza
        // htmlspecialchars --> ELiminar todo rastro html
        // strip_tags --> Su funcionalidad es la de eliminar o limpiar las etiquetas HTML y PHP de una cadena string
        $this->idEstado = htmlspecialchars(strip_tags($this->idEstado));
        $this->idRol = htmlspecialchars(strip_tags($this->idRol));
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->nombres = htmlspecialchars(strip_tags($this->nombres));
        $this->apellidos = htmlspecialchars(strip_tags($this->apellidos));
        $this->genero = htmlspecialchars(strip_tags($this->genero));
        $this->fecNac = htmlspecialchars(strip_tags($this->fecNac));
        $this->tipoDocumento = htmlspecialchars(strip_tags($this->tipoDocumento));
        $this->numDocumento = htmlspecialchars(strip_tags($this->numDocumento));
        $this->correoElectronico = htmlspecialchars(strip_tags($this->correoElectronico));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));

        // bindParam reemplaza las variables de el query por las variables de php
        $stmt->bindParam(':idEstado', $this->idEstado);
        $stmt->bindParam(':idRol', $this->idRol);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':nombres', $this->nombres);
        $stmt->bindParam(':apellidos', $this->apellidos);
        $stmt->bindParam(':genero', $this->genero);
        $stmt->bindParam(':fecNac', $this->fecNac);
        $stmt->bindParam(':tipoDocumento', $this->tipoDocumento);
        $stmt->bindParam(':numDocumento', $this->numDocumento);
        $stmt->bindParam(':correoElectronico', $this->correoElectronico);
        $stmt->bindParam(':telefono', $this->telefono);

        // Partir y encriptar la constraseña antes de guardar en la base de datos
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);

        // ejecutando el query y condicionarlo
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function usernameExists()
    {

        $idUsuario_set = !empty($this->idUsuario) ? " && idUsuario != ? " : "";

        $query = "SELECT idUsuario, username,password,idRol, rol, tipoDocumento, numDocumento, apellidos, nombres, fecNac, genero, correoElectronico, direccion, telefono, estadoCivil, imagen, idEstado, estado
         FROM usuariosInfo WHERE username = ? {$idUsuario_set} LIMIT 0,1";
        // $query = "SELECT id, firstname, lastname, tipo, password FROM " . $this->table_name .
        //     " WHERE username = ? {$id_set} LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $this->username = htmlspecialchars(strip_tags($this->username));
        $stmt->bindParam(1, $this->username);

        if (!empty($this->idUsuario)) {
            $stmt->bindParam(2, $this->idUsuario);
        }

        $stmt->execute();
        // obteniendo la cantidad de filas
        $num = $stmt->rowCount();

        // si el username existe almacena los datos en las variables del objeto
        if ($num > 0) {

            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // assign values to object properties
            $this->idUsuario = $row['idUsuario'];
            $this->username = $row['username'];
            $this->password = $row['password'];
            $this->idRol = $row['idRol'];
            $this->rol = $row['rol'];
            $this->tipoDocumento = $row['tipoDocumento'];
            $this->numDocumento = $row['numDocumento'];
            $this->apellidos = $row['apellidos'];
            $this->nombres = $row['nombres'];
            $this->fecNac = $row['fecNac'];
            $this->genero = $row['genero'];
            $this->correoElectronico = $row['correoElectronico'];
            $this->direccion = $row['direccion'];
            $this->telefono = $row['telefono'];
            $this->estadoCivil = $row['estadoCivil'];
            $this->imagen = $row['imagen'];
            $this->idEstado = $row['idEstado'];
            $this->estado = $row['estado'];

            // return true because username exists in the database
            return true;
        }
        // return false if username does not exist in the database
        return false;
    }

    public function update()
    {
        // if no posted password, do not update the password
        $query = "call updateUser(:idUsuario, :idEstado, :idRol, :username, :password, :nombres, :apellidos, :genero, :fecNac, :tipoDocumento, :numDocumento, :correoElectronico, :direccion, :telefono, :imagen, :estadoCivil)";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->idUsuario = htmlspecialchars(strip_tags($this->idUsuario));
        $this->idEstado = htmlspecialchars(strip_tags($this->idEstado));
        $this->idRol = htmlspecialchars(strip_tags($this->idRol));
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->nombres = htmlspecialchars(strip_tags($this->nombres));
        $this->apellidos = htmlspecialchars(strip_tags($this->apellidos));
        $this->genero = htmlspecialchars(strip_tags($this->genero));
        $this->fecNac = htmlspecialchars(strip_tags($this->fecNac));
        $this->estadoCivil = htmlspecialchars(strip_tags($this->estadoCivil));
        $this->tipoDocumento = htmlspecialchars(strip_tags($this->tipoDocumento));
        $this->numDocumento = htmlspecialchars(strip_tags($this->numDocumento));
        $this->correoElectronico = htmlspecialchars(strip_tags($this->correoElectronico));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->imagen = htmlspecialchars(strip_tags($this->imagen));

        // bind the values from the form
        $stmt->bindParam(':idUsuario', $this->idUsuario);
        $stmt->bindParam(':idEstado', $this->idEstado);
        $stmt->bindParam(':idRol', $this->idRol);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':nombres', $this->nombres);
        $stmt->bindParam(':apellidos', $this->apellidos);
        $stmt->bindParam(':genero', $this->genero);
        $stmt->bindParam(':fecNac', $this->fecNac);
        $stmt->bindParam(':estadoCivil', $this->estadoCivil);
        $stmt->bindParam(':tipoDocumento', $this->tipoDocumento);
        $stmt->bindParam(':numDocumento', $this->numDocumento);
        $stmt->bindParam(':correoElectronico', $this->correoElectronico);
        $stmt->bindParam(':direccion', $this->direccion);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':imagen', $this->imagen);

        // hash the password before saving to database
        if (!empty($this->password)) {
            $this->password = htmlspecialchars(strip_tags($this->password));
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password_hash);
        } else {
            $stmt->bindParam(':password', $this->password);
        }


        // execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function getUsers()
    {
        $query = "SELECT idUsuario, username, idRol, rol, tipoDocumento, numDocumento, apellidos, nombres, fecNac, genero, correoElectronico, direccion, telefono, estadoCivil, imagen, idEstado, estado
                FROM usuariosInfo";

        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {

            $num = $stmt->rowCount();

            for ($i = 0; $i < $num; $i++) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                // assign values to object properties
                $this->idUsuario = $row['idUsuario'];
                $this->username = $row['username'];
                $this->idRol = $row['idRol'];
                $this->rol = $row['rol'];
                $this->tipoDocumento = $row['tipoDocumento'];
                $this->numDocumento = $row['numDocumento'];
                $this->apellidos = $row['apellidos'];
                $this->nombres = $row['nombres'];
                $this->fecNac = $row['fecNac'];
                $this->genero = $row['genero'];
                $this->correoElectronico = $row['correoElectronico'];
                $this->direccion = $row['direccion'];
                $this->telefono = $row['telefono'];
                $this->estadoCivil = $row['estadoCivil'];
                $this->imagen = $row['imagen'];
                $this->idEstado = $row['idEstado'];
                $this->estado = $row['estado'];

                $this->users[$i] = array(
                    'idUsuario' => $this->idUsuario,
                    'username' => $this->username,
                    'idRol' => $this->idRol,
                    'rol' => $this->rol,
                    'tipoDocumento' => $this->tipoDocumento,
                    'numDocumento' => $this->numDocumento,
                    'apellidos' => $this->apellidos,
                    'nombres' => $this->nombres,
                    'fecNac' => $this->fecNac,
                    'correoElectronico' => $this->correoElectronico,
                    'direccion' => $this->direccion,
                    'telefono' => $this->telefono,
                    'estadoCivil' => $this->estadoCivil,
                    'imagen' => $this->imagen,
                    'idEstado' => $this->idEstado,
                    'estado' => $this->estado
                );
            }
            return true;
        }
        return false;
    }

    function updateToken()
    {
        //$query = "UPDATE usuarios SET expoToken = ? WHERE idUsuario = ?";
        $query = "INSERT INTO  expotokens ('idUsuario','expoToken') VALUES(?,?)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->expoToken);
        $stmt->bindParam(2, $this->idUsuario);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
