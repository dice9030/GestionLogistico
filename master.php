<?php
session_start();
require_once('_librerias/disenoVisual/menus.php');
require_once('_librerias/disenoVisual/cuerposite.php');
require_once('_librerias/php/conexiones.php');
require_once('_librerias/php/funciones.php');

error_reporting(E_ERROR);

$vConex = conexSys();

$master_access = $_SESSION["master_access"];

if (!$master_access) {
    rd("admin_master.php");
}

$menu_pie = menuPie("pie");
$CuerpoSite = vistaColumnaUnica("");

$s = menuMaster("menu");
$s .= '<div style="float:left;width:100%;height:100%;padding:10px 0px;">';
$s .= $CuerpoSite;
$s .= '</div>';

W($s);
?>
<style type="text/css">
    .PanelA{ width:100%;}
    .PanelB{width:100%;}
</style>
