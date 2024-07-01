<?php
require_once './models/Producto.php';

class ProductoController extends Producto
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $precio = $parametros['precio'];
        $tipo = $parametros['tipo'];
        $talla = $parametros['talla'];
        $color = $parametros['color'];
        $stock = $parametros['stock'];

        // Creamos el producto
        $producto = new Producto();
        $producto->nombre = $nombre;
        $producto->precio = $precio;
        $producto->tipo = $tipo;
        $producto->talla = $talla;
        $producto->color = $color;
        $producto->stock = $stock;
        $producto->crearProducto();

        $payload = json_encode(array("mensaje" => "Producto creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ConsularProducto($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $tipo = $parametros['tipo'];
        $color = $parametros['color'];

        // Creamos el producto
        $producto = new Producto();
        $producto->nombre = $nombre;
        $producto->tipo = $tipo;
        $producto->color = $color;
        $rta = $producto->consultarProducto();

        $payload = json_encode(array("mensaje" => $rta));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}