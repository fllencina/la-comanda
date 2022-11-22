<?php
require_once './models/Persona.php';


class PersonaController extends Persona 
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usr = new Persona();
        $usr->nombre = $parametros['nombre'];
        $usr->apellido = $parametros['apellido'];
        $usr->mail = $parametros['mail'];
       
        date_default_timezone_set("America/Buenos_Aires");
        $usr->fechaalta = date("Y-m-d H:i:s");
         
        $usr->crearPersona();

        $payload = json_encode(array("mensaje" => "Persona creada con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $usr = $args['mail'];
        $usuario = Persona::obtenerPersona($usr);
        $payload = json_encode($usuario);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Persona::obtenerTodos();
        $payload = json_encode(array("listaPersonas" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    // public function ModificarUno($request, $response, $args)
    // {
    //     $parametros = $request->getParsedBody();

    //     $id = $parametros['id'];
    //     $persona=Persona::obtenerPersona($id);
    //     var_dump($persona);
    //     date_default_timezone_set("America/Buenos_Aires");
        
    //     $persona->fechaalta=date("Y-m-d H:i:s");
    //     $persona->modificarPersona();

    //     $payload = json_encode(array("mensaje" => "Persona modificada con exito"));

    //     $response->getBody()->write($payload);
    //     return $response
    //       ->withHeader('Content-Type', 'application/json');
    // }

    
}
