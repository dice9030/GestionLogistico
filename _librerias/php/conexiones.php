<?php

const DB_SERVER ="104.236.16.206";
const DB_NAME = "gestiondc";
const DB_USER = "DCelis";
const DB_PASSWORD = "28rcYMcCtVdsTvLm";

function PDOConnection($server = null, $dbname = null, $user = null, $password = null) {
    $SERVER = ($server) ? $server : DB_SERVER;
    $DBNAME = ($dbname) ? $dbname : DB_NAME;
    $USER = ($user) ? $user : DB_USER;
    $PASSWORD = ($password) ? $password : DB_PASSWORD;

    $pdo = null;

    try {
        $pdo = new PDO("mysql:host={$SERVER};dbname={$DBNAME}", $USER, $PASSWORD);
    } catch (PDOException $e) {
        die($e->getMessage());
    }

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdo;
}


function GestionDC() {
    $cnx = mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD);
    mysql_select_db(DB_NAME, $cnx);

    return $cnx;
}

function conexSis_Emp($db_name) {
    $cnx = mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD);
    mysql_select_db($db_name, $cnx);

    return $cnx;
}


function conexSys(){
        $servidor ="localhost";
        $usuario="root";
        $contrasena="";
        $nombreBDatos = "gestiondc";
        $conexionA = mysql_connect($servidor, $usuario, $contrasena);
        mysql_select_db($nombreBDatos, $conexionA);
        return $conexionA;   
}



function conexDefsei(){
        $servidor ="localhost";
        $usuario="root";
        $contrasena="";
        $nombreBDatos = "gestiondc";
        $conexionA = mysql_connect($servidor, $usuario, $contrasena);
        mysql_select_db($nombreBDatos, $conexionA);
        return $conexionA;	 
}

function ConGestionDC(){
    try{
        $con = new mysqli();
        $con->connect('localhost', 'root', '', 'gestiondc');
        return $con;
    } catch (Exception $ex) {
        return "Error: ".$ex->getMessage();
    }
}
 ?>	
