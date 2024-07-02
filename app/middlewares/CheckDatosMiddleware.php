<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class CheckDatosMiddleware
{
    public function VerificarMail(Request $request, RequestHandler $handler): Response
    {   
        $email = $_GET['email'];

        if(!isset($email) || empty($email))
        {
            $response = new Response();
            $payload = json_encode(array('error' => 'Email vacio'));
            $response->getBody()->write($payload);
        }
        else
        {
            $response = $handler->handle($request);
        }
        
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function VerificarTipo(Request $request, RequestHandler $handler): Response
    {   
        $tipo = $_GET['tipo'];

        if(!isset($tipo) || empty($tipo))
        {
            $response = new Response();
            $payload = json_encode(array('error' => 'Tipo vacio'));
            $response->getBody()->write($payload);
        }
        else
        {
            $response = $handler->handle($request);
        }
        
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function VerificarValores(Request $request, RequestHandler $handler): Response
    {   
        
        $valor1 = $_GET['valor1'];
        $valor2 = $_GET['valor2'];

        if(!isset($valor1, $valor2) || empty($valor1) || empty($valor2))
        {
            $response = new Response();
            $payload = json_encode(array('error' => 'Valores vacio'));
            $response->getBody()->write($payload);
        }
        else
        {
            $response = $handler->handle($request);
        }
        
        return $response->withHeader('Content-Type', 'application/json');
    }
}