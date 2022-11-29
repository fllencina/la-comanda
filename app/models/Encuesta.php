<?php

class Encuesta
{
    public $id;
    public $puntosmesa;
    public $puntosrestaurant;
    public $puntosmozo;
    public $puntoscocinero;
    public $comentarios;
    public $idpersona;
    public $fecha;
    public $idmesa;
    public $idpedido;
    

    public function crearEncuesta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO Encuesta (puntosmesa, puntosrestaurant,puntosmozo,puntoscocinero,comentarios,idpersona,fecha,idmesa,idpedido) VALUES (:puntosmesa, :puntosrestaurant,:puntosmozo,:puntoscocinero,:comentarios,:idpersona,:fecha,:idmesa,:idpedido)");
        
        $consulta->bindValue(':puntosmesa', $this->puntosmesa, PDO::PARAM_STR);
        $consulta->bindValue(':puntosrestaurant', $this->puntosrestaurant, PDO::PARAM_STR);
        $consulta->bindValue(':puntosmozo', $this->puntosmozo, PDO::PARAM_INT);
        $consulta->bindValue(':puntoscocinero', $this->puntoscocinero, PDO::PARAM_INT);
        $consulta->bindValue(':comentarios', $this->comentarios, PDO::PARAM_STR);
        $consulta->bindValue(':idpersona', $this->idpersona, PDO::PARAM_INT);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->bindValue(':idmesa', $this->idmesa, PDO::PARAM_INT);
        $consulta->bindValue(':idpedido', $this->idpedido, PDO::PARAM_INT);


        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Encuesta");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }

    public static function obtenerEncuesta($idmesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Encuesta WHERE idmesa = :idmesa");
        $consulta->bindValue(':idmesa', $idmesa, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Encuesta');
    }

   

   
}