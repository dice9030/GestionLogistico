<?php 

header("Access-Control-Allow-Origin:*");
require_once('../funciones.php');
require_once('../conexiones.php');
require_once('../global_usuarios.php');

$action	  		   = $_POST["action"];
$params["email"]   = $_POST["email"];
$params["entidad"] = $_POST["entidad"];


$g = new Usuario($params);
WE( $g->$action() );


/**
*   
*/
class Usuario 
{	
	private $PDO     = null;
	private $user    = "";
	private $entidad = "";

	function __construct($params)
	{
		$this->PDO 	     = PDOConnection();
		$this->user      = $params["email"];
		$this->entidad   = $params["entidad"];
	}

	function existsUser(){


		$qUser = "  SELECT   
                    DISTINCT(tab2.Usuario) as Usuario,tab1.EntidadCreadora,tab2.Contrasena,tab2.CodigoParlante,tab2.Apellidos,tab2.Nombres 
                    FROM usuario_entidad AS tab1 
                    LEFT JOIN usuarios AS tab2
                    ON tab1.Usuario = tab2.Usuario 
                    WHERE tab1.EntidadCreadora IN ('".$this->entidad."')
					AND tab2.CodigoParlante = '".$this->user."'
                    ORDER BY IdCodCorrelativo DESC
                    LIMIT 1 "; 

		$stmt = $this->PDO;
		$stmt = $stmt->prepare($qUser);
		$stmt->execute();
		$user = $stmt->fetchObject();

		if($user)
			WE(json_encode(array('status' =>true)));
		else
			WE(json_encode(array('status' =>false)));
	}


	function getdataforUser(){
                    $qUser = "  SELECT   
                                DISTINCT(tab2.Usuario) as Usuario,tab1.EntidadCreadora,tab2.Contrasena,tab2.CodigoParlante,tab2.Apellidos,tab2.Nombres 
                                FROM usuario_entidad AS tab1 
                                LEFT JOIN usuarios AS tab2
                                ON tab1.Usuario = tab2.Usuario 
                                WHERE tab1.EntidadCreadora IN ('$this->entidad')
								AND tab2.CodigoParlante = '$this->user'
                                ORDER BY IdCodCorrelativo DESC
                                LIMIT 1 "; 

                        $stmt = $this->PDO;
						$stmt = $stmt->prepare($qUser);
						$stmt->execute();
						$user = $stmt->fetchObject();

						if($user)
							WE(json_encode(array('status' =>true,'data'=>$user)));
						else
							WE(json_encode(array('status' =>false)));
	}

	function insertUser(){
		$vConex 	= conexSys();
		$pagweb 	= $_POST["pagweb"];
		$email      = $_POST["email"];
		$nombres    = $_POST["Nombres"];
		$apellidos  = $_POST["Apellidos"];
		$clavedefault = $_POST["Contrasena"];
		$entidadCreadora = $_POST["entidadCreadora"];

		$rsp = CrearUser_Compra2($pagweb,$email,$nombres,$apellidos,$clavedefault,3,$entidadCreadora,$vConex,true);

		if($rsp)
			WE(json_encode(array('status' =>true)));
		else
			WE(json_encode(array('status' =>false)));
	}
}