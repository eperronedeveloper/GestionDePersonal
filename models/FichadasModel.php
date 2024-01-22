<?php

class FichadasModel extends Model {

        function __construct() {
            parent::__construct(FADU_PERSONAL);
        }
    /* ======================================= LISTAS ======================================= */

    public function listarFichadas() {
        
            $query = "SELECT 
                f.legajo,
                f.tipo_fichada,
                to_char(f.fecha_fichada,'DD/MM/YYYY') as fecha_fichada_f,
                f.fecha_fichada,
                f.hora_fichada,
                f.estado_fichada
            FROM 
                " . SCHEMA_FADU_PERSONAL . ".fichadas f
                WHERE f.estado_fichada = ESTADO_ACTIVO
            ORDER BY f.fecha_fichada ASC ";
            
            $result = $this->_db->prepare($query);
    
            $result->execute();
            return $result->fetchAll();
            
    }

    public function detalleFichada($datos) {
            $query = "SELECT
                f.legajo,
                f.tipo_fichada,
                to_char(f.fecha_fichada,'DD/MM/YYYY') as fecha_fichada_f,
                f.hora_fichada,
                f.estado_fichada
            FROM " . SCHEMA_FADU_PERSONAL . ".fichadas f
            WHERE f.estado_fichada = ESTADO_ACTIVO
            AND f.legajo = :legajo AND f.fecha_fichada = :fecha_fichada AND f.tipo_fichada = :tipo_fichada ";

            $result = $this->_db->prepare($query);

            $result->bindValue(":legajo", clean($datos['legajo']));
            $result->bindValue(":fecha_fichada", clean($datos['fecha_fichada']));
            $result->bindValue(":tipo_fichada", clean($datos['tipo_fichada']));

            $result->execute();
            return $result->fetch();
    }

    /* ======================================= ABM ======================================= */

    public function crearFichada($datos) {
        $query = 'INSERT INTO
            ' . SCHEMA_FADU_PERSONAL . '.fichadas
            (legajo, tipo_fichada, fecha_fichada, hora_fichada, estado_fichada)
            VALUES
            (:legajo, :tipo_fichada, :fecha_fichada, :hora_fichada, :estado_fichada)';

            $result = $this->_db->prepare($query);            

            $result->bindValue(":legajo", clean($datos['legajo']));
            $result->bindValue(":tipo_fichada", clean($datos['tipo_fichada']));
            $result->bindValue(":fecha_fichada", clean($datos['fecha_fichada']));
            $result->bindValue(":hora_fichada", clean($datos['hora_fichada']));
            $result->bindValue(":estado_fichada", ESTADO_ACTIVO);
            return $result->execute();
    }

    public function cambiarEstadoRegistroFichada($tabla, $pkCampo, $pkCampo2, $pkCampo3, $pkValor, $pkValor2, $pkValor3, $nombreColumna, $idEstado) {
        try {
            $query = "UPDATE
                " . SCHEMA_FADU_PERSONAL . ".".$tabla."
            SET $nombreColumna = :estado
            WHERE ". $pkCampo ." = :pk1 AND ".$pkCampo2." = :pk2 AND ".$pkCampo3." = :pk3 ";

            $result = $this->_db->prepare($query);

            $result->bindValue(":pk1", $pkValor);
            $result->bindValue(":pk2", $pkValor2);
            $result->bindValue(":pk3", $pkValor3);
            $result->bindValue(":estado", $idEstado);

            if ($result->execute()) {
                return true;
            }
        } catch (Exception $ex) {
            throw($ex);
        }
    }

/* ======================================= METODOS EXTRA ======================================= */

    public function ACLegajo($busqueda) {
        $query = "
                SELECT 
                    legajo,
                    nombre_agente,
                    apellido_agente,
                    estado_agente
                FROM " . SCHEMA_FADU_PERSONAL . ".agentes
                WHERE CAST(legajo AS varchar(6)) like :busqueda
                AND estado_agente = ESTADO_ACTIVO
                AND 1=1";

            $result = $this->_db->prepare($query);

            $result->bindValue(":busqueda", "%" . clean($busqueda) . "%");
            $result->execute();
            return $result->fetchAll();
    }    
}