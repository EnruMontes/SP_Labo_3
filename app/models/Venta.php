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
    public $precio;

    public function crearVenta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $rta = null;

        if(Producto::existeProductoNombreYTipo($this->nombre, $this->tipo))
        {            
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO ventas (email, nombre, tipo, talla, stock, fecha, nroPedido, precio) VALUES (:email, :nombre, :tipo, :talla, :stock, :fecha, :nroPedido, :precio)");
            $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
            $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
            $consulta->bindValue(':talla', $this->talla, PDO::PARAM_STR);
            $consulta->bindValue(':stock', $this->stock, PDO::PARAM_INT);
            $fecha = new DateTime(date("d-m-Y"));
            $consulta->bindValue(':fecha', date_format($fecha, 'Y-m-d'));
            $consulta->bindValue(':nroPedido', $this->nroPedido, PDO::PARAM_INT);
            $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
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

    public static function modificarVenta($nroPedido, $email, $nombre, $tipo, $talla, $stock, $precio)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $rta = null;

        if(Venta::existeVentaNroPedido($nroPedido))
        {
            $consulta = $objAccesoDatos->prepararConsulta("UPDATE ventas SET email = :email, nombre = :nombre, tipo = :tipo, talla = :talla, stock = :stock, precio = :precio WHERE nroPedido = :nroPedido");
            $consulta->bindValue(':email', $email, PDO::PARAM_STR);
            $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
            $consulta->bindValue(':tipo', $tipo, PDO::PARAM_STR);
            $consulta->bindValue(':talla', $talla, PDO::PARAM_STR);
            $consulta->bindValue(':stock', $stock, PDO::PARAM_INT);
            $consulta->bindValue(':nroPedido', $nroPedido, PDO::PARAM_INT);
            $consulta->bindValue(':precio', $precio, PDO::PARAM_INT);
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

    public static function guardarImagenVenta($path, $nombre, $tipo, $talla, $email, $nombreArchivo)
    {
        $rta = false;
    
        $fecha_actual = date('d-m-Y');
    
        // explode() divide la cadena en un array tomando como "@" como delimitador
        $parts = explode('@', $email);
        // Toma la primera parte que es antes del "@"
        $email_recortado = $parts[0];
    
        // Ruta a donde se quiere mover el archivo
        $destino = $path . $nombre . " - " . $tipo . " - " . $talla . " - " . $email_recortado . " - " . $fecha_actual . ".png";
        
        // $_FILES[$nombreArchivo]["tmp_name"] Ruta temporal donde tiene el archivo php
        if(move_uploaded_file($nombreArchivo, $destino)){
            $rta = true;
        }
        
        return $rta;
    }

    public static function obtenerVentasDia()
    {
        $date = new DateTime();
        $date->modify('-1 day');
        $fechaAyer = $date->format('Y-m-d');

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM ventas WHERE fecha = :fecha");
        $consulta->bindValue(':fecha', $fechaAyer);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Venta');
    }

    public static function obtenerVentasUsuario($usuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM ventas WHERE email = :email");
        $consulta->bindValue(':email', $usuario);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Venta');
    }

    public static function obtenerVentasTipo($tipo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM ventas WHERE tipo = :tipo");
        $consulta->bindValue(':tipo', $tipo);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Venta');
    }

    public static function obtenerVentasEntreValores($valor1, $valor2)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM ventas WHERE precio >= :valor1 AND precio <= :valor2");
        $consulta->bindValue(':valor1', $valor1);
        $consulta->bindValue(':valor2', $valor2);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Venta');
    }

    public static function obtenerIngresosPorDia($dia)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        if($dia == null)
        {
            $consulta = $objAccesoDatos->prepararConsulta("SELECT fecha, precio FROM ventas");
            $consulta->execute();
        }
        else
        {
            $consulta = $objAccesoDatos->prepararConsulta("SELECT fecha, precio FROM ventas WHERE fecha = :dia");
            $consulta->bindValue(':dia', $dia);
            $consulta->execute();
        }

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerProductoMasVendido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT nombre, SUM(stock) AS totalVentas FROM ventas GROUP BY nombre ORDER BY totalVentas DESC LIMIT 1;");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
}