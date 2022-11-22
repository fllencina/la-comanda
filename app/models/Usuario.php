<?php

class Usuario
{
    public $id;
    public $usuario;
    public $clave;
    public $mail;
    public $fechaultimologin;
    public $idpersona;
    public $idrol;
    public $idsector;
    public $fechaalta;
    public $fechabaja;
    public $estado;

    public function crearUsuario()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuario (usuario, clave,mail,idpersona,idrol,fechaalta,estado,idsector) VALUES (:usuario, :clave,:mail,:idpersona,:idrol,:fechaalta,:estado,:idsector)");
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->bindValue(':mail', $this->mail, PDO::PARAM_STR);
        $consulta->bindValue(':idpersona', $this->idpersona, PDO::PARAM_INT);
        $consulta->bindValue(':idrol', $this->idrol, PDO::PARAM_INT);
        $consulta->bindValue(':idsector', $this->idsector, PDO::PARAM_INT);
        $consulta->bindValue(':fechaalta', $this->fechaalta, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
//var_dump($consulta);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuario");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUsuario($usuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuario WHERE usuario = :usuario");
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }

    public  function modificarUsuario()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuario SET usuario = :usuario, clave = :clave ,mail=:mail , idpersona= :idpersona, idrol= :idrol, fechaultimologin= :fechaultimologin , estado= :estado WHERE id = :id");
        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(':mail', $this->mail, PDO::PARAM_STR);
        $consulta->bindValue(':idpersona', $this->idpersona, PDO::PARAM_INT);
        $consulta->bindValue(':idrol', $this->idrol, PDO::PARAM_INT);
        $consulta->bindValue(':fechaultimologin', $this->fechaultimologin, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);

        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarUsuario($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuario SET fechaBaja = :fechaBaja , estado=:estado  WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $usuario, PDO::PARAM_INT);
        $consulta->bindValue(':fechabaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->bindValue(':estado', 'Eliminado', PDO::PARAM_STR);

        $consulta->execute();
    }
}