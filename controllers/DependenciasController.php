<?php

use Slim\Http\Request as Request;
use Slim\Http\Response as Response;

class DependenciasController extends Controller
{  
/* ===================================== VISTAS ============================================= */
    public function renderDependencias(Request $request, Response $response, $args)
    {
        $data = [
            'titulo_nav' => 'DEPENDENCIAS',
            'title' => 'Dependencias',
            "baseUrl" => BASEPATH
        ];
        
        $this->view->render($response, 'dependencias/dependencias.twig', $data);
        return $response;
    }

    public function renderCrearDependencias(Request $request, Response $response)
    {   
        $data = [
            'titulo_modal' => 'Crear Dependencia',
            'baseUrl' => BASEPATH,
        ];

        $this->view->render($response, 'dependencias/dependencias_detalle.twig', $data);
        return $response;
    }

    public function renderDetalleDependencias(Request $request, Response $response)
    {
        $idDependencia = $request->getParam('idDependencia', null);
        $dm = new DependenciasModel();
        $dependencia = $dm->detalleDependencia($idDependencia);
        if($dependencia && $dependencia['estado_dependencia'] == ESTADO_INACTIVO)
            return $response->withMsgError('NO SE PUEDE EDITAR UNA DEPENDENCIA INACTIVA');
        
        $data = [
            'titulo_modal' => 'Detalle Dependencia',
            'baseUrl' => BASEPATH,
            'dependencia' => $dependencia
        ];

        $this->view->render($response, 'dependencias/dependencias_detalle.twig', $data);
        return $response;
    }

    /* ===================================== API ============================================= */

public function listarDependencias(Request $request, Response $response)
{
    $dm = new DependenciasModel();
    $lista = $dm->listarDependencias();
    
    $data = [
        'baseUrl' => BASEPATH,
        'data' => $lista
    ];
    
    return $response->withJson($data);
}

public function crearDependencia(Request $request, Response $response) {
    $datos = $request->getParams();

    
    
    if (emptyValues($datos['descripcion']))
        return $response->withMsgError('COMPLETAR DESCRIPCIÓN DE DEPENDENCIA');

    $dm = new DependenciasModel();
    $existeDependencia = $dm->validarDependencia($datos); 
         
    if($existeDependencia){
        if($existeDependencia["estado_dependencia"] == ESTADO_ACTIVO){
            return $response->withMsgError('DEPENDENCIA INGRESADA YA EXISTE');
        }            
        if($existeDependencia["estado_dependencia"] == ESTADO_INACTIVO){
            return $response->withMsgError('DEPENDENCIA INGRESADA YA EXISTE PERO SE ENCUENTRA INACTIVA, CONSULTE CON EL ADMINISTRADOR DEL SISTEMA');
        }
    }    
    $dm->crearDependencia($datos);
    return $response->withJson(['mensaje' => 'GUARDADO CON EXITO']);
}

public function editarDependencia(Request $request, Response $response, $args) {
    $datos = $request->getParams();
    $idDependencia = $args['idDependencia'];

    if (emptyValues($datos['descripcion']))
        return $response->withMsgError('COMPLETAR DESCRIPCIÓN DE DEPENDENCIA');
    
    try {
        $dm = new DependenciasModel();
        $dm->_beginTransaction();
        $existeDependencia = $dm->validarDependencia($datos); 
        if($existeDependencia && $existeDependencia["ID_DEPENDENCIA"] != $idDependencia){
            if($existeDependencia["estado_dependencia"] == ESTADO_ACTIVO){
                return $response->withMsgError('DEPENDENCIA INGRESADA YA EXISTE');
            }
            if($existeDependencia["estado_dependencia"] == ESTADO_INACTIVO){
                return $response->withMsgError('DEPENDENCIA INGRESADA YA EXISTE PERO SE ENCUENTRA INACTIVA, CONSULTE CON EL ADMINISTRADOR DEL SISTEMA');
            }
        }
        $dm->editarDependencia($idDependencia, $datos);
        $dm->_commit();
        return $response->withJson(['mensaje' => 'GUARDADO CON EXITO']);
    } catch (Exception $ex) {
        $dm->_rollBack();
        if(DEBUG) throw $ex;
        return $response->withMsgError("ERROR AL GUARDAR");
    }
}

public function eliminarDependencia(Request $request, Response $response, $args) {        
    $idDependencia = $args['idDependencia'];
    $dm = new DependenciasModel();
    $dm->cambiarEstadoRegistro('dependencias', 'id_dependencia', $idDependencia, 'estado_dependencia', ESTADO_BAJA);
    return $response->withJson(['mensaje' => 'ELIMINADO CORRECTAMENTE']);
}


}


