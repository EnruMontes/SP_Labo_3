<?php
require_once './models/Usuario.php';

class UsuarioController extends Venta
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $mail = $parametros['mail'];
        $user = $parametros['usuario'];
        $contrasenia = $parametros['contrasenia'];
        $perfil = $parametros['perfil'];

        // Creamos la usuario
        $usuario = new Usuario();
        $usuario->mail = $mail;
        $usuario->usuario = $user;
        $usuario->contrasenia = $contrasenia;
        $usuario->perfil = $perfil;

        if($usuario->crearUsuario() != null)
        {
            $archivo = isset($_FILES['foto']) ? $_FILES['foto'] : null;
            $tempFilePath = $archivo['tmp_name']; // Ruta temporal del archivo

            $imagenGuardada = Usuario::guardarImagenUsuario("ImagenesDeUsuarios/2024/", $usuario->usuario, $usuario->perfil, $tempFilePath);
            if($imagenGuardada != false)
            {
                $payload = json_encode(array("mensaje" => "Se creo el usuario y se guardo la imagen"));
            }
            else
            {
                $payload = json_encode(array("mensaje" => "Se creo el usario pero no se pudo guardar la imagen"));
            }
        }
        else
        {
            $payload = json_encode(array("mensaje" => "No se pudo crear el usuario"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function LoginUsuario($request, $response, $args)
    {
      $parametros = $request->getParsedBody();

      $user = $parametros['usuario'];
      $perfil = $parametros['perfil'];
      $contrasenia = $parametros['contrasenia'];

      $existe = false;
      $listaUsuarios = Usuario::obtenerTodos();

      foreach ($listaUsuarios as $usuario) {
        if($usuario->usuario == $user && $usuario->contrasenia == $contrasenia)
        {
          $existe = true;
          $idUsuario = $usuario->id;
        }
      }
      if($existe)
      {
        $datos=array('idUsuario' => $idUsuario, 'perfil' => $perfil, 'usuario' => $user, 'contrsenia' => $contrasenia);
        $token = AutentificadorJWT::CrearToken($datos);
        $payload = json_encode(array('jwt' => $token));
      }
      else
      {
        $payload = json_encode(array('error' => 'Nombre de usuario o clave incorrectos'));
      }

      $response->getBody()->write($payload);

      return $response->withHeader('Content-Type', 'application/json');

    }
}