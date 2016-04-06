<?php 

header("Access-Control-Allow-Origin:*");
require_once('../funciones.php');
require_once('../conexiones.php');

$action	  		   = $_POST["action"];

$g = new Ebooks($params);
WE( $g->$action() );


/**
*   
*/
class Ebooks 
{	
	private $PDO     = null;
	private $entity = "";

	function __construct($params)
	{
		$this->PDO 	     = PDOConnection();
		$this->entity    = $_POST["entity"];

	}

	function getAllebooks(){
		$qEbooks = "    SELECT
						T1.Titulo,AL.AlmacenCod
						FROM almacen AL
						INNER JOIN articulos AS T1 ON AL.Producto = T1.Producto 
						INNER JOIN documento AS doc ON T1.ProductoFab = doc.codigo 
						WHERE doc.entidad = '$this->entity' AND  
						AL.tipoproducto IN ('revista','libro') 
						group by T1.ProductoFab 
						ORDER BY AL.FechReg DESC   
						";

		$stmt = $this->PDO;
		$stmt = $stmt->prepare($qEbooks);
		$ebooks = $stmt->execute();
		$arrEbooks = [];
		while ($ebook = $stmt->fetchObject()) {
			$arrEbooks[] =  $ebook;
		}

		WE(json_encode($arrEbooks));

	}
}