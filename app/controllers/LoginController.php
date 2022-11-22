<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';
require_once './models/Usuario.php';
require_once './models/AutentificadorJWT.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class LoginController extends Usuario{

    public static function VerificarUsuario($request, $response, $args)
    {
        $datos=$request->getParsedBody();
        $UsuarioLogin=Usuario::obtenerUsuario($datos['usuario']);
        $data['usuario']=$datos['usuario'];
        if(password_verify($datos['clave'], $UsuarioLogin->clave )){

           if($UsuarioLogin->idrol==$datos['rol'])
            {   
                $data['rol']=$UsuarioLogin->idrol;
                $token = AutentificadorJWT::CrearToken($data);
                date_default_timezone_set("America/Buenos_Aires");
                $UsuarioLogin->fechaultimologin=date("Y-m-d");
                $UsuarioLogin->modificarUsuario();
                $payload = json_encode(array('jwt' => $token));
            }
            else{
            $payload = json_encode(array("mensaje" => "No coincide el rol"));

            }
        }
        else{
            $payload = json_encode(array("mensaje" => "No coincide la clave"));

        }
        $response->getBody()->write($payload);
        return $response ->withHeader('Content-Type', 'application/json');
    }


}
?>