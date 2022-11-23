<?php

class Productos
{
    public $id;
    public $descripcion;
    public $idsector;
    public $precio;
    

    public function crearProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos (descripcion, idsector,precio) VALUES (:descripcion, :idsector,:precio)");
        
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        
        $consulta->bindValue(':idsector', $this->idsector, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Productos');
    }

    public static function obtenerProducto($descripcion)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos WHERE descripcion = :descripcion");
        $consulta->bindValue(':descripcion', $descripcion, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Productos');
    }

    public  function modificarProducto()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE productos SET descripcion = :descripcion, idsector = :idsector ,precio=:precio  WHERE id = :id");
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':idsector', $this->idsector, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
    
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();
    }

   
}