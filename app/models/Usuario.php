<?php

date_default_timezone_set('America/Argentina/Buenos_Aires');

class Usuario
{
    public $id;
    public $mail;
    public $usuario;
    public $contrasenia;
    public $perfil;
    public $foto;
    public $fechaDeAlta;
    public $fechaDeBaja;

    public function crearUsuario()
    {
        $rta = null;

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (mail, usuario, contrasenia, perfil, fecha_de_alta) VALUES (:mail, :usuario, :contrasenia, :perfil, :fecha_de_alta)");
        $consulta->bindValue(':mail', $this->mail, PDO::PARAM_STR);
        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':contrasenia', $this->contrasenia, PDO::PARAM_STR);
        $consulta->bindValue(':perfil', $this->perfil, PDO::PARAM_STR);
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':fecha_de_alta', date_format($fecha, 'Y-m-d'));
        $consulta->execute();

        $rta = $objAccesoDatos->obtenerUltimoId();
        return $rta;
    }

    public static function guardarImagenUsuario($path, $usuario, $perfil, $nombreArchivo)
    {
        $rta = false;
    
        $fecha_actual = date('d-m-Y');
    
        // Ruta a donde se quiere mover el archivo
        $destino = $path . $usuario . " - " . $perfil . " - " . $fecha_actual . ".png";
        
        // $_FILES[$nombreArchivo]["tmp_name"] Ruta temporal donde tiene el archivo php
        if(move_uploaded_file($nombreArchivo, $destino)){
            $rta = true;
        }
        
        return $rta;
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, mail, usuario, contrasenia, perfil FROM usuarios");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }
}