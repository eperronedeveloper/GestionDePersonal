<?php

class CargosModel extends Model {

        function __construct() {
            parent::__construct(FADU_PERSONAL);
        }
    /* ======================================= LISTAS ======================================= */

    public function listarCargos() {
            $query = "SELECT 
                c.id_cargo,
                c.desc_cargo,
                c.cod_cargo,
                c.estado_cargo
            FROM 
                " . SCHEMA_FADU_PERSONAL . ".cargos c
            WHERE c.estado_cargo = ESTADO_ACTIVO
            ORDER BY c.desc_cargo ASC ";
            
            $result = $this->_db->prepare($query);
    
            $result->execute();
            return $result->fetchAll();
            
    }

    public function detalleCargo($id) {        
            $query = "SELECT
                d.id_cargo,
                d.desc_cargo,
                d.cod_cargo,
                d.estado_cargo
            FROM " . SCHEMA_FADU_PERSONAL . ".cargos d
            WHERE d.id_cargo = :id AND d.estado_cargo = ESTADO_ACTIVO";

            $result = $this->_db->prepare($query);

            $result->bindValue(":id", $id);

            $result->execute();
            return $result->fetch();
    }

    /* ======================================= ABM ======================================= */

    public function crearCargo($datos) {
        $query = 'INSERT INTO
            ' . SCHEMA_FADU_PERSONAL . '.cargos
            (desc_cargo, cod_cargo, estado_cargo)
            VALUES
            (:desc_cargo, :cod_cargo, :estado_cargo)';

            $result = $this->_db->prepare($query);            

            $result->bindValue(":desc_cargo", clean($datos['desc_cargo']));
            $result->bindValue(":cod_cargo", clean($datos['cod_cargo']));
            $result->bindValue(":estado_cargo", ESTADO_ACTIVO);
            return $result->execute();
    }

    public function editarCargo($idCargo, $datos) {        
            $query = 'UPDATE
                ' . SCHEMA_FADU_PERSONAL . '.cargos
            SET desc_cargo = :desc_cargo,
            cod_cargo = :cod_cargo
            WHERE id_cargo = :idCargo';

            $result = $this->_db->prepare($query);

            $result->bindValue(":idCargo", clean($idCargo));
            $result->bindValue(":desc_cargo", clean($datos['desc_cargo']));
            $result->bindValue(":cod_cargo", clean($datos['cod_cargo']));
            return $result->execute();
    }

    /* ======================================= METODOS EXTRA ======================================= */
    
    public function validarCargo($datos){
        
            $query = "SELECT 
                d.id_cargo,
                d.desc_cargo,
                d.cod_cargo,
                d.estado_cargo
            FROM 
                " . SCHEMA_FADU_PERSONAL . ".cargos d
            WHERE d.desc_cargo = :descripcion AND d.estado_cargo <> ESTADO_BAJA";

            $result = $this->_db->prepare($query);

            $result->bindValue(":descripcion", $datos['descripcion']);
            return $result->execute();
    }
    
}