<?php

class DependenciasModel extends Model {

        function __construct() {
            parent::__construct(FADU_PERSONAL);
        }
    /* ======================================= LISTAS ======================================= */

    public function listarDependencias() {
        
            $query = "SELECT 
                d.id_dependencia,
                d.desc_dependencia,
                d.ubicacion,
                CASE WHEN d.interno IS NULL or d.interno = ''
                THEN '-'
                ELSE d.interno
                END AS interno,
                d.estado_dependencia
            FROM 
                " . SCHEMA_FADU_PERSONAL . ".dependencias d
            WHERE d.estado_dependencia = ESTADO_ACTIVO
            ORDER BY d.desc_dependencia ASC ";
            
            $result = $this->_db->prepare($query);
    
            $result->execute();
            return $result->fetchAll();
            
    }

    public function detalleDependencia($id) {
        
            $query = "SELECT
                d.id_dependencia,
                d.desc_dependencia,
                d.interno,
                d.ubicacion,
                d.estado_dependencia
            FROM " . SCHEMA_FADU_PERSONAL . ".dependencias d
            WHERE d.id_dependencia = :id AND d.estado_dependencia = ESTADO_ACTIVO";

            $result = $this->_db->prepare($query);

            $result->bindValue(":id", $id);

            $result->execute();
            return $result->fetch();
    }

    /* ======================================= ABM ======================================= */

    public function crearDependencia($datos) {
        $query = 'INSERT INTO
            ' . SCHEMA_FADU_PERSONAL . '.dependencias
            (desc_dependencia, interno, ubicacion, estado_dependencia)
            VALUES
            (:descripcion, :interno, :ubicacion, :estado_dependencia)';

            $result = $this->_db->prepare($query);            

            $result->bindValue(":descripcion", clean($datos['descripcion']));
            $result->bindValue(":interno", clean($datos['interno']));
            $result->bindValue(":ubicacion", clean($datos['ubicacion']));
            $result->bindValue(":estado_dependencia", ESTADO_ACTIVO);
            return $result->execute();
    }

    public function editarDependencia($idDependencia, $datos) {
        
        
            $query = 'UPDATE
                ' . SCHEMA_FADU_PERSONAL . '.dependencias
            SET desc_dependencia = :descripcion,
            interno = :interno,
            ubicacion = :ubicacion
            WHERE id_dependencia = :idDependencia';

            $result = $this->_db->prepare($query);

            $result->bindValue(":idDependencia", clean($idDependencia));
            $result->bindValue(":descripcion", clean($datos['descripcion']));
            $result->bindValue(":interno", clean($datos['interno']));
            $result->bindValue(":ubicacion", clean($datos['ubicacion']));
            return $result->execute();
    }

    /* ======================================= METODOS EXTRA ======================================= */
    
    public function validarDependencia($datos){
        
            $query = "SELECT 
                d.id_dependencia,
                d.desc_dependencia,
                d.interno,
                d.ubicacion,
                d.estado_dependencia
            FROM 
                " . SCHEMA_FADU_PERSONAL . ".dependencias d
            WHERE d.desc_dependencia = :descripcion AND d.estado_dependencia <> ESTADO_BAJA";

            $result = $this->_db->prepare($query);

            $result->bindValue(":descripcion", $datos['descripcion']);
            return $result->execute();
    }
    
}