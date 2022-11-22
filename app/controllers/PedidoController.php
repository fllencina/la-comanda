<?php
require_once './models/Pedido.php';


class PedidoController extends Pedido
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        // Creamos el usuario
        $ped = new Pedido();
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $ped->id =substr(str_shuffle($permitted_chars), 0, 5);  
        $ped->idmesa = $parametros['idmesa'];
        $ped->idusuario = $parametros['idusuario'];
        $ped->fechamodificado = $parametros['fechamodificado'];
        $ped->tiempoestimado = $parametros['tiempoestimado'];
        date_default_timezone_set("America/Buenos_Aires");
        $ped->fechaalta = date("Y-m-d");
        //inicialmente queda como pendiente
        //Estados pedido
        // 1 pendiente
        // 2 en preparacion
        // 3 listo para servir
        $ped->idestado = 1;
       
        $ped->crearPedido();

        $items=$parametros['items'];
        $arrayItems=explode(";", $items);
       
        //inserto items de pedido
        foreach($arrayItems as $item)
        {
          $arrayItemsCantidad=explode("=", $item);
          $prod = Producto::obtenerProducto($arrayItemsCantidad[0]);
          $itemCrear = new ItemsPedido();
          $itemCrear->idproducto=$prod->id;
          $itemCrear->idpedido=$ped->id;
          $itemCrear->cantidad=$arrayItemsCantidad[1];
          $itemCrear->idEstado=1;
          $itemCrear->crearItemPedido();
        }
        

        $payload = json_encode(array("mensaje" => "Pedido creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {

        $id = $args['id'];
        $Pedido = Pedido::obtenerPedido($id);
        $payload = json_encode($Pedido);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Pedido::obtenerTodos();
        $payload = json_encode(array("listaPedido" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];
        $Pedido=Pedido::obtenerPedido($id);
        $Pedido->idestado=$parametros['idestado'];
        Pedido::modificarPedido($id);

        $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function ModificarItemPedido($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $idpedido = $parametros['idpedido'];
        $idproducto = $parametros['idproducto'];

        $Pedido=ItemsPedido::obtenerItemPedido($idproducto,$idpedido);
        $Pedido->idestado=$parametros['idestado'];

        $Pedido->ModificarItemPedido();

        $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

   
}
