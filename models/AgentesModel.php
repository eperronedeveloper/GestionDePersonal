<?php

class AgentesModel extends Model {

        function __construct() {
            parent::__construct(FADU_PERSONAL);
        }
    /* ======================================= LISTAS ======================================= */

    public function listarAgentes() {
            $query = "SELECT 
                c.legajo,
                c.nombre_agente,
                c.apellido_agente,
                c.dni_agente,
                c.cuil_agente,
                c.telefono,
                c.email,
                c.foto_agente
            FROM 
                " . SCHEMA_FADU_PERSONAL . ".agentes c
            WHERE c.estado_agente = ESTADO_ACTIVO
            ORDER BY c.legajo ASC ";
            
            $result = $this->_db->prepare($query);
    
            $result->execute();
            return $result->fetchAll();            
    }

    public function detalleAgente($id) {        
            $query = "SELECT
                c.legajo,
                c.nombre_agente,
                c.apellido_agente,
                c.genero,
                c.estado_civil,
                c.dni_agente,
                c.cuil_agente,
                c.telefono,
                c.codigo_postal,
                c.calle_domicilio,
                c.numero_domicilio,
                c.departamento_domicilio,
                c.email,
                c.foto_agente,
                to_char(c.fecha_nacimiento,'DD/MM/YYYY') as fecha_nacimiento,
                c.pais_nacimiento,
                c.pais_residencia,
                c.provincia_nacimiento,
                c.provincia_residencia,
                c.localidad_nacimiento,
                c.localidad_residencia,
                pan.nombre_pais as nombre_pais_nacimiento,
                par.nombre_pais as nombre_pais_residencia,
                prn.nombre_provincia as nombre_provincia_nacimiento,
                prr.nombre_provincia as nombre_provincia_residencia
            FROM " . SCHEMA_FADU_PERSONAL . ".agentes c
            LEFT JOIN " . SCHEMA_FADU_PERSONAL . ".paises pan
                ON pan.id_pais = c.pais_nacimiento
            LEFT JOIN " . SCHEMA_FADU_PERSONAL . ".paises par
                ON par.id_pais = c.pais_residencia
            LEFT JOIN " . SCHEMA_FADU_PERSONAL . ".provincias prn
                ON prn.id_pais = c.pais_nacimiento
            LEFT JOIN " . SCHEMA_FADU_PERSONAL . ".provincias prr
                ON prr.id_pais = c.pais_residencia
            WHERE c.legajo = :id AND c.estado_agente = ESTADO_ACTIVO";

            $result = $this->_db->prepare($query);

            $result->bindValue(":id", $id);

            $result->execute();
            return $result->fetch();
    }

    /* ======================================= ABM ======================================= */

    public function crearAgente($datos) {
        $query = 'INSERT INTO
            ' . SCHEMA_FADU_PERSONAL . '.agentes
            (legajo,
            fecha_nacimiento,
            nombre_agente,
            apellido_agente,
            dni_agente,
            cuil_agente,
            genero,
            estado_civil,
            email,
            foto_agente,
            franquero,
            telefono,
            pais_nacimiento,
            provincia_nacimiento,
            localidad_nacimiento,
            pais_residencia,
            provincia_residencia,
            localidad_residencia,
            codigo_postal,
            calle_domicilio,
            numero_domicilio,
            departamento_domicilio,
            estado_agente)
            VALUES
            (:legajo,
            :fecha_nacimiento,
            :nombre_agente,
            :apellido_agente,
            :dni_agente,
            :cuil_agente,
            :genero,
            :estado_civil,
            :email,
            :foto_agente,
            :franquero,
            :telefono,
            :pais_nacimiento,
            :provincia_nacimiento,
            :localidad_nacimiento,
            :pais_residencia,
            :provincia_residencia,
            :localidad_residencia,
            :codigo_postal,
            :calle_domicilio,
            :numero_domicilio,
            :departamento_domicilio,
            :estado_agente)';

            $result = $this->_db->prepare($query);            

            $result->bindValue(":legajo", clean((int)$datos['legajo']));
            $result->bindValue(":fecha_nacimiento", clean($datos['fecha_nacimiento']));
            $result->bindValue(":nombre_agente", clean($datos['nombre_agente']));
            $result->bindValue(":apellido_agente", clean($datos['apellido_agente']));
            $result->bindValue(":dni_agente", clean($datos['dni_agente']));
            $result->bindValue(":cuil_agente", clean($datos['cuil_agente']));
            $result->bindValue(":genero", clean($datos['genero']));
            $result->bindValue(":estado_civil", clean($datos['estado_civil']));
            $result->bindValue(":email", clean($datos['email']));
            $result->bindValue(":foto_agente", $datos['foto_agente']);
            $result->bindValue(":franquero", clean($datos['franquero']));
            $result->bindValue(":telefono", clean($datos['telefono']));
            $result->bindValue(":pais_nacimiento", clean($datos['id_pais_nacimiento']));
            $result->bindValue(":provincia_nacimiento", clean($datos['id_provincia_nacimiento']));
            $result->bindValue(":localidad_nacimiento", clean($datos['localidad_nacimiento']));
            $result->bindValue(":pais_residencia", clean($datos['id_pais_residencia']));
            $result->bindValue(":provincia_residencia", clean($datos['id_provincia_residencia']));
            $result->bindValue(":localidad_residencia", clean($datos['localidad_residencia']));
            $result->bindValue(":codigo_postal", clean($datos['codigo_postal']));
            $result->bindValue(":calle_domicilio", clean($datos['calle_domicilio']));
            $result->bindValue(":numero_domicilio", clean($datos['numero_domicilio']));
            $result->bindValue(":departamento_domicilio", clean($datos['departamento_domicilio']));
            $result->bindValue(":estado_agente", ESTADO_ACTIVO);
            return $result->execute();
    }

    public function editarAgente($datos) {
        
        $query = 'UPDATE
        ' . SCHEMA_FADU_PERSONAL . '.agentes
            SET legajo = :legajo,
            fecha_nacimiento = :fecha_nacimiento,
            nombre_agente = :nombre_agente,
            apellido_agente = :apellido_agente,
            dni_agente = :dni_agente,
            cuil_agente = :cuil_agente,
            genero = :genero,
            estado_civil = :estado_civil,
            email = :email,
            foto_agente = :foto_agente,
            franquero = :franquero,
            telefono = :telefono,
            pais_nacimiento = :pais_nacimiento,
            provincia_nacimiento = :provincia_nacimiento,
            localidad_nacimiento = :localidad_nacimiento,
            pais_residencia = :pais_residencia,
            provincia_residencia = :provincia_residencia,
            localidad_residencia = :localidad_residencia,
            codigo_postal = :codigo_postal,
            calle_domicilio = :calle_domicilio,
            numero_domicilio = :numero_domicilio,
            departamento_domicilio = :departamento_domicilio
            WHERE legajo = :idAgente';

            $result = $this->_db->prepare($query);            

            $result->bindValue(":idAgente", $datos['id_agente']);
            $result->bindValue(":legajo", clean((int)$datos['legajo']));
            $result->bindValue(":fecha_nacimiento", clean($datos['fecha_nacimiento']));
            $result->bindValue(":nombre_agente", clean($datos['nombre_agente']));
            $result->bindValue(":apellido_agente", clean($datos['apellido_agente']));
            $result->bindValue(":dni_agente", clean($datos['dni_agente']));
            $result->bindValue(":cuil_agente", clean($datos['cuil_agente']));
            $result->bindValue(":genero", clean($datos['genero']));
            $result->bindValue(":estado_civil", clean($datos['estado_civil']));
            $result->bindValue(":email", clean($datos['email']));
            $result->bindValue(":foto_agente", $datos['foto_agente']);
            $result->bindValue(":franquero", clean($datos['franquero']));
            $result->bindValue(":telefono", clean($datos['telefono']));
            $result->bindValue(":pais_nacimiento", clean($datos['id_pais_nacimiento']));
            $result->bindValue(":provincia_nacimiento", clean($datos['id_provincia_nacimiento']));
            $result->bindValue(":localidad_nacimiento", clean($datos['localidad_nacimiento']));
            $result->bindValue(":pais_residencia", clean($datos['id_pais_residencia']));
            $result->bindValue(":provincia_residencia", clean($datos['id_provincia_residencia']));
            $result->bindValue(":localidad_residencia", clean($datos['localidad_residencia']));
            $result->bindValue(":codigo_postal", clean($datos['codigo_postal']));
            $result->bindValue(":calle_domicilio", clean($datos['calle_domicilio']));
            $result->bindValue(":numero_domicilio", clean($datos['numero_domicilio']));
            $result->bindValue(":departamento_domicilio", clean($datos['departamento_domicilio']));
            return $result->execute();
    }

    /* ======================================= METODOS EXTRA ======================================= */
    
    public function validarAgente($datos){
        
            $query = "SELECT 
                a.legajo,
                a.estado_agente
            FROM 
                " . SCHEMA_FADU_PERSONAL . ".agentes a
            WHERE a.legajo = :legajo AND a.estado_agente <> ESTADO_BAJA";

            $result = $this->_db->prepare($query);

            $result->bindValue(":legajo", $datos['legajo']);
            return $result->execute();
    }

    public function ACPais($busqueda) {
        $query = '
                SELECT 
                    id_pais,
                    nombre_pais
                FROM ' . SCHEMA_FADU_PERSONAL . '.paises
                WHERE UPPER(nombre_pais) like :busqueda AND 1=1';

            $result = $this->_db->prepare($query);

            $result->bindValue(":busqueda", "%" . clean($busqueda) . "%");
            $result->execute();
            return $result->fetchAll();
    }

    public function ACProvincia($idPais, $busqueda) {
        $query = '
                SELECT 
                    id_provincia,
                    id_pais,
                    nombre_provincia
                FROM ' . SCHEMA_FADU_PERSONAL . '.provincias
                WHERE UPPER(nombre_provincia) like :busqueda 
                AND id_pais = :idPais AND 1=1';

            $result = $this->_db->prepare($query);

            $result->bindValue(":busqueda", "%" . clean($busqueda) . "%");
            $result->bindValue(":idPais", $idPais);
            $result->execute();
            return $result->fetchAll();
    }
    
}