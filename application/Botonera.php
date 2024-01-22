<?php

define('BOTONES_PORTADA', array(
    "AGENTES" => array(
        'NOMBRE' => 'AGENTES',
        'ID' => 'agentes',
        'ICONO' => BASEPATH . "/public/images/icons/usuarios.svg",
        'URL' => BASEPATH . "/agentes",
        'PERMISO' => 'PERSONAL_AGENTES'
    ),
    "FICHADAS" => array(
        'NOMBRE' => 'FICHADAS',
        'ID' => 'fichadas',
        'ICONO' => BASEPATH . "/public/images/icons/fichadas.svg",
        'URL' => BASEPATH . "/fichadas",
        'PERMISO' => 'PERSONAL_FICHADAS'
    ),
    "DEPENDENCIAS" => array( // NOMBRE DE ENTIDAD
        'NOMBRE' => 'DEPENDENCIAS', // NOMBRE PARA MOSTRAR
        'ID' => 'dependencias', //ID DEL ELEMENTO
        'ICONO' => BASEPATH . "/public/images/icons/dependencias.svg", // RUTA AL ICONO DEL BOTON
        'URL' => BASEPATH . "/dependencias", //RUTA DEL METODO
        'PERMISO' => 'PERSONAL_DEPENDENCIAS' // PERMISO REQUERIDO
    ),
    "CATALOGOS" => array(
        'NOMBRE' => 'CATALOGOS',
        'ID' => 'catalogos',
        'ICONO' => BASEPATH . "/public/images/icons/catalogo.svg",
        'URL' => BASEPATH . "/catalogos",
        'PERMISO' => 'PERSONAL_CATALOGOS'
    ),
));

define('BOTONES_CATALOGOS', array(
    "CARGOS" => array(
        'NOMBRE' => 'CARGOS',
        'ID' => 'cargos',
        'ICONO' => BASEPATH . "/public/images/icons/cargos.svg",
        'URL' => BASEPATH . "/cargos",
        'PERMISO' => 'PERSONAL_CARGOS'
    ),
    "TIPO_ANTIGUEDAD" => array(
        'NOMBRE' => 'TIPO ANTIGUEDAD',
        'ID' => 'tipoAntiguedad',
        'ICONO' => BASEPATH . "/public/images/icons/tipoAntiguedad.svg",
        'URL' => BASEPATH . "/tipoAntiguedad",
        'PERMISO' => 'PERSONAL_TIPOANTIGUEDAD'
    ),
));
