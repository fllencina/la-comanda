<?php
require_once './models/Encuesta.php';


class EncuestaController extends Encuesta 
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $enc = new Encuesta();
        $enc->puntosmesa = $parametros['puntosmesa'];
        $enc->puntoscocinero = $parametros['puntoscocinero'];
        $enc->puntosmozo = $parametros['puntosmozo'];
        $enc->puntosrestaurant = $parametros['puntosrestaurant'];
        $enc->comentarios = $parametros['comentarios'];
        $enc->idpersona = $parametros['idpersona'];
        $enc->fecha = $parametros['fecha'];
        $enc->idmesa = $parametros['idmesa'];        
        $enc->crearEncuesta();

        $payload = json_encode(array("mensaje" => "Encuesta creada con exito"));

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
