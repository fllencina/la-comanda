<?php

class Persona
{
    public $id;
    public $nombre;
    public $apellido;
    public $mail;
    public $fechaalta;
   

    public function crearPersona()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO persona (nombre, apellido,mail,fechaalta) VALUES (:nombre, :apellido,:mail,:fechaalta)");
      
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':mail', $this->mail, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':fechaalta', $this->fechaalta, PDO::PARAM_STR);
       

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM persona");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Persona');
    }

    public static function obtenerPersona($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM persona WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Persona');
    }

    // public  function modificarPersona()
    // {
    //     $objAccesoDato = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDato->prepararConsulta("UPDATE persona SET  nombre = :nombre ,mail=:mail , apellido= :apellido,fechaalta=:fechaalta WHERE id = :id");
    //     $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
    //     $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
    //     $consulta->bindValue(':mail', $this->mail, PDO::PARAM_STR);  
    //     //borrar
    //     $consulta->bindValue(':fechaalta', $this->fechaalta, PDO::PARAM_STR);  

    //     $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
    //     $consulta->execute();
    // }

   
}