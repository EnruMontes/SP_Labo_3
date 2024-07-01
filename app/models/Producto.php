<?php

class Producto
{
    public $id;
    public $nombre;
    public $precio;
    public $tipo;
    public $talla;
    public $color;
    public $stock;

    public function crearProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $rta = null;
        
        if(!(Producto::existeProductoNombreYTipo($this->nombre, $this->tipo)))
        {
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO tienda (nombre, precio, tipo, talla, color, stock) VALUES (:nombre, :precio, :tipo, :talla, :color, :stock)");
            $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
            $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
            $consulta->bindValue(':talla', $this->talla, PDO::PARAM_STR);
            $consulta->bindValue(':color', $this->color, PDO::PARAM_STR);
            $consulta->bindValue(':stock', $this->stock, PDO::PARAM_INT);
            $consulta->execute();

            $rta = $objAccesoDatos->obtenerUltimoId();
        }
        else
        {
            $this->actualizarProducto();
            $rta = $objAccesoDatos->obtenerUltimoId();
        }
        return $rta;

    }

    public static function existeProductoNombreYTipo($nombre, $tipo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT 1 FROM tienda WHERE nombre = :nombre AND tipo = :tipo");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $tipo, PDO::PARAM_STR);
        $consulta->execute();
        
        // Verifica si la consulta devuelve alguna fila
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        // Devuelve true si se encontró una fila, false si no
        return $resultado !== false;
    }

    public function actualizarProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $stockViejo = Producto::obtenerStock($this->nombre, $this->tipo);
        $nuevoStock = $this->stock + $stockViejo;

        $consulta = $objAccesoDatos->prepararConsulta("UPDATE tienda SET precio = :precio, stock = :stock WHERE nombre = :nombre AND tipo = :tipo");
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(':stock', $nuevoStock, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function obtenerStock($nombre, $tipo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT stock FROM tienda WHERE nombre = :nombre AND tipo = :tipo");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $tipo, PDO::PARAM_STR);
        $consulta->execute();
        
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        if ($resultado === false) {
            // No se encontraron resultados
            $rta = null;
        }
        else
        {
            $rta = $resultado['stock'];
        }
    
        return $rta;
    }

    public function consultarProducto()
    {
        $rta = "";

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT 1 FROM tienda WHERE nombre = :nombre AND tipo = :tipo AND color = :color");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':color', $this->color, PDO::PARAM_STR);
        $consulta->execute();
        
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        
        if($resultado !== false)
        {
            $rta = "Existe";
        }
        else
        {
            $consulta = $objAccesoDatos->prepararConsulta("SELECT 1 FROM tienda WHERE nombre = :nombre");
            $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consulta->execute();

            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            if($resultado == false)
            {
                $rta = "No hay productos del nombre " . $this->nombre;
            }
            else
            {
                $consulta = $objAccesoDatos->prepararConsulta("SELECT 1 FROM tienda WHERE tipo = :tipo");
                $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
                $consulta->execute();
    
                $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

                if($resultado == false)
                {
                    $rta = "No hay productos del tipo " . $this->tipo;
                }
                else
                {
                    $consulta = $objAccesoDatos->prepararConsulta("SELECT 1 FROM tienda WHERE color = :color");
                    $consulta->bindValue(':color', $this->color, PDO::PARAM_STR);
                    $consulta->execute();
        
                    $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

                    if($resultado == false)
                    {
                        $rta = "No hay productos del color " . $this->color;
                    }
                    else
                    {
                        $rta = "Existe algun atributo de esos productos pero por separado.";
                    }
                }
            }
        }
        
        return $rta;
    }

    public static function descontarStock($stock, $nombre, $tipo, $talla)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $stockViejo = Producto::obtenerStock($nombre, $tipo);
        $nuevoStock = $stockViejo - $stock;

        $consulta = $objAccesoDatos->prepararConsulta("UPDATE tienda SET stock = :stock WHERE nombre = :nombre AND tipo = :tipo AND talla = :talla");
        $consulta->bindValue(':stock', $nuevoStock, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $tipo, PDO::PARAM_STR);
        $consulta->bindValue(':talla', $talla, PDO::PARAM_STR);
        $consulta->execute();
    } 
}