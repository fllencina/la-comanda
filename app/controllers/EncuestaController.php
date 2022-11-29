<?php
require_once './models/Encuesta.php';


class EncuestaController extends Encuesta 
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $pedido=Pedido::obtenerPedido($parametros['idpedido']);
        $mesa=Mesa::obtenerMesa($parametros['idmesa']);
      if(isset($pedido,$mesa) && $pedido  && $mesa)
        {
          $enc = new Encuesta();
        $enc->puntosmesa = $parametros['puntosmesa'];
        $enc->puntoscocinero = $parametros['puntoscocinero'];
        $enc->puntosmozo = $parametros['puntosmozo'];
        $enc->puntosrestaurant = $parametros['puntosrestaurant'];
        $enc->comentarios = $parametros['comentarios'];
        $enc->idpersona = $parametros['idpersona'];
        date_default_timezone_set("America/Buenos_Aires");
        $enc->fecha = date("Y-m-d H:i:s");
        $enc->idmesa = $parametros['idmesa'];
        $enc->idpedido = $parametros['idpedido'];        

        $enc->crearEncuesta();

       
            $actividad=new Actividad();
            $actividad->fecha=date("Y-m-d H:i:s");
            $actividad->userid=0;
            $actividad->accion=11;
            $actividad->observaciones="cliente idpersona: ".$parametros['idpersona'];
            $actividad->crear();
       
        $payload = json_encode(array("mensaje" => "Encuesta creada con exito"));
        }
        else{
          $payload = json_encode(array("mensaje" => "No existe la mesa o el pedido, no es posible realizar la encuesta"));
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        
        $enc = $args['idmesa'];
        $encuesta = Encuesta::obtenerEncuesta($enc);
        $payload = json_encode($encuesta);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Encuesta::obtenerTodos();
        $payload = json_encode(array("listaEncuesta" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
   
   
}
