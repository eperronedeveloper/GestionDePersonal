<?php

use Slim\Http\Request as Request;
use Slim\Http\Response as Response;

class FichadasController extends Controller
{  
/* ===================================== VISTAS ============================================= */
    public function renderFichadas(Request $request, Response $response, $args)
    {
        $data = [
            'titulo_nav' => 'FICHADAS',
            'title' => 'Fichadas',
            "baseUrl" => BASEPATH
        ];
        
        $this->view->render($response, 'fichadas/fichadas.twig', $data);
        return $response;
    }

    public function renderCrearFichadas(Request $request, Response $response)
    {   
        $data = [
            'titulo_modal' => 'Crear Fichada',
            'baseUrl' => BASEPATH,
        ];

        $this->view->render($response, 'fichadas/fichadas_detalle.twig', $data);
        return $response;
    }

    public function renderDetalleFichadas(Request $request, Response $response)
    {
        $idFichada = $request->getParam('idFichada', null);
        $fm = new FichadasModel();
        $fichada = $fm->detalleFichada($idFichada);
        // if($fichada && $fichada['estado_fichada'] == ESTADO_INACTIVO)
            // return $response->withMsgError('NO SE PUEDE EDITAR UNA FICHADA INACTIVA');
        
        $data = [
            'titulo_modal' => 'Detalle Fichada',
            'baseUrl' => BASEPATH,
            'fichada' => $fichada
        ];

        $this->view->render($response, 'fichadas/fichadas_detalle.twig', $data);
        return $response;
    }

    /* ===================================== API ============================================= */

public function listarFichadas(Request $request, Response $response)
{
    $fm = new FichadasModel();
    $lista = $fm->listarFichadas();
    
    $data = [
        'baseUrl' => BASEPATH,
        'data' => $lista
    ];
    
    return $response->withJson($data);
}

public function crearFichada(Request $request, Response $response) {
    $datos = $request->getParam('datos', null);
    $fm = new FichadasModel();
    foreach($datos as $fichada){
        if(emptyValues($fichada)) return $response->withMsgError('DEBE COMPLETAR TODOS LOS DATOS');

        $existeFichada = $fm->detalleFichada($fichada);
        if($existeFichada) return $response->withMsgError($existeFichada);
    }
    
    try {
        $fm->_beginTransaction();
            foreach($datos as $fichada){
                $fm->crearFichada($fichada);
            }
        $fm->_commit();
    } catch (Exception $ex) {
        $fm->_rollBack();
        if(DEBUG) throw $ex;
        return $response->withMsgError("ERROR AL GUARDAR");
    }
    return $response->withJson(['mensaje' => 'GUARDADO CON EXITO']);
}

public function eliminarFichada(Request $request, Response $response, $args) {        
    $legajo = $args['legajo'];
    $datos = $request->getParams();
    (new FichadasModel())->cambiarEstadoRegistroFichada('fichadas', 'legajo', 'fecha_fichada', 'tipo_fichada', $legajo, $datos['fecha_fichada'], $datos['tipo_fichada'], 'estado_fichada', ESTADO_BAJA);
    return $response->withJson(['mensaje' => 'ELIMINADO CORRECTAMENTE']);
}

/*===================================== METODOS EXTRA =============================================*/

public function ACLegajo(Request $request, Response $response, $args) {

    $datos = $request->getParam('descripcion', null);
    $listaLegajos = (new FichadasModel())->ACLegajo($datos);
    if ($listaLegajos) {
        foreach ($listaLegajos as $agente) {
            $arrayAgente[] = array(
                "id" => $agente["legajo"],
                "value" => $agente['legajo'] . ' - ' . $agente["nombre_agente"] . ', ' . $agente["apellido_agente"],
                "nombre_agente" => $agente["nombre_agente"],
                "apellido_agente" => $agente["apellido_agente"]
            );
        }
        return $response->withJson($arrayAgente);
    } else {
        return $response->withJson(['mensaje' => 'SIN RESULTADOS']);
    }
}

//public function editarFichada(Request $request, Response $response, $args) {
    // $datos = $request->getParams();
    // $idFichada = $args['idFichada'];
// 
    // if (emptyValues($datos['descripcion']))
        // return $response->withMsgError('COMPLETAR DESCRIPCIÓN DE FICHADA');
// 
        // $fm = new FichadasModel();
        // $fm->editarFichada($idFichada, $datos);
        // $fm->_commit();
        // return $response->withJson(['mensaje' => 'GUARDADO CON EXITO']);
// }

// public function eliminarFichada(Request $request, Response $response, $args) {        
    // $idFichada = $args['idFichada'];
    // $fm = new FichadasModel();
    // $fm->cambiarEstadoRegistro('fichadas', 'id_fichada', $idFichada, 'estado_fichada', ESTADO_BAJA);
    // return $response->withJson(['mensaje' => 'ELIMINADO CORRECTAMENTE']);
// }


}


