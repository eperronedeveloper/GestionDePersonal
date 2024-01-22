<?php

use Slim\Http\Request as Request;
use Slim\Http\Response as Response;

class HomeController extends Controller
{
    public function renderInicio(Request $request, Response $response, $args)
    {
        $data = [
            'title' => 'Inicio',
            "baseUrl" => BASEPATH,
            "botonera" => BOTONES_PORTADA
        ];
        
        if(!LOGUEADO){
            $this->view->render($response, 'login.twig', $data);
        }else{
            $this->view->render($response, 'portada.twig', $data);
        }
        
        return $response;
    }

    public function renderPortada(Request $request, Response $response, $args)
    {
        $data = [
            "title" => 'Portada',
            "baseUrl" => BASEPATH,
            "botonera" => BOTONES_PORTADA
        ];
        
        $this->view->render($response, 'portada.twig', $data);
        return $response;
    }

    public function renderCatalogos(Request $request, Response $response)
    {
        
        $data = [
            'titulo_nav' => 'CATALOGOS',
            'title' => 'Catalogos',
            'baseUrl' => BASEPATH,            
            'portada' => URL_PORTADA,
            "botonera" => BOTONES_CATALOGOS,
        ];

        $this->view->render($response, 'catalogos/catalogos.twig', $data);
        return $response;
    }

    
    function renderPaginaError(Request $request, Response $response, $args){
        $data = 
        [
            'titulo_nav' => 'Error 404',
            'title' => 'Error 404',
            'mensaje' => 'Pagina no encontrada',
            'indexUrl' => URL_PORTADA
        ];
        
        $this->view->render($response, 'embeds/error/error404.twig', $data);
        return $response;
    }

    public function actualizarSesion(Request $request, Response $response, $args)
    {
        $idUsuario = $request->getParam('idUsuario', null);
        $apiUrl = COREPATH . '/api/usuarios/actualizarSesion';
        $query = [
            'idUsuario' => $idUsuario,
            'permisos' => USUARIO_PERMISOS,
            'schema' => DB_SCHEMA_POSTGRES
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
            'X-API-KEY:' . API_KEY
        ]);
        curl_setopt($ch, CURLOPT_HEADER, 0); 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
         
        $res = curl_exec($ch);
        
        if ($res === false) {
            // Manejo de errores
            echo "Error al realizar la solicitud: " . curl_error($ch);
        } else {
            // Manejo de la respuesta (JSON)
            $data = json_decode($res);
            
            if ($data->error) {
                curl_close($ch);
                return $response->withMsgError($data->error);
            }
        }
        
    curl_close($ch);
    $_SESSION['PERMISOS'] = $data->permisos;
    return $response->withJson($data);
    }

    /*
    public function Get(Request $request, Response $response, $args)
    {
        $idUsuario = $request->getParam('idUsuario', null);
        $apiUrl = COREPATH . '/roles/listar-roles';
        $query = [
            'idUsuario' => $idUsuario,
            'permisos' => USUARIO_PERMISOS,
            'schema' => DB_SCHEMA_POSTGRES
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
            'X-API-KEY:' . API_KEY
        ]);
        curl_setopt($ch, CURLOPT_HEADER, 0); 
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
        
        $res = curl_exec($ch);
        
        if ($res === false) {
            // Manejo de errores
            echo "Error al realizar la solicitud: " . curl_error($ch);
        } else {
            // Manejo de la respuesta (JSON)
            $data = json_decode($res);
            
            if ($data->error) {
                curl_close($ch);
                return $response->withMsgError($data->error);
            }
        }
        
    curl_close($ch);
    return $response->withJson($data);
    }
    */

    
    public function logout(Request $request, Response $response)
    {
        session_unset();
        session_destroy();
    }
    
}
