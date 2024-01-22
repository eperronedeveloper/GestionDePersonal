<?php

//RENDERS
$app->get('/', 'HomeController:renderInicio');
$app->get('/portada', 'HomeController:renderPortada')->add(requiereUsuario());
$app->any('/actualizar-sesion', 'HomeController:actualizarSesion')->add(requiereUsuario());
$app->any('/cerrar-sesion', 'HomeController:logout')->add(requiereUsuario());