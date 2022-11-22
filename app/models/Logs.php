<?php

class Logs{
    public $idusuario;
    public $id;
    public $accion;
    public $fecha;

    public function crearLog()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO actividad (idusuario, accion,fecha) VALUES (:idusuario,:accion,:fecha)");
       
        $consulta->bindValue(':idusuario', $this->idusuario, PDO::PARAM_INT);
      
        $consulta->bindValue(':accion', $this->accion, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $this->fechaaccion, PDO::PARAM_STR);


        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }
}