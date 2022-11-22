<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Symfony\Contracts\Service\Attribute\Required;

require_once './controllers/LogsController.php';
class InsertaLogMiddleWare
{
    public  function __invoke($request, RequestHandler $handler):Response
    {
        $response=new Response();
        
        $datos=$request->getParsedBody();
        
        $Log = new Logs();
        $header = $request->getHeaderLine('Authorization');
    
        if(isset($header) && $header)
        {
            $token = trim(explode("Bearer", $header)[1]);

        $data=AutentificadorJWT::ObtenerData($token);
        $UsuarioLogin=Usuario::obtenerUsuario($data->mail);
        $Log->idusuario=  $UsuarioLogin->id;
        $Log->accion=$datos["accion"];
        
        date_default_timezone_set("America/Buenos_Aires");
        $Log->fecha=date("Y-m-d");
        $Log->crearLog();

        $payload = json_encode(array("mensaje" => "Log Insertado."));
        $response=$handler->handle($request);
             
        
    
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
    }
}

    
}
?>