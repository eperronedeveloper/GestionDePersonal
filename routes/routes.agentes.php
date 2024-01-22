<?php

$app->group('/agentes', function () use ($app) {
    $app->get('', 'AgentesController:renderAgentes')->add(requierePermiso('PERSONAL_AGENTES'));
    $app->post('/crear', 'AgentesController:renderCrearAgentes')->add(requierePermiso('PERSONAL_AGENTES.GUARDAR'));
    $app->post('/detalle', 'AgentesController:renderDetalleAgentes')->add(requierePermiso('PERSONAL_AGENTES.DETALLE'));
    $app->get('/listar', 'AgentesController:listarAgentes')->add(requierePermiso('PERSONAL_AGENTES'));
    $app->post('/ACPais', 'AgentesController:ACPais')->add(requierePermiso('PERSONAL_AGENTES.DETALLE'));
    $app->post('/ACProvincia', 'AgentesController:ACProvincia')->add(requierePermiso('PERSONAL_AGENTES.DETALLE'));
})->add(requiereUsuario());

$app->group('/api/agentes', function () use ($app) {
    $app->post('/crear', 'AgentesController:crearAgente')->add(requierePermiso('PERSONAL_AGENTES.GUARDAR'));
    $app->post('/subirDocumentacion', 'AgentesController:subirDocumentacion')->add(requierePermiso('PERSONAL_AGENTES.GUARDAR'));
    $app->post('/cargarDocumentacion', 'AgentesController:cargarDocumentacion')->add(requierePermiso('PERSONAL_AGENTES.GUARDAR'));
        $app->group('/{legajo}', function () use ($app) {
            $app->post(':cambiarEstado', 'AgentesController:cambiarEstadoAgente')->add(requierePermiso('PERSONAL_AGENTES.CAMBIAR_ESTADO'));
            $app->put('/editar', 'AgentesController:editarAgente')->add(requierePermiso('PERSONAL_AGENTES.GUARDAR'));
            $app->delete('/eliminar', 'AgentesController:eliminarAgente')->add(requierePermiso('PERSONAL_AGENTES.ELIMINAR'));
        });
})->add(requiereUsuario());