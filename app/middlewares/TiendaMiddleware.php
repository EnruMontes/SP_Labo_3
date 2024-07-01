<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class TiendaMiddleware
{
    public function VerificarTipoYTalla(Request $request, RequestHandler $handler): Response
    {   
        $parametros = $request->getParsedBody();
        $tipo = $parametros['tipo'];
        $talla = $parametros['talla'];

        if($tipo != "Camiseta" && $tipo != "Pantalon")
        {
            $response = new Response();
            $payload = json_encode(array('error' => 'tipo incorrecto'));
            $response->getBody()->write($payload);
        }
        elseif($talla != "S" && $talla != "M" && $talla != "L")
        {
            $response = new Response();
            $payload = json_encode(array('error' => 'talla incorrecta'));
            $response->getBody()->write($payload);
        }
        else
        {
            $response = $handler->handle($request);
        }
        
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ExisteStock(Request $request, RequestHandler $handler): Response
    {
        require_once './models/Producto.php';
        
        $parametros = $request->getParsedBody();
        $stockVenta = $parametros['stock'];
        $nombre = $parametros['nombre'];
        $tipo = $parametros['tipo'];

        $stockExistente = Producto::obtenerStock($nombre, $tipo);

        if($stockExistente == null)
        {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'No se encontro el producto'));
            $response->getBody()->write($payload);
        }
        elseif($stockVenta > $stockExistente)
        {
            $response = new Response();
            $payload = json_encode(array('error' => 'No hay suficiente stock'));
            $response->getBody()->write($payload);
        }
        else
        {
            $response = $handler->handle($request);
        }
        
        return $response->withHeader('Content-Type', 'application/json');
    }
}