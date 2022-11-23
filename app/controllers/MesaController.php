<?php
require_once './models/Mesa.php';


class MesaController extends Mesa 
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $Mesa = new Mesa();
        $Mesa->descripcion = $parametros['descripcion'];
        $Mesa->idestadomesa = 4;
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $Mesa->id =substr(str_shuffle($permitted_chars), 0, 5);  

        $Mesa->crearMesa();

        $payload = json_encode(array("mensaje" => "Mesa creada con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $mesa = $args['descripcion'];
        $Mesa = Mesa::obtenerMesa($mesa);
        $payload = json_encode($Mesa);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Mesa::obtenerTodos();
        $payload = json_encode(array("listaMesa" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];
        
        //estados mesa
        //1 con cliente esperando pedido
        //2 con cliente comiendo
        //3 con cliente pagando
        //4 cerrada
        $Mesa=Mesa::obtenerMesa($id);
        $Mesa->idestado=$parametros['idestado'];
        Mesa::modificarMesa($Mesa);

        $payload = json_encode(array("mensaje" => "Mesa modificada con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

   
}
