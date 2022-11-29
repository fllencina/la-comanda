<?php

class Pedido
{
    public $id;
    public $idmesa;
    public $idusuario;
    public $tiempoestimado;
    public $fechamodificado;
    public $fechaalta;
    public $idestado;
    public $foto;
    public $totalfacturado;
    

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO Pedido (id,idmesa, idusuario,tiempoestimado,fechamodificado,fechaalta,idestado,foto) VALUES (:id,:idmesa, :idusuario,:tiempoestimado,:fechamodificado,:fechaalta,:idestado,:foto)");
        $consulta->bindValue(':id', $this->id, PDO::PARAM_STR);  
        $consulta->bindValue(':idmesa', $this->idmesa, PDO::PARAM_INT);
        $consulta->bindValue(':idusuario', $this->idusuario, PDO::PARAM_INT);
        $consulta->bindValue(':tiempoestimado', $this->tiempoestimado, PDO::PARAM_STR);
        $consulta->bindValue(':fechamodificado', $this->fechamodificado, PDO::PARAM_INT); 
        $consulta->bindValue(':fechaalta', $this->fechaalta, PDO::PARAM_STR);
        $consulta->bindValue(':idestado', $this->idestado, PDO::PARAM_INT);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);


        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Pedido");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }
    public static function obtenerTodosSector($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $sql= "SELECT ped.id as id ,prod.descripcion,ip.cantidad,ped.idmesa,ped.fechaalta,s.nombre as sector,u.usuario as idusuario ,ep.descripcion as idestado , ped.tiempoestimado as tiempoestimado,ped.fechamodificado as fechamodificado 
        FROM `productos` as prod
        left join itemspedido as ip on ip.idproducto=prod.id
        left JOIN pedido as ped on ped.id=ip.idPedido
        left join sectores as s on s.id=prod.idsector
        left join usuario as u on u.id=ped.idusuario
        left join estadopedido as ep on ep.id=ip.idestado
        where prod.idsector=:idsector";

        $consulta = $objAccesoDatos->prepararConsulta($sql);
        $consulta->bindParam(':idsector', $id);
       
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }
    public static function obtenerTodosPendientesSector($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $sql= "SELECT ped.id as id ,prod.descripcion,ip.cantidad,ped.idmesa,ped.fechaalta,s.nombre as sector,u.usuario as idusuario ,ep.descripcion as idestado , ped.tiempoestimado as tiempoestimado,ped.fechamodificado as fechamodificado 
        FROM `productos` as prod
         join itemspedido as ip on ip.idproducto=prod.id and ip.idestado=1
         JOIN pedido as ped on ped.id=ip.idPedido 
         join sectores as s on s.id=prod.idsector
         join usuario as u on u.id=ped.idusuario
         join estadopedido as ep on ep.id=ip.idestado
        where prod.idsector=:idsector";

        $consulta = $objAccesoDatos->prepararConsulta($sql);
        $consulta->bindParam(':idsector', $id);
       
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }
    public static function obtenerTodosEstado($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $sql= "SELECT ped.id as id,mesa.id as idmesa,ped.idusuario,usuario.usuario as idusuario,estadopedido.descripcion as idestado, ped.fechaalta as fechaalta,ped.tiempoestimado as tiempoestimado,ped.fechamodificado as fechamodificado 
        from pedido as ped 
        inner join estadopedido on estadopedido.id=ped.idestado 
        inner join mesa on mesa.id=ped.idmesa 
        inner join usuario on usuario.id=ped.idusuario 
        where ped.idestado=:idestado";

        $consulta = $objAccesoDatos->prepararConsulta($sql);
        $consulta->bindParam(':idestado', $id);
       
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerTodosPorUsuario($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $sql= "SELECT ped.id as id,mesa.id as idmesa,ped.idusuario,usuario.usuario as idusuario,estadopedido.descripcion as idestado, ped.fechaalta as fechaalta,ped.tiempoestimado as tiempoestimado,ped.fechamodificado as fechamodificado 
        from pedido as ped 
        inner join estadopedido on estadopedido.id=ped.idestado 
        inner join mesa on mesa.id=ped.idmesa 
        inner join usuario on usuario.id=ped.idusuario 
        where ped.idusuario=:idusuario";

        $consulta = $objAccesoDatos->prepararConsulta($sql);
        $consulta->bindParam(':idusuario', $id);
       
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }
    
    
    public static function obtenerPedidoPorMesa($idmesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Pedido WHERE idmesa = :idmesa");
        $consulta->bindValue(':idmesa', $idmesa, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }
    public static function obtenerPedido($idpedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Pedido WHERE id = :id");
        $consulta->bindValue(':id', $idpedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public  function modificarPedido()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE Pedido SET idmesa = :idmesa, idusuario = :idusuario ,tiempoestimado=:tiempoestimado , fechamodificado= :fechamodificado, fechaalta= :fechaalta, idestado= :idestado , totalfacturado=:totalfacturado  WHERE id = :id");
        $consulta->bindValue(':id', $this->id, PDO::PARAM_STR);  
        $consulta->bindValue(':idmesa', $this->idmesa, PDO::PARAM_INT);
        $consulta->bindValue(':idusuario', $this->idusuario, PDO::PARAM_INT);
        $consulta->bindValue(':tiempoestimado', $this->tiempoestimado, PDO::PARAM_STR);
        $consulta->bindValue(':fechamodificado', $this->fechamodificado, PDO::PARAM_INT); 
        $consulta->bindValue(':fechaalta', $this->fechaalta, PDO::PARAM_STR);
        $consulta->bindValue(':idestado', $this->idestado, PDO::PARAM_INT);
        $consulta->bindValue(':totalfacturado', $this->totalfacturado, PDO::PARAM_STR);
        
        $consulta->execute();
    }
    public  function agregarfoto()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE Pedido SET foto=:foto
        WHERE idmesa = :idmesa and id=:id");
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->bindValue(':idmesa', $this->idmesa, PDO::PARAM_INT);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);

        $consulta->execute();
    }
    public static function obtenerMesaMasUsada()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT *,count(*) FROM `pedido` group by idmesa");
        
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }
    public  function obtenerPedidoCliente($idpedido,$idmesa)
    {
        
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Pedido WHERE id = :id and idmesa=:idmesa");
        $consulta->bindValue(':id', $idpedido, PDO::PARAM_STR);
        $consulta->bindValue(':idmesa', $idmesa, PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }
}