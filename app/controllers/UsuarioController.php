<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class UsuarioController extends Usuario implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $UsuarioExistente=Usuario::obtenerUsuario($parametros['usuario']);

     
        if(!isset($UsuarioExistente) || !$UsuarioExistente  )
        {
            $usr = new Usuario();
            $usr->usuario = $parametros['usuario'];
            $usr->clave = $parametros['clave'];
            $usr->mail = $parametros['mail'];
            $usr->idpersona = $parametros['idpersona'];
            $usr->idrol = $parametros['idrol'];
            $usr->idsector = $parametros['idsector'];
            date_default_timezone_set("America/Buenos_Aires");
            $usr->fechaalta = date("Y-m-d H:i:s");
            $usr->estado = 'Activo'; 
            $usr->crearUsuario();
            $payload = json_encode(array("mensaje" => "Usuario creado con exito"));   
      }
      else{
        $payload = json_encode(array("mensaje" => "El usuario ya existe."));  
      }
      $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $usr = $args['usuario'];
        $usuario = Usuario::obtenerUsuario($usr);
        $payload = json_encode($usuario);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Usuario::obtenerTodos();
        $payload = json_encode(array("listaUsuario" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['usuario'];
        $clave = $parametros['clave'];
        $usuario=Usuario::obtenerUsuario( $id);
        var_dump($usuario);
        $usuario->clave= password_hash( $clave , PASSWORD_DEFAULT);
        $usuario->modificarUsuario();

        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usuarioId = $parametros['usuarioId'];
        Usuario::borrarUsuario($usuarioId);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
