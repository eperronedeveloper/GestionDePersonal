<?php 

abstract class Model extends Database {

    protected $_db;
    protected $_schema;

    public function __construct($conexion) {
        $this->_db = new Database($conexion);
        $this->_schema = $conexion['db_schema'];
    }

    public function getParametrosStore($nombrestore) {
        $query = 'SELECT PARAMETER_NAME , DATA_TYPE, DTD_IDENTIFIER, PARAMETER_MODE
                FROM information_schema.parameters
                WHERE SPECIFIC_NAME = :nombrestore';
        $model = $this->_db->prepare($query);
        $model->bindValue(":nombrestore", $nombrestore, PDO::PARAM_STR);
        $model->execute();
        return $model->fetchall();
    }
    
    public function obtenerFechaServidor() {
        $query = "select DATE_FORMAT(sysdate(), '%d-%m-%Y')  as Fecha from dual";
        $fechaActual = $this->_db->prepare($query);
        $fechaActual->execute();
        return $fechaActual->fetch();
    }


    public function _beginTransaction() {
        return $this->_db->beginTransaction();
    }
    
    public function _inTransaction() {
        return $this->_db->inTransaction();
    }

    public function _commit() {
        $result = 1;
        if ($this->_db->inTransaction()) {
            $result = $this->_db->commit();
        }
        return $result;
    }

    public function _rollBack() {
        return $this->_db->rollBack();
    }

    public function cambiarEstadoRegistro($tabla, $pkCampo, $pkValor, $nombreColumna, $idEstado) {
        try {
            $query = "UPDATE
                " . SCHEMA_FADU_PERSONAL . ".".$tabla."
            SET $nombreColumna = :estado
            WHERE ". $pkCampo ." = :valor";

            $result = $this->_db->prepare($query);

            $result->bindValue(":valor", $pkValor);
            $result->bindValue(":estado", $idEstado);

            if ($result->execute()) {
                return true;
            }
        } catch (Exception $ex) {
            throw($ex);
        }
    }

    

}