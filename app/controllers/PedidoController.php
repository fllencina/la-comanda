<?php
require_once './models/Pedido.php';
require_once './models/ItemsPedido.php';
require_once './ManejoArchivos.php';




class PedidoController extends Pedido
{
  

    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $ped = new Pedido();
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $ped->id =substr(str_shuffle($permitted_chars), 0, 5);  
        $ped->idmesa = $parametros['idmesa'];
        $ped->idusuario = $parametros['idusuario'];
        date_default_timezone_set("America/Buenos_Aires");
        $ped->fechaalta = date("Y-m-d H:i:s");
        $Nombre="Foto mesa_".$ped->idmesa.'_'.date("Y-m-d"); 
        GuardarFoto($_FILES["foto"],"Foto mesa_".$ped->idmesa."_".date("Y-m-d"),'./FotoMesa/');
        $nameImagen = $_FILES["foto"]["name"];
        $explode = explode(".", $nameImagen);
        $tamaño = count($explode);
        $dic = $Nombre;
        $dic .= ".";
        $dic .= $explode[$tamaño - 1];
        $ped->foto=$dic;
        
        $ped->idestado = 1;
       
        $ped->crearPedido();
        $header = $request->getHeaderLine('Authorization');
        
        if(isset($header) && $header)
        {
            $token = trim(explode("Bearer", $header)[1]);
        
        $data=AutentificadorJWT::ObtenerData($token);
       
        $UsuarioLogin=Usuario::obtenerUsuario($data->usuario);
        $actividad=new Actividad();
        $actividad->userid=$UsuarioLogin->id;
        $actividad->fecha=date("Y-m-d H:i:s");
        $actividad->accion=2;
        $actividad->observaciones=$ped->id;
        $actividad->crear();
        }
        $items=$parametros['items'];
        $arrayItems=explode(";", $items);
       
        //inserto items de pedido
        foreach($arrayItems as $item)
        {
          $arrayItemsCantidad=explode("=", $item);
          $prod = Productos::obtenerProducto($arrayItemsCantidad[0]);
          $itemCrear = new ItemsPedido();
          $itemCrear->idproducto=$prod->id;
          $itemCrear->idpedido=$ped->id;
          $itemCrear->cantidad=$arrayItemsCantidad[1];
          $itemCrear->idestado=1;
          $itemCrear->crearItemPedido();
        }

        $mesa=Mesa::obtenerMesa($parametros['idmesa']);
        $mesa->idestadomesa=1;
        $mesa->modificarMesa();

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

    public function TraerTodosPorSector($request, $response, $args)
    {
      $id = $args['idsector'];
        $lista = Pedido::obtenerTodosSector($id);
        $payload = json_encode(array("listaPedido" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function TraerTodosPendientesSector($request, $response, $args)
    {
      $id = $args['idsector'];
        $lista = Pedido::obtenerTodosPendientesSector($id);
        $payload = json_encode(array("listaPedido" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function TraerTodosEstado($request, $response, $args)
    {
      $id = $args['idestado'];
        $lista = Pedido::obtenerTodosEstado($id);
        $payload = json_encode(array("listaPedido" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function TraerTodosPorUsuario($request, $response, $args)
    {
      $id = $args['idusuario'];
        $lista = Pedido::obtenerTodosPorUsuario($id);
        $payload = json_encode(array("listaPedido" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
   public function agregarfotopedido($request, $response, $args)
   {
    $parametros = $request->getParsedBody();

    date_default_timezone_set("America/Buenos_Aires");
    $Nombre="Foto mesa_".$parametros['idmesa'].'_'.date("Y-m-d"); 
    GuardarFoto($_FILES["foto"],"Foto mesa_".$parametros['idmesa']."_".date("Y-m-d"),'./FotoMesa/');
    $nameImagen = $_FILES["foto"]["name"];
    $explode = explode(".", $nameImagen);
    $tamaño = count($explode);
    $dic = $Nombre;
    $dic .= ".";
    $dic .= $explode[$tamaño - 1];
    $ped= Pedido::obtenerPedido($parametros['idpedido']);
    $ped->foto=$dic;
    $ped->agregarfoto();
    $payload = json_encode(array("mensaje" => "Foto agregada al pedido"));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
   }

    public function ModificarEstadoUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];
        $Pedido=Pedido::obtenerPedido($id);
        $Pedido->idestado=$parametros['idestado'];
        
        Pedido::modificarPedido($id);
        $header = $request->getHeaderLine('Authorization');
        if(isset($header) && $header)
        {
            $token = trim(explode("Bearer", $header)[1]);
        
            $data=AutentificadorJWT::ObtenerData($token);
            $UsuarioLogin=Usuario::obtenerUsuario($data->usuario);
            $actividad=new Actividad();
            $actividad->userid=$UsuarioLogin->id;
            $actividad->fecha=date("Y-m-d H:i:s");
            $actividad->accion=4;
            $actividad->observaciones=$Pedido->id;
            $actividad->crear();
        }
        $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarEstadoItemPedido($request, $response, $args)
    {
      date_default_timezone_set("America/Buenos_Aires");
        $parametros = $request->getParsedBody();

        $idpedido = $parametros['idpedido'];
        $idproducto = $parametros['idproducto'];

        $ItemPedido=ItemsPedido::obtenerItemPedido($idproducto,$idpedido);
        $ItemPedido->idestado=$parametros['idestado'];
        var_dump($parametros['idestado']);
        if($parametros['idestado']==2)
        {
          //echo "entra en 2";
          $ItemPedido->fechainiciopreparacion=date("Y-m-d H:i:s");
        }
        if($parametros['idestado']==3)
        {
          $ItemPedido->fechalisto=date("Y-m-d H:i:s");
        }
        $ItemPedido->tiempoestimado=$parametros['tiempoestimado'];
        
        $ItemPedido->ModificarItemPedido();
        $header = $request->getHeaderLine('Authorization');
        if(isset($header) && $header)
        {
            $token = trim(explode("Bearer", $header)[1]);
        
            $data=AutentificadorJWT::ObtenerData($token);
            $UsuarioLogin=Usuario::obtenerUsuario($data->usuario);
            $actividad=new Actividad();
            $actividad->userid=$UsuarioLogin->id;
            $actividad->fecha=date("Y-m-d H:i:s");
            $actividad->accion=3;
            $actividad->observaciones=$idpedido;
            $actividad->crear();
        }
        self::VerificarEstadoPedido($idpedido,$header);
        

        $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function VerificarEstadoPedido($idpedido,$header)
    {
      //Revisar el estado de los items del pedido, si todos los items estan en listos para entregar modificar el pedido a listo para entregar
      //si todos los items del pedido tienen un tiempo estimado de entrega, agarrar el mas grande y setearlo en  el pedido como el total de tiempo estimado para entregar

      $array=ItemsPedido::obtenerTodosLosItemsPorIdPedido($idpedido);
      $Pedido=  Pedido::obtenerPedido($idpedido);
      date_default_timezone_set("America/Buenos_Aires");
      $max=0;
      $terminado=true;
      $totalimporte=0;
      foreach ($array as $item) {

        if($item->idestado!=3){
          $terminado=false;
        }
        if($max<$item->tiempoestimado)
        {
          $max=$item->tiempoestimado;
          $Pedido->fechamodificado=date("Y-m-d H:i:s");
          
        }
        
        //var_dump(Productos::obtenerProductoPorID($item->idproducto)->precio);
        $totalimporte+=$item->cantidad * (Productos::obtenerProductoPorID($item->idproducto)->precio);
        //echo "<br>" . $totalimporte;
        $Pedido->idestado=$item->idestado;
    }
    if($terminado==true)
    {   
      $Pedido->idestado=3;
      $Pedido->totalfacturado=$totalimporte;
      $mesa=Mesa::obtenerMesa($Pedido->idmesa);
      $mesa->idestadomesa=2;
      $mesa->modificarMesa();
      
        if(isset($header) && $header)
        {
            $token = trim(explode("Bearer", $header)[1]);
        
            $data=AutentificadorJWT::ObtenerData($token);
            $UsuarioLogin=Usuario::obtenerUsuario($data->usuario);
            $actividad=new Actividad();
            $actividad->userid=$UsuarioLogin->id;
            $actividad->fecha=date("Y-m-d H:i:s");
            $actividad->accion=4;
            $actividad->observaciones=$idpedido;
            $actividad->crear();
            $actividad=new Actividad();
            $actividad->userid=$UsuarioLogin->id;
            $actividad->fecha=date("Y-m-d H:i:s");
            $actividad->accion=10;
            $actividad->observaciones=$idpedido;
            $actividad->crear();
        }
    }
    $Pedido->tiempoestimado=$max;
    $Pedido->modificarPedido();
    
    
      
    }

   
}
