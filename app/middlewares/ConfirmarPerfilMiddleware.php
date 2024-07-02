<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class ConfirmarPerfilMiddleware // Tendria que tener la funcion __construct
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        try {
            AutentificadorJWT::VerificarToken($token);
            $response = $handler->handle($request);
        } catch (Exception $e) {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'ERROR: Hubo un error con el TOKEN'));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function VerificarAdmin(Request $request, RequestHandler $handler)
    {   
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        if($token!=null)
        {
            AutentificadorJWT::VerificarToken($token);
            $parametros = (array)AutentificadorJWT::ObtenerData($token);
    
            $usuario = $parametros['perfil'];
    
            if ($usuario === 'admin') {
                $response = $handler->handle($request);
            } else {
                $response = new Response();
                $payload = json_encode(array('mensaje' => 'No sos Admin'));
                $response->getBody()->write($payload);
            }
        }
        else
        {
            $response = new Response();
            $payload = json_encode(array('error' => 'Token vacio'));
            $response->getBody()->write($payload);
        }


        return $response->withHeader('Content-Type', 'application/json');
    }

    public function VerificarEmpleado(Request $request, RequestHandler $handler)
    {   
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        if($token!=null)
        {
            AutentificadorJWT::VerificarToken($token);
            $parametros = (array)AutentificadorJWT::ObtenerData($token);
    
            $usuario = $parametros['perfil'];
    
            if ($usuario === 'empleado') {
                $response = $handler->handle($request);
            } else {
                $response = new Response();
                $payload = json_encode(array('mensaje' => 'No sos Empleado'));
                $response->getBody()->write($payload);
            }
        }
        else
        {
            $response = new Response();
            $payload = json_encode(array('error' => 'Token vacio'));
            $response->getBody()->write($payload);
        }


        return $response->withHeader('Content-Type', 'application/json');
    }

    public function VerificarAdminYEmpleado(Request $request, RequestHandler $handler)
    {   
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        if($token!=null)
        {
            AutentificadorJWT::VerificarToken($token);
            $parametros = (array)AutentificadorJWT::ObtenerData($token);
    
            $usuario = $parametros['perfil'];
    
            if ($usuario === 'admin' || $usuario === 'empleado') {
                $response = $handler->handle($request);
            } else {
                $response = new Response();
                $payload = json_encode(array('mensaje' => 'No sos admin o empleado'));
                $response->getBody()->write($payload);
            }
        }
        else
        {
            $response = new Response();
            $payload = json_encode(array('error' => 'Token vacio'));
            $response->getBody()->write($payload);
        }


        return $response->withHeader('Content-Type', 'application/json');
    }
}