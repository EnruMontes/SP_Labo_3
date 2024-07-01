<?php
require_once './models/Venta.php';

class VentasController extends Venta
{
    public function CargarUna($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $email = $parametros['email'];
        $nombre = $parametros['nombre'];
        $tipo = $parametros['tipo'];
        $talla = $parametros['talla'];
        $stock = $parametros['stock'];
        $nroPedido = $parametros['nroPedido'];

        // Creamos la venta
        $venta = new Venta();
        $venta->email = $email;
        $venta->nombre = $nombre;
        $venta->tipo = $tipo;
        $venta->talla = $talla;
        $venta->stock = $stock;
        $venta->nroPedido = $nroPedido;

        if($venta->crearVenta())
        {
            $payload = json_encode(array("mensaje" => "Venta creado con exito"));
        }
        else
        {
            $payload = json_encode(array("mensaje" => "No se encontro el productso"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUna($request, $response, $args) // x-www-form-unlencoded
    {
        $parametros = $request->getParsedBody();

        $nroPedido = $parametros['nroPedido'];
        $email = $parametros['email'];
        $nombre = $parametros['nombre'];
        $tipo = $parametros['tipo'];
        $talla = $parametros['talla'];
        $stock = $parametros['stock'];

        $rta = Venta::modificarVenta($nroPedido, $email, $nombre, $tipo, $talla, $stock);
        if($rta != false)
        {
            $payload = json_encode(array("mensaje" => "Venta modificada con exito"));
        }
        else
        {
            $payload = json_encode(array("error" => "No existe el numero del pedido"));
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}