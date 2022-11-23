<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
require_once './middlewares/EsSocioMiddleWare.php';
require_once './middlewares/EsMozoMiddleWare.php';


require_once './controllers/UsuarioController.php';
require_once './controllers/PersonaController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/EncuestaController.php';
require_once './controllers/MesaController.php';
require_once './controllers/ProductosController.php';
require_once './controllers/LoginController.php';




// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes

$app->post('/Login', \LoginController::class . ':VerificarUsuario');

$app->group('/Persona', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PersonaController::class . ':TraerTodos');
    $group->get('/{mail}', \PersonaController::class . ':TraerUno');
    $group->post('/Alta', \PersonaController::class . ':CargarUno');  
   // $group->post('/Modificar', \PersonaController::class . ':ModificarUno');
  })->add(new EsSocioMiddleWare());

  $app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioController::class . ':TraerTodos');
    $group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
    $group->post('/Alta', \UsuarioController::class . ':CargarUno');  
    $group->post('/Modificar', \UsuarioController::class . ':ModificarUno');
    $group->delete('/Baja/{usuario}', \UsuarioController::class . ':BorrarUno');
  })->add(new EsSocioMiddleWare());

  $app->group('/Producto', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductosController::class . ':TraerTodos');
    $group->get('/{nombreProducto}', \ProductosController::class . ':TraerUno');
    $group->post('/Alta', \ProductosController::class . ':CargarUno');  
    $group->post('/Modificar', \ProductosController::class . ':ModificarUno');
    
  });
  $app->group('/Mesa', function (RouteCollectorProxy $group) {
    $group->get('[/]', \MesaController::class . ':TraerTodos');
    $group->get('/{id}', \MesaController::class . ':TraerUno');
    $group->post('/Alta', \MesaController::class . ':CargarUno');  
    $group->post('/Modificar', \MesaController::class . ':ModificarUno');
    
  });
  $app->group('/Encuesta', function (RouteCollectorProxy $group) {
    $group->get('[/]', \EncuestaController::class . ':TraerTodos');
    $group->get('/{numMesa}', \EncuestaController::class . ':TraerUno');
    $group->post('/Alta', \EncuestaController::class . ':CargarUno');       
  });

  $app->group('/Pedido', function (RouteCollectorProxy $group) {
    $group->get('[/]', \PedidoController::class . ':TraerTodos');
    $group->get('/TraerUno/{idproducto}/{idpedido}', \PedidoController::class . ':TraerUno');
    $group->post('/Alta', \PedidoController::class . ':CargarUno')->add(new EsMozoMiddleWare());  
    $group->post('/ModificarPedido', \PedidoController::class . ':ModificarUno');  
    $group->post('/ModificarItemPedido', \PedidoController::class . ':ModificarItemPedido'); 
    $group->get('/listarPendientes/{idsector}', \PedidoController::class . ':TraerTodosPorSector'); 


  });




$app->get('[/]', function (Request $request, Response $response) {    
    $payload = json_encode(array("mensaje" => "Slim Framework 4 PHP"));
    
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
