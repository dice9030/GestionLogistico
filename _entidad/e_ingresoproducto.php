<?php
require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');
#require_once('../_classDatos/cd_producto.php');


error_reporting(E_ERROR);
$enlace = "./_entidad/e_ingresoproducto.php";
$CN = GestionDC();


if (get('IngresoProducto')){ IngresoProducto(get('IngresoProducto'));}

if (get("metodo") != ""){
    
    if(get("TipoDato") == "archivo"){
    }
    
    function p_interno($codigo,$campo){
        
        if(get("metodo") == "SysFomr1"){
            if ($campo == "CODIGO"){
                $vcamp = "'".post("NumDoc")."-"."'";
                $valor = " 'Form_".$vcamp." ' ";
            }else{$valor ="";}
                return $valor; 
            }
            
    }
    function p_before($codigo){

    }			
    if(get("TipoDato") == "texto"){
        if(get("transaccion") == "UPDATE"){
            if(get("metodo") == "Entidades"){p_gf_ult("Entidad",get('codEnt'),$CN);Productos("Listado");}            
         }

        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "Entidades"){p_gf_ult("Entidad","",$CN);Productos("Listado");}
           
        }	
        if(get("transaccion") == "OTRO"){
            if(get("metodo") == "resgistra_usuario"){P_Registro();}		
        }				
    }

    if(get("transaccion") == "DELETE"){
        if(get("metodo") == "Entidades"){DReg("ct_entidad","Codigo","'".get("codEnt")."'",$CN);Productos("Listado");}        
    }		
    exit();
}
#Formulario : FTipoProducto
function IngresoProducto($Arg){
    global $CN, $enlace;
    switch ($Arg) {
        case "Listado":
            $btn = "Agregar]".$enlace."?IngresoProducto=Crear]optionbody}";
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Lista</span><p >REGISTRO DE STOCK</p><div class='bicel'></div>",$btn,"70px","TituloA");
            
            $sql = "SELECT Producto,Cantidad,Codigo AS CodigoAjax FROM maalmacen ";
           
            $clase = 'reporteA';
            $panel = 'optionbody';
            $enlaceCod = 'CodigoProducto';
            $url = $enlace."?IngresoProducto=Editar";
            $reporte = ListR2('',$sql, $CN, $clase,'', $url, $enlaceCod, $panel,'maalmacen','','');
            $s = "<div>".$btn.$reporte."</div>";
            WE($s);	
            
            break;
        case "Crear":
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?IngresoProducto=Listado]optionbody}";	
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Registrar</span><p >REGISTRO DE STOCK</p><div class='bicel'></div>",$btn,"50px","TituloA");
            
           # $uRLForm ="Buscar]".$enlace."?Entidades=Confirmar]PanelB]F]}";

            $form = c_form_ult('', $CN,'FAlmacen', 'CuadroA', $path, $uRLForm, "'".$codEntidad."'", $tSelectD);
            $form = "<div style='width:100%;'>".$btn.$form."</div>";
            $s = "<div class= 'PanelPadding'>".$form."</div>";             
            WE($s);
        break;    
        case "Editar":
            $CodigoProducto = get("CodigoProducto");
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?IngresoProducto=Listado]optionbody}"; 
            $btn = Botones($btn, 'botones1','');        
            $btn = tituloBtnPn("<span>Registrar</span><p > REGISTRO DE STOCK</p><div class='bicel'></div>",$btn,"50px","TituloA");
            
            $uRLForm ="Buscar]".$enlace."?IngresoProducto=Confirmar]PanelB]F]}";

            $form = c_form_ult('',$CN,'FAlmacen', 'CuadroA', $path, $uRLForm, $CodigoProducto, $tSelectD);
            $form = "<div style='width:100%;'>".$btn.$form."</div>";
            $s = "<div class= 'PanelPadding'>".$form."</div>";             
            WE($s);
        break;    
    }
    
}	
