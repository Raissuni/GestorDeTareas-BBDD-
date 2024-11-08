<?php
class Tarea {
    public $id;
    public $nombreTarea;
    public $descripcion;
    public $prioridad;
    public $fechaLimite;

    public function __construct($nombreTarea, $descripcion, $prioridad, $fechaLimite) {
        $this->id=uniqid();
        $this->nombreTarea = $nombreTarea;
        $this->descripcion = $descripcion;
        $this->prioridad = $prioridad;
        $this->fechaLimite = $fechaLimite;
    }
    function getId(){
        return $this->id;
    }
}
?>
