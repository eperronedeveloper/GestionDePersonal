<?php

$app->group('/dependencias', function () use ($app) {
    $app->get('', 'DependenciasController:renderDependencias')->add(requierePermiso('PERSONAL_DEPENDENCIAS'));
    $app->post('/crear', 'DependenciasController:renderCrearDependencias')->add(requierePermiso('PERSONAL_DEPENDENCIAS.GUARDAR'));
    $app->post('/detalle', 'DependenciasController:renderDetalleDependencias')->add(requierePermiso('PERSONAL_DEPENDENCIAS.DETALLE'));
    $app->get('/listar', 'DependenciasController:listarDependencias')->add(requierePermiso('PERSONAL_DEPENDENCIAS'));
})->add(requiereUsuario());

$app->group('/api/dependencias', function () use ($app) {
    $app->post('/crear', 'DependenciasController:crearDependencia')->add(requierePermiso('PERSONAL_DEPENDENCIAS.GUARDAR'));
        $app->group('/{idDependencia}', function () use ($app) {
            $app->post(':cambiarEstado', 'DependenciasController:cambiarEstadoDependencia')->add(requierePermiso('PERSONAL_DEPENDENCIAS.CAMBIAR_ESTADO'));
            $app->put('/editar', 'DependenciasController:editarDependencia')->add(requierePermiso('PERSONAL_DEPENDENCIAS.GUARDAR'));
            $app->delete('/eliminar', 'DependenciasController:eliminarDependencia')->add(requierePermiso('PERSONAL_DEPENDENCIAS.ELIMINAR'));
        });
})->add(requiereUsuario());