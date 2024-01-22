<?php

$app->group('/fichadas', function () use ($app) {
    $app->get('', 'FichadasController:renderFichadas')->add(requierePermiso('PERSONAL_FICHADAS'));
    $app->post('/crear', 'FichadasController:renderCrearFichadas')->add(requierePermiso('PERSONAL_FICHADAS.GUARDAR'));
    $app->post('/detalle', 'FichadasController:renderDetalleFichadas')->add(requierePermiso('PERSONAL_FICHADAS.DETALLE'));
    $app->get('/listar', 'FichadasController:listarFichadas')->add(requierePermiso('PERSONAL_FICHADAS'));
    $app->post('/ACLegajo', 'FichadasController:ACLegajo')->add(requierePermiso('PERSONAL_FICHADAS'));
})->add(requiereUsuario());

$app->group('/api/fichadas', function () use ($app) {
    $app->post('/crear', 'FichadasController:crearFichada')->add(requierePermiso('PERSONAL_FICHADAS.GUARDAR'));
        $app->group('/{legajo}', function () use ($app) {
            $app->put('/editar', 'FichadasController:editarFichada')->add(requierePermiso('PERSONAL_FICHADAS.GUARDAR'));
            $app->delete('/eliminar', 'FichadasController:eliminarFichada')->add(requierePermiso('PERSONAL_FICHADAS.ELIMINAR'));
        });
})->add(requiereUsuario());