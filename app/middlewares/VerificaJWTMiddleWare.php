<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class VerificaJWTMiddleWare
{
    public  function __invoke($request, RequestHandler $handler):Response
    {

    $header = $request->getHeaderLine('Authorization');
    $response=new Response();
    if(isset($header)&& $header)
    {
      $token = trim(explode("Bearer", $header)[1]);
      $esValido = false;
      
      try {
      
      AutentificadorJWT::verificarToken($token);
      $esValido = true;
      $response=$handler->handle($request);
    } catch (Exception $e) {
      $payload = json_encode(array('error' => $e->getMessage()));
    }

    if ($esValido) {
      $payload = json_encode(array('JWT valido' => $esValido));
    }
  }
  else{
    $payload = json_encode(array('JWT' => "El token llega vacio"));
  }
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
}
}
?>
