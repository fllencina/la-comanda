<?php

class Mesa
{
    public $id;
    public $idestadomesa;
    public $descripcion;
    
    

    public function crearMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO Mesa (id,descripcion, idestadomesa) VALUES (:id,:descripcion, :idestadomesa)");    
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);  
        $consulta->bindValue(':idestadomesa', $this->idestadomesa, PDO::PARAM_STR);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_STR);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Mesa");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function obtenerMesa($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Mesa WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }

    public  function modificarMesa()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE Mesa SET  idestadomesa = :idestadomesa   WHERE id = :id");
      
        $consulta->bindValue(':idestadomesa', $this->idestadomesa, PDO::PARAM_STR);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_STR);
        $consulta->execute();
    }

   
}