<?php
// DIC configuration

$container = $app->getContainer();

set_error_handler(function ($severity, $message, $file, $line) {
    if (error_reporting() & $severity) {
        throw new \ErrorException($message, 0, $severity, $file, $line);
    }
});

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------
// Twig

$container['view'] = function ($c) {
    $settings = $c->get('settings');
    $view = new \Slim\Views\Twig($settings['view']['template_path'], $settings['view']['twig']);

    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
    $view->addExtension(new Twig_Extension_Debug());
    return $view;
};

// Twig, FUNCION PARA PERMISOS EN VISTAS
$twig = $container['view']->getEnvironment();
$function = new Twig_SimpleFunction('tienePermiso', function ($permisoRequerido) {
    if(!is_array($permisoRequerido)) (array) $permisoRequerido;
    if(in_array($permisoRequerido, USUARIO_PERMISOS)){
        return true;
    }else{
        return false;
    };    
});
$twig->addFunction($function);

// Mensajes Flash
$container['flash'] = function ($c) {
    return new \Slim\Flash\Messages;
};

// -----------------------------------------------------------------------------
// Service factories
// -----------------------------------------------------------------------------

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings');
    $logger = new \Monolog\Logger($settings['logger']['name']);
    $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
    $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['logger']['path'], \Monolog\Logger::DEBUG));
    return $logger;
};

$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        return $container->view->render($response, "embeds/error/error404.twig");
    };
};

// Configuramos la gestion de errores
$container['errorHandler'] = function ($container) {
    return function ($request, $response, $exception) use ($container) {
        
         //LOG DE ERRORES DESCOMENTAR PARA MOSTRAR EN ALGUN MODULO CON PERMISOS
         //$logger = $container['logger'];
         //$logger->error($exception->getMessage(), array(
         //    'archivo' => $exception->getFile(),
         //    'linea' => $exception->getLine(),
         //    'codigo' => $exception->getCode(),
         //        //'trace' => $exception->getTrace()
         //));

        // respuesta personalizada
        // $response->getBody()->rewind();
        // $response = $response->withStatus(500);
        
        //DEBUG TRUE EN DESARROLLO - CAMBIAR A FALSE EN PRODUCCION EN SETTINGS.PHP
        if (DEBUG) {
            $errorCapturado = array(
                'status' => 500,
                'error' => $exception->getMessage(),
                'metadata' => array(
                    'archivo' => $exception->getFile(),
                    'linea' => $exception->getLine(),
                    'codigo' => $exception->getCode(),
                    'trace' => $exception->getTrace()
                ),
            );
            return $response->withJson($errorCapturado, 500);
        }else{
            return $response->withMsgError('ERROR. PÓNGASE EN CONTACTO CON EL ADMINISTRADOR DEL SISTEMA.');
        }
    };
};


