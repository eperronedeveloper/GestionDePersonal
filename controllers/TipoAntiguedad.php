<?php

use Slim\Http\Request as Request;
use Slim\Http\Response as Response;

class TipoAntiguedadController extends Controller
{  
/* ===================================== VISTAS ============================================= */
    public function renderTipoAntiguedad(Request $request, Response $response, $args)
    {
        $data = [
            'titulo_nav' => 'TIPO ANTIGUEDAD',
            'title' => 'Tipo Antiguedad',
            "baseUrl" => BASEPATH
        ];
        
        $this->view->render($response, 'catalogos/tipoAntiguedad/tipoAntiguedad.twig', $data);
        return $response;
    }

    public function renderCrearTipoAntiguedad(Request $request, Response $response)
    {   
        $data = [
            'titulo_modal' => 'Crear Tipo Antiguedad',
            'baseUrl' => BASEPATH,
        ];

        $this->view->render($response, 'catalogos/tipoAntiguedad/tipoAntiguedad_detalle.twig', $data);
        return $response;
    }

    public function renderDetalleTipoAntiguedad(Request $request, Response $response)
    {
        $idTipoAntiguedad = $request->getParam('idTipoAntiguedad', null);
        $tam = new TipoAntiguedadModel();
        $tipoAntiguedad = $tam->detalleTipoAntiguedad($idTipoAntiguedad);
        if($tipoAntiguedad && $tipoAntiguedad['estado_tipoantiguedad'] == ESTADO_INACTIVO)
            return $response->withMsgError('NO SE PUEDE EDITAR UN TIPO DE ANTIGUEDAD INACTIVO');
        
        $data = [
            'titulo_modal' => 'Detalle Tipo Antiguedad',
            'baseUrl' => BASEPATH,
            'tipoAntiguedad' => $tipoAntiguedad
        ];

        $this->view->render($response, 'catalogos/tipoAntiguedad/tipoAntiguedad_detalle.twig', $data);
        return $response;
    }

    /* ===================================== API ============================================= */

public function listarTipoAntiguedad(Request $request, Response $response)
{
    $tam = new TipoAntiguedadModel();
    $lista = $tam->listarTipoAntiguedad();
    
    $data = [
        'baseUrl' => BASEPATH,
        'data' => $lista
    ];
    
    return $response->withJson($data);
}

public function crearTipoAntiguedad(Request $request, Response $response) {
    $datos = $request->getParams();
    
    if (emptyValues($datos['desc_tipo_ant']))
        return $response->withMsgError('COMPLETAR DESCRIPCIÓN DE TIPO DE ANTIGUEDAD');

    $tam = new TipoAntiguedadModel();
    $existeTipoAntiguedad = $tam->validarTipoAntiguedad($datos); 
         
    if($existeTipoAntiguedad){
        if($existeTipoAntiguedad["estado_tipoantiguedad"] == ESTADO_ACTIVO){
            return $response->withMsgError('TIPO ANTIGUEDAD INGRESADO YA EXISTE');
        }            
        if($existeTipoAntiguedad["estado_tipoantiguedad"] == ESTADO_INACTIVO){
            return $response->withMsgError('TIPO ANTIGUEDAD INGRESADO YA EXISTE PERO SE ENCUENTRA INACTIVO, CONSULTE CON EL ADMINISTRADOR DEL SISTEMA');
        }
    }    
    $tam->crearTipoAntiguedad($datos);
    return $response->withJson(['mensaje' => 'GUARDADO CON EXITO']);
}

public function editarTipoAntiguedad(Request $request, Response $response, $args) {
    $datos = $request->getParams();
    $idTipoAntiguedad = $args['idTipoAntiguedad'];

    if (emptyValues($datos['desc_tipo_ant']))
        return $response->withMsgError('COMPLETAR DESCRIPCIÓN DE TIPO DE ANTIGUEDAD');
    
    try {
        $tam = new TipoAntiguedadModel();
        $tam->_beginTransaction();
        $existeTipoAntiguedad = $tam->validarTipoAntiguedad($datos); 
        if($existeTipoAntiguedad && $existeTipoAntiguedad["id_tipo_ant"] != $idTipoAntiguedad){
            if($existeTipoAntiguedad["estado_tipoantiguedad"] == ESTADO_ACTIVO){
                return $response->withMsgError('TIPO ANTIGUEDAD INGRESADA YA EXISTE');
            }
            if($existeTipoAntiguedad["estado_tipoantiguedad"] == ESTADO_INACTIVO){
                return $response->withMsgError('TIPO ANTIGUEDAD INGRESADA YA EXISTE PERO SE ENCUENTRA INACTIVA, CONSULTE CON EL ADMINISTRADOR DEL SISTEMA');
            }
        }
        $tam->editarTipoAntiguedad($idTipoAntiguedad, $datos);
        $tam->_commit();
        return $response->withJson(['mensaje' => 'GUARDADO CON EXITO']);
    } catch (Exception $ex) {
        $tam->_rollBack();
        if(DEBUG) throw $ex;
        return $response->withMsgError("ERROR AL GUARDAR");
    }
}

public function eliminarTipoAntiguedad(Request $request, Response $response, $args) {        
    $idTipoAntiguedad = $args['idTipoAntiguedad'];
    $tam = new TipoAntiguedadModel();
    $tam->cambiarEstadoRegistro('tipo_antiguedad', 'id_tipo_ant', $idTipoAntiguedad, 'estado_tipoantiguedad', ESTADO_BAJA);
    return $response->withJson(['mensaje' => 'ELIMINADO CORRECTAMENTE']);
}


}


