<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class EsSocioMiddleWare
{
    public  function __invoke($request, RequestHandler $handler):Response
    {
        $response=new Response();
        $header = $request->getHeaderLine('Authorization');
        
        if(isset($header) && $header)
        {
            $token = trim(explode("Bearer", $header)[1]);
        
         try {
     
            $payload = json_encode(array('data' => AutentificadorJWT::ObtenerData($token)));
        
            $data=AutentificadorJWT::ObtenerData($token);
       
             if ($data->rol == "10") {
             $response=$handler->handle($request);
             $payload = json_encode(array('ok' => "Es Socio"));

             }
        
            else{
                $payload = json_encode(array('error' => "No es socio"));
            }
        } catch (Exception $e) {
            $payload = json_encode(array('error' => $e->getMessage()));
        }
    }
    else{
        $payload = json_encode(array('error' => "Token vacio"));
    }
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
    }
    
}
?>