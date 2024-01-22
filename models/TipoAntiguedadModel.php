<?php

class TipoAntiguedadModel extends Model {

        function __construct() {
            parent::__construct(FADU_PERSONAL);
        }
    /* ======================================= LISTAS ======================================= */

    public function listarTipoAntiguedad() {
            $query = "SELECT 
                ta.id_tipo_ant,
                ta.desc_tipo_ant,
                ta.estado_tipoantiguedad
            FROM 
                " . SCHEMA_FADU_PERSONAL . ".tipo_antiguedad ta
            WHERE ta.estado_tipoantiguedad = ESTADO_ACTIVO
            ORDER BY ta.desc_tipo_ant ASC ";
            
            $result = $this->_db->prepare($query);
    
            $result->execute();
            return $result->fetchAll();
            
    }

    public function detalleTipoAntiguedad($id) {        
            $query = "SELECT
                ta.id_tipo_ant,
                ta.desc_tipo_ant,
                ta.estado_tipoantiguedad
            FROM " . SCHEMA_FADU_PERSONAL . ".tipo_antiguedad ta
            WHERE ta.id_tipo_ant = :id AND ta.estado_tipoantiguedad = ESTADO_ACTIVO";

            $result = $this->_db->prepare($query);

            $result->bindValue(":id", $id);

            $result->execute();
            return $result->fetch();
    }

    /* ======================================= ABM ======================================= */

    public function crearTipoAntiguedad($datos) {
        $query = 'INSERT INTO
            ' . SCHEMA_FADU_PERSONAL . '.tipo_antiguedad
            (desc_tipo_ant, estado_tipoantiguedad)
            VALUES
            (:desc_tipo_ant, :estado_tipoantiguedad)';

            $result = $this->_db->prepare($query);            

            $result->bindValue(":desc_tipo_ant", clean($datos['desc_tipo_ant']));
            $result->bindValue(":estado_tipoantiguedad", ESTADO_ACTIVO);
            return $result->execute();
    }

    public function editarTipoAntiguedad($idTipoAntiguedad, $datos) {        
            $query = 'UPDATE
                ' . SCHEMA_FADU_PERSONAL . '.tipo_antiguedad
            SET desc_tipo_ant = :desc_tipo_ant
            WHERE id_tipo_ant = :idTipoAntiguedad';

            $result = $this->_db->prepare($query);

            $result->bindValue(":idTipoAntiguedad", clean($idTipoAntiguedad));
            $result->bindValue(":desc_tipo_ant", clean($datos['desc_tipo_ant']));
            return $result->execute();
    }

    /* ======================================= METODOS EXTRA ======================================= */
    
    public function validarTipoAntiguedad($datos){
        
            $query = "SELECT 
                d.id_tipo_ant,
                d.desc_tipo_ant,
                d.estado_tipoantiguedad
            FROM 
                " . SCHEMA_FADU_PERSONAL . ".tipo_antiguedad d
            WHERE d.desc_tipo_ant = :descripcion AND d.estado_tipoantiguedad <> ESTADO_BAJA";

            $result = $this->_db->prepare($query);

            $result->bindValue(":descripcion", $datos['desc_tipo_ant']);
            return $result->execute();
    }
    
}