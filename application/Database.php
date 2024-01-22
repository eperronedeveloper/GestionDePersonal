<?php

class Database extends PDO {

    public function __construct($conn) {
        $motor = $conn['db'];
        
        if ($motor == 'mysql') {
            parent::__construct('mysql:host=' . $conn['db_host'] . ';dbname=' . $conn['db_schema'] . ';charset=' . $conn['db_char'], $conn['db_user'], $conn['db_pass'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . $conn['db_char']));
        } else if ($motor == "postgres") {
            parent::__construct('pgsql:host='.$conn['db_host'].';dbname=' .$conn['db_name'], $conn['db_user'], $conn['db_pass']);
        }
        
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    }
} 