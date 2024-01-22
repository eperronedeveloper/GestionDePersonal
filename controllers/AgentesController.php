<?php

use Slim\Http\Request as Request;
use Slim\Http\Response as Response;

class AgentesController extends Controller
{  
/* ===================================== VISTAS ============================================= */
    public function renderAgentes(Request $request, Response $response, $args)
    {
        $data = [
            'titulo_nav' => 'AGENTES',
            'title' => 'Agentes',
            "baseUrl" => BASEPATH
        ];
        
        $this->view->render($response, 'agentes/agentes.twig', $data);
        return $response;
    }

    public function renderCrearAgentes(Request $request, Response $response)
    {   
        $data = [
            'titulo_modal' => 'Crear Agente',
            'baseUrl' => BASEPATH,
            'accion' => 'crear'
        ];

        $this->view->render($response, 'agentes/agentes_detalle.twig', $data);
        return $response;
    }

    public function renderDetalleAgentes(Request $request, Response $response)
    {
        $legajo = $request->getParam('legajo', null);
        $am = new AgentesModel();
        $agente = $am->detalleAgente($legajo);
        if($agente && $agente['estado_agente'] == ESTADO_INACTIVO)
            return $response->withMsgError('NO SE PUEDE EDITAR UN AGENTE INACTIVO');
        
        $data = [
            'titulo_modal' => 'Detalle Agente',
            'baseUrl' => BASEPATH,
            'agente' => $agente,
            'accion' => 'editar'
        ];

        $this->view->render($response, 'agentes/agentes_detalle.twig', $data);
        return $response;
    }

    /* ===================================== API ============================================= */

public function listarAgentes(Request $request, Response $response)
{
    $am = new AgentesModel();
    $lista = $am->listarAgentes();
    
    $data = [
        'baseUrl' => BASEPATH,
        'data' => $lista
    ];
    
    return $response->withJson($data);
}

public function crearAgente(Request $request, Response $response) {
    $datos = $request->getParam('datos', null);

    if ($datos['foto_agente'] != null && strpos($datos['foto_agente'], $datos['legajo']) === false) {
        $datos['foto_agente'] = $datos['legajo'] . '_' . $datos['foto_agente'];
    }else{
        $datos['foto_agente'] = 'agente_sin_foto.jpg'; 
    }

    $am = new AgentesModel();
    $existeAgente = $am->validarAgente($datos);

    if($existeAgente){
        if($existeAgente["estado_agente"] == ESTADO_ACTIVO){
            return $response->withMsgError('AGENTE INGRESADO YA EXISTE');
        }            
        if($existeAgente["estado_agente"] == ESTADO_INACTIVO){
            return $response->withMsgError('AGENTE INGRESADO YA EXISTE PERO SE ENCUENTRA INACTIVO, CONSULTE CON EL ADMINISTRADOR DEL SISTEMA');
        }
    }
    
try {
        $am->_beginTransaction();
        $am->crearAgente($datos);
        $am->_commit();
        return $response->withJson(['mensaje' => 'GUARDADO CON EXITO']);
    } catch (Exception $ex) {
        $am->_rollBack();
        if(DEBUG) throw $ex;
        return $response->withMsgError("ERROR AL GUARDAR");
    }
}

public function editarAgente(Request $request, Response $response, $args) {
    $datos = $request->getParam('datos', null);
    $legajo = $args['legajo'];
    
    // if (($datos['foto_agente'] != null && strpos($datos['foto_agente'], $datos['legajo']) === false)){
    //     if($datos['foto_agente'] != 'agente_sin_foto.jpg'){
    //         $datos['foto_agente'] = $datos['legajo'] . '_' . $datos['foto_agente'];  
    //     }else{
    //         $datos['foto_agente'] = 'agente_sin_foto.jpg';    
    //     }
    // }
    
    if($datos['foto_agente'] == 'agente_sin_foto.jpg' && strpos($datos['foto_agente'], $datos['legajo']) === true){
        $datos['foto_agente'] = 'agente_sin_foto.jpg';    
    }
    
    if($datos['foto_agente'] == null)$datos['foto_agente'] = 'agente_sin_foto.jpg';
    
    if (emptyValues($datos))
        return $response->withMsgError('DEBE COMPLETAR TODOS LOS DATOS');
    
    try {
        $dm = new AgentesModel();
        $dm->_beginTransaction();
        $existeAgente = $dm->validarAgente($datos); 
        if($existeAgente && $existeAgente["legajo"] != $legajo){
            if($existeAgente["estado_agente"] == ESTADO_ACTIVO){
                return $response->withMsgError('Agente INGRESADA YA EXISTE');
            }
            if($existeAgente["estado_agente"] == ESTADO_INACTIVO){
                return $response->withMsgError('AGENTE INGRESADO YA EXISTE PERO SE ENCUENTRA INACTIVO, CONSULTE CON EL ADMINISTRADOR DEL SISTEMA');
            }
        }
        $dm->editarAgente($datos);
        $dm->_commit();
        return $response->withJson(['mensaje' => 'GUARDADO CON EXITO']);
    } catch (Exception $ex) {
        $dm->_rollBack();
        if(DEBUG) throw $ex;
        return $response->withMsgError("ERROR AL GUARDAR");
    }
}

public function subirDocumentacion(Request $request, Response $response) {

    $datos = $request->getParams();
    $datosAgente = json_decode($datos['agente']);
    $agente = (array) $datosAgente;
    $am = new AgentesModel();
    $existeAgente = $am->validarAgente($agente); 
    
    if($existeAgente){
        if($existeAgente["estado_agente"] == ESTADO_ACTIVO){
            return $response->withMsgError('AGENTE INGRESADO YA EXISTE');
        }            
        if($existeAgente["estado_agente"] == ESTADO_INACTIVO){
            return $response->withMsgError('AGENTE INGRESADO YA EXISTE PERO SE ENCUENTRA INACTIVO, CONSULTE CON EL ADMINISTRADOR DEL SISTEMA');
        }
    }
    
    $filename = $agente['legajo'] . '_' . $_FILES['file']['name'];

    if(isset($_FILES["file"]["name"])){
        $location = "uploads/fotoAgentes/" . $filename;
        $filesize = $_FILES['file']['size'];
        $max_size = 1000000; // 1mb

        if ($filesize > $max_size) {   
            return $response->withMsgError('LA FOTO NO DEBE SUPERAR 1MB.');
        }

        // Extension
        $extension = pathinfo($location, PATHINFO_EXTENSION);
        $extension = strtolower($extension);

        $acceptfile_ext = array("jpeg","jpg","png");

        if(in_array($extension, $acceptfile_ext)){
            move_uploaded_file($_FILES["file"]["tmp_name"], $location);
        }else{
            return $response->withMsgError('LA EXTENSIÓN DEL ARCHIVO DEBE SER JPG, PNG O JPEG.');
        }
    }
}


public function cargarDocumentacion(Request $request, Response $response) {
    $fotoAgente = $request->getParam('foto_agente', null);
    $file_list = array();
    $dir = "uploads/fotoAgentes/";
    if (is_dir($dir)){
        if ($dh = opendir($dir)){
            while (($file = readdir($dh)) !== false){
                if($file == $fotoAgente && $file != '.' && $file != '..'){
                    $extension = pathinfo($file, PATHINFO_EXTENSION);
                    $file_path = $dir.$file;
                    if(!is_dir($file_path)){
                        $size = filesize($file_path);
                        $file_list[] = array('name'=>$file,'size'=>$size,'path'=>$file_path, 'type' => 'image/' . $extension);
                    }
                }
            }
           closedir($dh);
        }
    }
    return $response->withJson($file_list);
}

public function eliminarAgente(Request $request, Response $response, $args) {
    $legajo = $args['legajo'];
    $am = new AgentesModel();
    $am->cambiarEstadoRegistro('agentes', 'legajo', $legajo, 'estado_agente', ESTADO_BAJA);
    return $response->withJson(['mensaje' => 'ELIMINADO CORRECTAMENTE']);
}



/*===================================== METODOS EXTRA =============================================*/

public function ACPais(Request $request, Response $response, $args) {

    $datos = $request->getParam('descripcion', null);
    $am = new AgentesModel();
    $listaPaises = $am->ACPais($datos);

    if ($listaPaises) {
        foreach ($listaPaises as $cf) {
            $arraycf[] = array(
                "id" => $cf["id_pais"],
                "value" => $cf["nombre_pais"]
            );
        }
        return $response->withJson($arraycf);
    } else {
        return $response->withJson(['mensaje' => 'SIN RESULTADOS']);
    }
}

public function ACProvincia(Request $request, Response $response, $args) {

    $idPais = $request->getParam('id_pais', null);
    $datos = $request->getParam('descripcion', null);
    $am = new AgentesModel();
    $listaPaises = $am->ACProvincia($idPais, $datos);

    if ($listaPaises) {
        foreach ($listaPaises as $cf) {
            $arraycf[] = array(
                "id" => $cf["id_provincia"],
                "value" => $cf["nombre_provincia"]
            );
        }
        return $response->withJson($arraycf);
    } else {
        return $response->withJson(['mensaje' => 'SIN RESULTADOS']);
    }
}


}


