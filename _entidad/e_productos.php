<?php
require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');
//require_once('../_classDatos/cd_producto.php');


error_reporting(E_ERROR);
$enlace = "./_entidad/e_productos.php";
$CN = GestionDC();


if (get('Productos')){ Productos(get('Productos'));}

if (get("metodo") != ""){
    
    if(get("TipoDato") == "archivo"){
         if (protect(get("metodo")) == "FProducto") {
            $archivo = upload('admin', 'Empresa', $vConex);
            echo json_encode($archivo);
        }
    }
    
    function p_interno($codigo,$campo){
        
        if(get("metodo") == "SysFomr1"){
            if ($campo == "CODIGO"){
              //  $vcamp = "'".post("NumDoc")."-"."'";
              //  $valor = " 'Form_".$vcamp." ' ";
            }else{$valor ="";}
              //  return $valor; 
            }
            
    }
    function p_before($codigo){

    }			
    if(get("TipoDato") == "texto"){
        if(get("transaccion") == "UPDATE"){
            $CodigoProducto = get("CodigoProducto");
            if(get("metodo") == "FProducto"){
              //  p_gf("FProducto",$CN,$CodigoProducto);
                p_gf_udp("FProducto", $CN, $CodigoProducto, 'Codigo');
                Productos("Listado");
            }           
             

         }

        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "FProducto"){
                //p_gf("FProducto",$CN,"");
                p_gf_udp("FProducto", $CN, '', 'Codigo');
                Productos("Listado");
            }
           
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
function Productos($Arg){
    global $CN, $enlace;
    switch ($Arg) {
        case "Listado":
            $btn = "Agregar]".$enlace."?Productos=Crear]optionbody}";
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Lista</span><p >Productos</p><div class='bicel'></div>",$btn,"70px","TituloA");
            
            $sql = "SELECT CodigoProducto,Descripcion,TipoProducto,Codigo AS CodigoAjax FROM maproducto ";
           
            $clase = 'reporteA';
            $panel = 'optionbody';
            $enlaceCod = 'CodigoProducto';
            $url = $enlace."?Productos=Editar";
            $reporte = ListR2('',$sql, $CN, $clase,'', $url, $enlaceCod, $panel,'maproducto','','');
            $s = "<div>".$btn.$reporte."</div>";
            WE($s);	
            
            break;
        case "Crear":
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Productos=Listado]optionbody}";	
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Registrar</span><p > REGISTRO DE PRODUCTOS</p><div class='bicel'></div>",$btn,"50px","TituloA");
            $tSelectD = array(
                'TipoProducto'       => 'SELECT Codigo,Concepto FROM matipoproducto',
                'Categoria'       => 'SELECT Codigo,Abreviatura FROM macategoriaproducto'
               
            );


            $path = array('Imagen' => '../_files/producto/');
            if(!file_exists($path['Imagen'])){
                W("La carpeta 'Formatos' no existe en esta empresa...<br>");
                W("Creando la carpeta...<br>");
                if(!mkdir($path['Imagen'],0777,true)){
                    W("ERROR: No se puedo crear la carpeta<br>");
                }else{
                    W("&check; Se creo la carpeta<br>");
                }
            }
            //$uRLForm ="".$enlace."?metodo=procesaForm&FArticulos1=INSERT";
       // $s = c_form($vConex,"FArticulos1","CuadroA",$path,$uRLForm);
                                                                                  //]vista]F]}
            $uRLForm = "Guardar]".$enlace."?metodo=FProducto&transaccion=INSERT]optionbody]F]}";

            $form = c_form_adp('', $CN,'FProducto', 'CuadroA', $path, $uRLForm, "'".$codEntidad."'", $tSelectD,'Codigo');
        //    $Form = c_form_adp('', $vC, "lecurso_crear", "CuadroA", $path, $uRLForm, $EvalDetalleCod, "", 'Codigo');
            $form = "<div style='width:100%;'>".$btn.$form."</div>";
            $s = "<div class= 'PanelPadding'>".$form."</div>";             
            WE($s);
        break;    
        case "Editar":
            $CodigoProducto = get("CodigoProducto");
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Productos=Listado]optionbody}"; 
            $btn = Botones($btn, 'botones1','');        
            $btn = tituloBtnPn("<span>Registrar</span><p > REGISTRO DE PRODUCTOS</p><div class='bicel'></div>",$btn,"50px","TituloA");
             $tSelectD = array(
                'TipoProducto'       => 'SELECT Codigo,Concepto FROM matipoproducto',
                'Categoria'       => 'SELECT Codigo,Abreviatura FROM macategoriaproducto'
               
            );

            $path = array('Imagen' => '../_files/producto/');
            if(!file_exists($path['Imagen'])){
                W("La carpeta 'Formatos' no existe en esta empresa...<br>");
                W("Creando la carpeta...<br>");
                if(!mkdir($path['Imagen'],0777,true)){
                    W("ERROR: No se puedo crear la carpeta<br>");
                }else{
                    W("&check; Se creo la carpeta<br>");
                }
            }


            $uRLForm = "Guardar]".$enlace."?metodo=FProducto&transaccion=UPDATE&CodigoProducto={$CodigoProducto}]optionbody]F]}";

            $form = c_form_adp('',$CN,'FProducto', 'CuadroA', $path, $uRLForm, $CodigoProducto, $tSelectD,'Codigo');
            $form = "<div style='width:100%;'>".$btn.$form."</div>";
            $s = "<div class= 'PanelPadding'>".$form."</div>";             
            WE($s);
        break;    
    }
    
}	
