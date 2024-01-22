<?php

use Slim\Http\Request as Request;
use Slim\Http\Response as Response;

class CargosController extends Controller
{  
/* ===================================== VISTAS ============================================= */
    public function renderCargos(Request $request, Response $response, $args)
    {
        $data = [
            'titulo_nav' => 'CARGOS',
            'title' => 'Cargos',
            "baseUrl" => BASEPATH
        ];
        
        $this->view->render($response, 'catalogos/cargos/cargos.twig', $data);
        return $response;
    }

    public function renderCrearCargos(Request $request, Response $response)
    {   
        $data = [
            'titulo_modal' => 'Crear Cargo',
            'baseUrl' => BASEPATH,
        ];

        $this->view->render($response, 'catalogos/cargos/cargos_detalle.twig', $data);
        return $response;
    }

    public function renderDetalleCargos(Request $request, Response $response)
    {
        $idCargo = $request->getParam('idCargo', null);
        $cm = new CargosModel();
        $cargo = $cm->detalleCargo($idCargo);
        if($cargo && $cargo['estado_cargo'] == ESTADO_INACTIVO)
            return $response->withMsgError('NO SE PUEDE EDITAR UN CARGO INACTIVO');
        
        $data = [
            'titulo_modal' => 'Detalle Cargo',
            'baseUrl' => BASEPATH,
            'cargo' => $cargo
        ];

        $this->view->render($response, 'catalogos/cargos/cargos_detalle.twig', $data);
        return $response;
    }

    /* ===================================== API ============================================= */

public function listarCargos(Request $request, Response $response)
{
    $cm = new CargosModel();
    $lista = $cm->listarCargos();
    
    $data = [
        'baseUrl' => BASEPATH,
        'data' => $lista
    ];
    
    return $response->withJson($data);
}

public function crearCargo(Request $request, Response $response) {
    $datos = $request->getParams();
    
    if (emptyValues($datos['desc_cargo']))
        return $response->withMsgError('COMPLETAR DESCRIPCIÓN DE CARGO');

    $cm = new CargosModel();
    $existeCargo = $cm->validarCargo($datos); 
         
    if($existeCargo){
        if($existeCargo["estado_cargo"] == ESTADO_ACTIVO){
            return $response->withMsgError('CARGO INGRESADO YA EXISTE');
        }            
        if($existeCargo["estado_cargo"] == ESTADO_INACTIVO){
            return $response->withMsgError('CARGO INGRESADO YA EXISTE PERO SE ENCUENTRA INACTIVO, CONSULTE CON EL ADMINISTRADOR DEL SISTEMA');
        }
    }    
    $cm->crearCargo($datos);
    return $response->withJson(['mensaje' => 'GUARDADO CON EXITO']);
}

public function editarCargo(Request $request, Response $response, $args) {
    $datos = $request->getParams();
    $idCargo = $args['idCargo'];

    if (emptyValues($datos['desc_cargo']))
        return $response->withMsgError('COMPLETAR DESCRIPCIÓN DE CARGO');
    
    try {
        $cm = new CargosModel();
        $cm->_beginTransaction();
        $existeCargo = $cm->validarCargo($datos); 
        if($existeCargo && $existeCargo["id_cargo"] != $idCargo){
            if($existeCargo["estado_cargo"] == ESTADO_ACTIVO){
                return $response->withMsgError('CARGO INGRESADA YA EXISTE');
            }
            if($existeCargo["estado_cargo"] == ESTADO_INACTIVO){
                return $response->withMsgError('CARGO INGRESADA YA EXISTE PERO SE ENCUENTRA INACTIVA, CONSULTE CON EL ADMINISTRADOR DEL SISTEMA');
            }
        }
        $cm->editarCargo($idCargo, $datos);
        $cm->_commit();
        return $response->withJson(['mensaje' => 'GUARDADO CON EXITO']);
    } catch (Exception $ex) {
        $cm->_rollBack();
        if(DEBUG) throw $ex;
        return $response->withMsgError("ERROR AL GUARDAR");
    }
}

public function eliminarCargo(Request $request, Response $response, $args) {        
    $idCargo = $args['idCargo'];
    $cm = new CargosModel();
    $cm->cambiarEstadoRegistro('cargos', 'id_cargo', $idCargo, 'estado_cargo', ESTADO_BAJA);
    return $response->withJson(['mensaje' => 'ELIMINADO CORRECTAMENTE']);
}


}


