<?php

class news
{
    public $nombre;
    public $correo;
    public $telefono;
    public $ticket;
    public $destinatario;
    public $asunto;
    public $carta;

    public function __construct()
    {
        $this->asunto = "Movil Peru - Su reserva ha sido exitosa";
        $this->nombre = "Movil Peru";
        $this->correo = "movilperu.info@gmail.com";
        $this->telefono = "956727151";
    }

    function send_mail()
    {
        $this->carta = "De: $this->nombre \n";
        $this->carta .= "Correo: $this->correo \n";
        $this->carta .= "Telefono: $this->telefono \n";
        $this->carta .= "Mensaje: Su Reserva realizada con número de ticket $this->ticket, ha sido exitosa.  \n";

        mail($this->destinatario,$this->asunto,$this->carta);
    }
}
