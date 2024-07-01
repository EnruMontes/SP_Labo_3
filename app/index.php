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

require_once './controllers/ProductoController.php';
require_once './controllers/VentasController.php';

require_once './middlewares/TiendaMiddleware.php';

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
$app->group('/tienda', function (RouteCollectorProxy $group) {
  $group->post('/alta', \ProductoController::class . ':CargarUno')->add(\TiendaMiddleware::class . ':VerificarTipoYTalla');
  $group->post('/consultar', \ProductoController::class . ':ConsularProducto');
});

$app->group('/ventas', function (RouteCollectorProxy $group) {
  $group->post('/alta', \VentasController::class . ':CargarUna')->add(\TiendaMiddleware::class . ':ExisteStock');
  $group->put('/modificar', \VentasController::class . ':ModificarUna');
});


$app->run();