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
        $precio = $parametros['precio'];

        // Creamos la venta
        $venta = new Venta();
        $venta->email = $email;
        $venta->nombre = $nombre;
        $venta->tipo = $tipo;
        $venta->talla = $talla;
        $venta->stock = $stock;
        $venta->nroPedido = $nroPedido;
        $venta->precio = $precio;

        if($venta->crearVenta())
        {
            $archivo = isset($_FILES['foto']) ? $_FILES['foto'] : null;
            $tempFilePath = $archivo['tmp_name']; // Ruta temporal del archivo

            $imagenGuardada = Venta::guardarImagenVenta("ImagenesDeVenta/2024/", $venta->nombre, $venta->tipo, $venta->talla, $venta->email, $tempFilePath);
            if($imagenGuardada != false)
            {
                $payload = json_encode(array("mensaje" => "Se creo la venta y se guardo la imagen"));
            }
            else
            {
                $payload = json_encode(array("mensaje" => "Se creo la venta pero no se pudo guardar la imagen"));
            }
        }
        else
        {
            $payload = json_encode(array("mensaje" => "No se encontro el producto"));
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
        $precio = $parametros['precio'];

        $rta = Venta::modificarVenta($nroPedido, $email, $nombre, $tipo, $talla, $stock, $precio);
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

    public function TraerVentasDia($request, $response, $args)
    {
        $lista = Venta::obtenerVentasDia();
        $payload = json_encode(array("Lista Ventas" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerVentasPorUsuario($request, $response, $args)
    {
        $email = isset($_GET['email']) ? $_GET['email'] : null;
        $lista = Venta::obtenerVentasUsuario($email);
        $payload = json_encode(array("Lista Ventas" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerVentasPorProducto($request, $response, $args)
    {
        $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : null;
        $lista = Venta::obtenerVentasTipo($tipo);
        $payload = json_encode(array("Lista Ventas" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerVentasEntreValores($request, $response, $args)
    {
        $valor1 = isset($_GET['valor1']) ? $_GET['valor1'] : null;
        $valor2 = isset($_GET['valor2']) ? $_GET['valor2'] : null;
        $lista = Venta::obtenerVentasEntreValores($valor1, $valor2);
        $payload = json_encode(array("Lista Ventas" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerVentasPorIngresos($request, $response, $args)
    {
        $dia = isset($_GET['dia']) ? $_GET['dia'] : null;
        $lista = Venta::obtenerIngresosPorDia($dia);

        $preciosAcumulados = [];

        foreach ($lista as $venta) 
        {
            $fecha = $venta["fecha"];
            $precio = $venta["precio"];
            
            if (isset($preciosAcumulados[$fecha])) 
            {
                $preciosAcumulados[$fecha] += $precio;
            } else 
            {
                $preciosAcumulados[$fecha] = $precio;
            }
        }
        
        $listaPrecioAcumulablePorFecha = [];
        foreach ($preciosAcumulados as $fecha => $precio) 
        {
            $listaPrecioAcumulablePorFecha[] = ["fecha" => $fecha, "precio" => $precio];
        }
        
        $payload = json_encode(array("Lista ganancias por dia" => $listaPrecioAcumulablePorFecha));
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerProductoMasVendido($request, $response, $args)
    {
        $lista = Venta::obtenerProductoMasVendido();
        $payload = json_encode(array("Producto mas vendido" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}