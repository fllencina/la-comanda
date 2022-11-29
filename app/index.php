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
require_once './middlewares/EsPreparadorMiddleWare.php';



require_once './controllers/UsuarioController.php';
require_once './controllers/PersonaController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/EncuestaController.php';
require_once './controllers/MesaController.php';
require_once './controllers/ProductosController.php';
require_once './controllers/LoginController.php';
require_once './controllers/ActividadController.php';





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
  })->add(new EsSocioMiddleWare());

  $app->group('/Mesa', function (RouteCollectorProxy $group) {
    $group->get('[/]', \MesaController::class . ':TraerTodos')->add(new EsSocioMiddleWare());
    $group->get('/UnaMesa/{id}', \MesaController::class . ':TraerUno')->add(new EsSocioMiddleWare());
    $group->post('/Alta', \MesaController::class . ':CargarUno')->add(new EsSocioMiddleWare()); 
    $group->post('/Modificar', \MesaController::class . ':ModificarUno')->add(new EsMozoMiddleWare()); 
    $group->post('/Cerrar', \MesaController::class . ':ModificarUno')->add(new EsSocioMiddleWare()); 
    $group->get('/MasUsada', \MesaController::class . ':TraerMasUsada')->add(new EsSocioMiddleWare());
});

  $app->group('/Encuesta', function (RouteCollectorProxy $group) {
    $group->get('[/]', \EncuestaController::class . ':TraerTodos')->add(new EsSocioMiddleWare());
    $group->get('/Mesa/{numMesa}', \EncuestaController::class . ':TraerUno')->add(new EsSocioMiddleWare());
    //cliente 
    $group->post('/Alta', \EncuestaController::class . ':CargarUno'); 

    $group->get('/ListarEncuesta', \EncuestaController::class . ':TraerLosMejoresComentarios')->add(new EsSocioMiddleWare());    
  });

  $app->group('/Pedido', function (RouteCollectorProxy $group) {
    $group->get('/TraerTodos', \PedidoController::class . ':TraerTodos')->add(new EsSocioMiddleWare());
    $group->get('/TraerTodosEstado/{idestado}', \PedidoController::class . ':TraerTodosEstado')->add(new EsSocioMiddleWare());
    $group->get('/TraerTodosSector/{idsector}', \PedidoController::class . ':TraerTodosPorSector')->add(new EsSocioMiddleWare());
    //traer los pendientes de sector para que lo puedan tomar los preparadores
    $group->get('/TraerTodosPendientesSector/{idsector}', \PedidoController::class . ':TraerTodosPendientesSector')->add(new EsPreparadorMiddleWare());
    $group->get('/TraerTodosUsuario/{idusuario}', \PedidoController::class . ':TraerTodosPorUsuario')->add(new EsSocioMiddleWare());
    $group->get('/TraerUno/{idproducto}/{idpedido}', \PedidoController::class . ':TraerUno');
    $group->post('/Alta', \PedidoController::class . ':CargarUno')->add(new EsMozoMiddleWare());  
    //cambiar estado de pedido SOLO MOZO
    $group->post('/ModificarEstadoPedido', \PedidoController::class . ':ModificarEstadoUno')->add(new EsMozoMiddleWare()); 
    $group->post('/AgregarFoto', \PedidoController::class . ':agregarfotopedido')->add(new EsMozoMiddleWare()); 
    
    //cambiar estado item pedido SOLO PREPARADORES
    $group->post('/ModificarEstadoItemPedido', \PedidoController::class . ':ModificarEstadoItemPedido')->add(new EsPreparadorMiddleWare()); 
   

  });


$app->get('/Actividad', \ActividadController::class . ':ExportarCSV');
$app->post('/Actividad', \ActividadController::class . ':ImportarCSV')->add(new EsSocioMiddleWare());

$app->post('/Cliente', \PedidoController::class . ':obtenerPedidoDeCliente');





$app->run();
