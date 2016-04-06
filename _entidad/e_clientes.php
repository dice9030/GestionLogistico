<?php
require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');
#require_once('../_classDatos/cd_producto.php');


error_reporting(E_ERROR);
$enlace = "./_entidad/e_clientes.php";
$CN = GestionDC();


if (get('Cliente')){ Cliente(get('Cliente'));}

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
           $CodigoCliente = get("CodigoCliente");
            if(get("metodo") == "FCliente"){p_gf("FCliente",$CN,$CodigoCliente);Cliente("Listado");}            
         }

        if(get("transaccion") == "INSERT"){

            if(get("metodo") == "FCliente"){p_gf("FCliente",$CN,"");Cliente("Listado");}
           
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
function Cliente($Arg){
    global $CN, $enlace;
    switch ($Arg) {
        case "Listado":

            $btn = "Agregar]".$enlace."?Cliente=Crear]optionbody}";
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Lista</span><p >Clientes</p><div class='bicel'></div>",$btn,"70px","TituloA");
            
            $sql = "SELECT RazonSocial,Nombre,ApellidoPaterno,ApellidoPaterno,Codigo AS CodigoAjax FROM macliente ";
           
            $clase = 'reporteA';
            $panel = 'optionbody';
            $enlaceCod = 'CodigoCliente';
            $url = $enlace."?Cliente=Editar";
            $reporte = ListR2('',$sql, $CN, $clase,'', $url, $enlaceCod, $panel,'ma_usuario','','');
            $s = "<div>".$btn.$reporte."</div>";
            WE($s);	
            
            break;
        case "Crear":


            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Cliente=Listado]optionbody}";	
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Registrar</span><p > Clientes</p><div class='bicel'></div>",$btn,"50px","TituloA");
            
            $tSelectD = array(
                'Distrito'  => 'SELECT Codigo,Descripcion FROM madistritos'                        
            );

            $uRLForm = "Guardar]".$enlace."?metodo=FCliente&transaccion=INSERT]optionbody]F]}";
            $form = c_form_adp('', $CN,'FCliente', 'CuadroA', $path, $uRLForm, "", $tSelectD,'Codigo');
            $form = "<div style='width:100%;'>".$btn.$form."</div>";
            $s = "<div class= 'PanelPadding'>".$form."</div>";             
            WE($s);
        break;    
        case "Editar":
            $CodigoCliente = get("CodigoCliente");
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Cliente=Listado]optionbody}"; 
            $btn = Botones($btn, 'botones1','');        
            $btn = tituloBtnPn("<span>Registrar</span><p > Clientes</p><div class='bicel'></div>",$btn,"50px","TituloA");

            $tSelectD = array(
                'Distrito'  => 'SELECT Codigo,Descripcion FROM madistritos'                        
            );

            $uRLForm = "Guardar]".$enlace."?metodo=FCliente&transaccion=UPDATE&CodigoCliente={$CodigoCliente}]optionbody]F]}";
            $form = c_form_adp('',$CN,'FCliente', 'CuadroA', $path, $uRLForm, $CodigoCliente, $tSelectD,'Codigo');
            $form = "<div style='width:100%;'>".$btn.$form."</div>";
            $s = "<div class= 'PanelPadding'>".$form."</div>";             
            WE($s);
        break;    
    }
    
}	
