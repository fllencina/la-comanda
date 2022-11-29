<?php

class Actividad
{
    public $id;
    public $userid;
    public $fecha;
    public $accion;
    public $observaciones;

   
    public function crear()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO actividad (id,userid, fecha,accion,observaciones) VALUES (:id,:userid, :fecha,:accion,:observaciones)");
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);   
     // echo "fecha recibida: ". $this->fecha;
        $consulta->bindValue(':userid', $this->userid, PDO::PARAM_INT);   
        $consulta->bindValue(':accion', $this->accion, PDO::PARAM_INT);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->bindValue(':observaciones', $this->observaciones, PDO::PARAM_STR);

       
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM actividad");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'actividad');
    }

    public static function obtenerPersona($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM actividad WHERE useridid = :useridid");
        $consulta->bindValue(':userid', $id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('actividad');
    }
    public static function EliminarTodosLosDatos(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("DELETE from actividad");
        $consulta->execute();
    }
}
?>
