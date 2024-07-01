<?php

date_default_timezone_set('America/Argentina/Buenos_Aires');
require_once './models/Producto.php';

class Venta
{
    public $id;
    public $email;
    public $nombre;
    public $tipo;
    public $talla;
    public $stock;
    public $fecha;
    public $nroPedido;

    public function crearVenta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $rta = null;

        if(Producto::existeProductoNombreYTipo($this->nombre, $this->tipo))
        {            
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO ventas (email, nombre, tipo, talla, stock, fecha, nroPedido) VALUES (:email, :nombre, :tipo, :talla, :stock, :fecha, :nroPedido)");
            $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
            $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
            $consulta->bindValue(':talla', $this->talla, PDO::PARAM_STR);
            $consulta->bindValue(':stock', $this->stock, PDO::PARAM_INT);
            $fecha = new DateTime(date("d-m-Y"));
            $consulta->bindValue(':fecha', date_format($fecha, 'Y-m-d'));
            $consulta->bindValue(':nroPedido', $this->nroPedido, PDO::PARAM_INT);
            $consulta->execute();

            $rta = $objAccesoDatos->obtenerUltimoId();
            Producto::descontarStock($this->stock, $this->nombre, $this->tipo, $this->talla);
        }
        else
        {
            $rta = false;
        }

        return $rta;
    }

    public static function modificarVenta($nroPedido, $email, $nombre, $tipo, $talla, $stock)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $rta = null;

        if(Venta::existeVentaNroPedido($nroPedido))
        {
            $consulta = $objAccesoDatos->prepararConsulta("UPDATE ventas SET email = :email, nombre = :nombre, tipo = :tipo, talla = :talla, stock = :stock WHERE nroPedido = :nroPedido");
            $consulta->bindValue(':email', $email, PDO::PARAM_STR);
            $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
            $consulta->bindValue(':tipo', $tipo, PDO::PARAM_STR);
            $consulta->bindValue(':talla', $talla, PDO::PARAM_STR);
            $consulta->bindValue(':stock', $stock, PDO::PARAM_INT);
            $consulta->bindValue(':nroPedido', $nroPedido, PDO::PARAM_INT);
            $consulta->execute();

            $rta = true;
        }
        else
        {
            $rta = false;
        }
        return $rta;
    }

    public static function existeVentaNroPedido($nroPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT 1 FROM ventas WHERE nroPedido = :nroPedido");
        $consulta->bindValue(':nroPedido', $nroPedido, PDO::PARAM_INT);
        $consulta->execute();
        
        // Verifica si la consulta devuelve alguna fila
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        // Devuelve true si se encontró una fila, false si no
        return $resultado !== false;
    }
}