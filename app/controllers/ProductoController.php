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

        if($producto->crearProducto() != null)
        {
          $payload = json_encode(array("mensaje" => "Producto creado con exito"));
          
          $archivo = isset($_FILES['foto']) ? $_FILES['foto'] : null;
          $tempFilePath = $archivo['tmp_name']; // Ruta temporal del archivo
          
          $guardadoImagen = Producto::guardarImagenProducto("ImagenesDeRopa/2024/", $producto->nombre, $producto->tipo, $tempFilePath);
          if($guardadoImagen != false)
          {
            $payload = json_encode(array("mensaje" => "Producto e imagen creado con exito"));
          }
          else
          {
            $payload = json_encode(array("mensaje" => "Se cargo el producto pero hubo un error al cargar la imagen"));
          }
        }
        else
        {
          $payload = json_encode(array("mensaje" => "No se pudo cargar el producto"));
        }

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