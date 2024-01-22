<?php

class HomeModel extends Model {

    function __construct() {
        parent::__construct(NOMBRE_PROYECTO);
    }

    // public function validarLogin($usuario) {
    //     try {
    //         $query = "SELECT
    //             U.id_legajo,
    //             U.nombre_usuario,
    //             U.apellido_usuario
    //         FROM
    //             " . SCHEMA_PROYECTOS_CORE . ".usuarios U
    //         WHERE U.id_legajo = :legajo";
    //         $result = $this->_db->prepare($query);
    //         $result->bindValue(":legajo", $usuario);
    //         if ($result->execute()) {
    //             return $result->fetch();
    //         }
    //     } catch (Exception $ex) {
    //         throw($ex);
    //     }
    // }

    // public function validarLoginPorBase($usuario, $pass) {
    //     try {
    //         $query = "SELECT
    //             U.id_legajo,
    //             U.nombre_usuario,
    //             U.apellido_usuario,
    //             U.password
    //         FROM
    //             " . SCHEMA_PROYECTOS_CORE . ".usuarios U
    //         WHERE U.id_legajo = :legajo AND U.password = :password";
    //         $result = $this->_db->prepare($query);
    //         $result->bindValue(":legajo", $usuario);
    //         $result->bindValue(":password", $pass);
    //         if ($result->execute()) {
    //             return $result->fetch();
    //         }
    //     } catch (Exception $ex) {
    //         throw($ex);
    //     }
    // }

    // public function getPermisos($id_legajo) {
    //     try {
    //         $query = "SELECT 
    //             p.nombre_permiso as permisos
    //         FROM " . SCHEMA_PROYECTOS_CORE . ".proyectos_usuarios_roles pur
    //             INNER JOIN " . SCHEMA_PROYECTOS_CORE . ".roles r
    //                 ON r.id_rol = pur.id_rol
    //             INNER JOIN " . SCHEMA_PROYECTOS_CORE . ".permisos p
    //                 ON p.id_rol = r.id_rol
    //             WHERE 
    //             pur.id_legajo = :id_legajo AND pur.estado_proyectos_usuarios_roles = ESTADO_ACTIVO AND P.estado_permisos = ESTADO_ACTIVO
    //         ORDER BY p.nombre_permiso ASC";

    //         $result = $this->_db->prepare($query);
    //         $result->bindValue(":id_legajo", $id_legajo);
    //         if ($result->execute()) {
    //             return $result->fetchAll();
    //         }
    //     } catch (Exception $ex) {
    //         throw($ex);
    //     }
    // }
}