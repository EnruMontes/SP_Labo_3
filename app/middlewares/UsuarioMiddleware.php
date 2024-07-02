<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class UsuarioMiddleware
{
    public function VerificarPerfil(Request $request, RequestHandler $handler): Response
    {   
        $parametros = $request->getParsedBody();
        $perfil = $parametros['perfil'];

        if($perfil != "cliente" && $perfil != "empleado" && $perfil != "admin")
        {
            $response = new Response();
            $payload = json_encode(array('error' => 'perfil incorrecto'));
            $response->getBody()->write($payload);
        }
        else
        {
            $response = $handler->handle($request);
        }
        
        return $response->withHeader('Content-Type', 'application/json');
    }
}