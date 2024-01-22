<?php

$app->group('/tipoAntiguedad', function () use ($app) {
    $app->get('', 'TipoAntiguedadController:renderTipoAntiguedad')->add(requierePermiso('PERSONAL_TIPOANTIGUEDAD'));
    $app->post('/crear', 'TipoAntiguedadController:renderCrearTipoAntiguedad')->add(requierePermiso('PERSONAL_TIPOANTIGUEDAD.GUARDAR'));
    $app->post('/detalle', 'TipoAntiguedadController:renderDetalleTipoAntiguedad')->add(requierePermiso('PERSONAL_TIPOANTIGUEDAD.DETALLE'));
    $app->get('/listar', 'TipoAntiguedadController:listarTipoAntiguedad')->add(requierePermiso('PERSONAL_TIPOANTIGUEDAD'));
})->add(requiereUsuario());

$app->group('/api/tipoAntiguedad', function () use ($app) {
    $app->post('/crear', 'TipoAntiguedadController:crearTipoAntiguedad')->add(requierePermiso('PERSONAL_TIPOANTIGUEDAD.GUARDAR'));
        $app->group('/{idTipoAntiguedad}', function () use ($app) {
            $app->post(':cambiarEstado', 'TipoAntiguedadController:cambiarEstadoTipoAntiguedad')->add(requierePermiso('PERSONAL_TIPOANTIGUEDAD.CAMBIAR_ESTADO'));
            $app->put('/editar', 'TipoAntiguedadController:editarTipoAntiguedad')->add(requierePermiso('PERSONAL_TIPOANTIGUEDAD.GUARDAR'));
            $app->delete('/eliminar', 'TipoAntiguedadController:eliminarTipoAntiguedad')->add(requierePermiso('PERSONAL_TIPOANTIGUEDAD.ELIMINAR'));
        });
})->add(requiereUsuario());