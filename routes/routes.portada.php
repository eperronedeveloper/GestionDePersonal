<?php

$app->group('/catalogos', function () use ($app) {
   $app->get('', 'HomeController:renderCatalogos')->add(requierePermiso('PERSONAL_CATALOGOS'));
})->add(requiereUsuario());