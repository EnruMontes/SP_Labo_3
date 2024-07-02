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
require_once './utils/AutentificadorJWT.php';

require_once './controllers/ProductoController.php';
require_once './controllers/VentasController.php';
require_once './controllers/UsuarioController.php';

require_once './middlewares/TiendaMiddleware.php';
require_once './middlewares/UsuarioMiddleware.php';
require_once './middlewares/ConfirmarPerfilMiddleware.php';
require_once './middlewares/CheckDatosMiddleware.php';

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
  $group->post('/alta', \ProductoController::class . ':CargarUno')
  ->add(\TiendaMiddleware::class . ':VerificarTipoYTalla')
  ->add(\ConfirmarPerfilMiddleware::class . ':VerificarAdmin');

  $group->post('/consultar', \ProductoController::class . ':ConsularProducto');
});

$app->group('/ventas', function (RouteCollectorProxy $group) {
  $group->get('/descargar', \VentasController::class . ':GuardarCSV')->add(\ConfirmarPerfilMiddleware::class . ':VerificarAdmin');

  $group->group('/consultar', function (RouteCollectorProxy $groupConsultar) {
      $groupConsultar->get('/productos/vendidos', \VentasController::class . ':TraerVentasDia')->add(\ConfirmarPerfilMiddleware::class . ':VerificarAdminYEmpleado');
      $groupConsultar->get('/ventas/porUsuario', \VentasController::class . ':TraerVentasPorUsuario')->add(\ConfirmarPerfilMiddleware::class . ':VerificarAdminYEmpleado')
      ->add(\CheckDatosMiddleware::class . ':VerificarMail');
      $groupConsultar->get('/ventas/porProducto', \VentasController::class . ':TraerVentasPorProducto')->add(\ConfirmarPerfilMiddleware::class . ':VerificarAdminYEmpleado')
      ->add(\CheckDatosMiddleware::class . ':VerificarTipo');
      $groupConsultar->get('/productos/entreValores', \VentasController::class . ':TraerVentasEntreValores')->add(\ConfirmarPerfilMiddleware::class . ':VerificarAdminYEmpleado')
      ->add(\CheckDatosMiddleware::class . ':VerificarValores');
      $groupConsultar->get('/ventas/ingresos', \VentasController::class . ':TraerVentasPorIngresos')->add(\ConfirmarPerfilMiddleware::class . ':VerificarAdmin');
      $groupConsultar->get('/productos/masVendido', \VentasController::class . ':TraerProductoMasVendido')->add(\ConfirmarPerfilMiddleware::class . ':VerificarAdminYEmpleado');
  });

  $group->post('/alta', \VentasController::class . ':CargarUna')->add(\TiendaMiddleware::class . ':ExisteStock');
  $group->put('/modificar', \VentasController::class . ':ModificarUna')->add(\ConfirmarPerfilMiddleware::class . ':VerificarAdmin');
});

$app->post('/registro', \UsuarioController::class . ':CargarUno')->add(\UsuarioMiddleware::class . ':VerificarPerfil');

$app->post('/login', \UsuarioController::class . ':LoginUsuario');

$app->run();