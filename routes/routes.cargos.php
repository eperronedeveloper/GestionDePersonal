<?php

$app->group('/cargos', function () use ($app) {
    $app->get('', 'CargosController:renderCargos')->add(requierePermiso('PERSONAL_CARGOS'));
    $app->post('/crear', 'CargosController:renderCrearCargos')->add(requierePermiso('PERSONAL_CARGOS.GUARDAR'));
    $app->post('/detalle', 'CargosController:renderDetalleCargos')->add(requierePermiso('PERSONAL_CARGOS.DETALLE'));
    $app->get('/listar', 'CargosController:listarCargos')->add(requierePermiso('PERSONAL_CARGOS'));
})->add(requiereUsuario());

$app->group('/api/cargos', function () use ($app) {
    $app->post('/crear', 'CargosController:crearCargo')->add(requierePermiso('PERSONAL_CARGOS.GUARDAR'));
        $app->group('/{idCargo}', function () use ($app) {
            $app->post(':cambiarEstado', 'CargosController:cambiarEstadoCargo')->add(requierePermiso('PERSONAL_CARGOS.CAMBIAR_ESTADO'));
            $app->put('/editar', 'CargosController:editarCargo')->add(requierePermiso('PERSONAL_CARGOS.GUARDAR'));
            $app->delete('/eliminar', 'CargosController:eliminarCargo')->add(requierePermiso('PERSONAL_CARGOS.ELIMINAR'));
        });
})->add(requiereUsuario());