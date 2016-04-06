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
        if(get("metodo") == "FAlmacen"){
            switch ($campo) {
                case 'Usuario':
                    $valor = "'admin'";
                    break;                
                default:
                     $valor = "";
                    break;             
            }
             return $valor;                 
        }
    }
    function p_before($codigo){

    }			
    if(get("TipoDato") == "texto"){
        if(get("transaccion") == "UPDATE"){
              $CodigoAlmacen = get("CodigoAlmacen");
            if(get("metodo") == "FAlmacen"){p_gf("FAlmacen",$CN,$CodigoAlmacen);IngresoProducto("Listado");}            
         }

        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "FAlmacen"){p_gf("FAlmacen",$CN,"");IngresoProducto("Listado");}
           
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
            $btn .= "Detalle]".$enlace."?IngresoProducto=Detalle]optionbody}";
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Lista</span><p >REGISTRO DE STOCK</p><div class='bicel'></div>",$btn,"200px","TituloA");
            
            $sql = "  SELECT MPR.Descripcion,SUM(MAL.Cantidad) AS Cantidad,MPR.Codigo AS CodigoAjax
                    FROM maalmacen MAL
                    INNER JOIN maproducto AS MPR ON MAL.Producto=MPR.Codigo
                    GROUP BY MPR.Descripcion, MPR.Codigo ";
           
            $clase = 'reporteA';
            $panel = 'optionbody';
            $enlaceCod = 'Producto';
            $url = $enlace."?IngresoProducto=Detalle";
            $reporte = ListR2('',$sql, $CN, $clase,'', $url, $enlaceCod, $panel,'maalmacen','','');
            $s = "<div>".$btn.$reporte."</div>";
            WE($s);	
            
            break;
          case "Detalle":
            $CodigoProducto = get("Producto");

            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?IngresoProducto=Listado]optionbody}"; 
            $btn .= "Agregar]".$enlace."?IngresoProducto=Crear]optionbody}";            
            //$btn .= "Detalle]".$enlace."?IngresoProducto=Detalle]optionbody}";
            
            $btn = Botones($btn, 'botones1','');        
            $btn = tituloBtnPn("<span>Lista</span><p >DETALLE DE PRODUCTOS</p><div class='bicel'></div>",$btn,"300px","TituloA");
            
            $sql = "  SELECT MPR.Descripcion,MAL.Cantidad AS Cantidad,MAL.FechaRegistro,MAL.Codigo AS CodigoAjax
                      FROM maalmacen MAL
                      INNER JOIN maproducto AS MPR ON MAL.Producto=MPR.Codigo ";

            if($CodigoProducto){
                    $sql .= "WHERE MPR.Codigo = '{$CodigoProducto}' ";
            }
                                   
            $clase = 'reporteA';
            $panel = 'optionbody';
            $enlaceCod = 'CodigoAlmacen';
            $url = $enlace."?IngresoProducto=Editar";
            $reporte = ListR2('',$sql, $CN, $clase,'', $url, $enlaceCod, $panel,'maalmacen','','');
            $s = "<div>".$btn.$reporte."</div>";
            WE($s); 
            
            break;
        case "Crear":           

    
            $uRLForm  = "Buscar]" . $enlace . "?IngresoProducto=BuscarProveedor&transaccion=BUSCAR&Campo=Proveedor_FAlmacen_C]Proveedor_FAlmacen_B]F]}";            
            $form = c_form_adp("BUSCAR Proveedor ",$CN, "FBuscarProveedor", "CuadroA", '', $uRLForm, "", '');
            $form = "<div style='width:600px;'>" . $form . "</div>";
            $style = "top:0px;z-index:6;";
            $FBusqueda = search($form, "Proveedor_FAlmacen", $style);


            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?IngresoProducto=Listado]optionbody}";	
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Registrar</span><p >REGISTRO DE STOCK</p><div class='bicel'></div>",$btn,"50px","TituloA");            

            $tSelectD = array(
                'Producto'  => 'SELECT Codigo,Descripcion FROM maproducto'                        
            );

            $uRLForm = "Guardar]".$enlace."?metodo=FAlmacen&transaccion=INSERT]optionbody]F]}";
            $form = c_form_adp('', $CN,'FAlmacen', 'CuadroA', $path, $uRLForm, "", $tSelectD,'Codigo');
            $form = "<div style='width:100%;'>".$FBusqueda.$btn.$form."</div>";
            $s = "<div class= 'PanelPadding'>".$form."</div>";             
            WE($s);
        break;    
        case "Editar":
            $CodigoAlmacen = get("CodigoAlmacen");        

            $uRLForm  = "Buscar]" . $enlace . "?IngresoProducto=BuscarProveedor&transaccion=BUSCAR&Campo=Proveedor_FAlmacen_C]Proveedor_FAlmacen_B]F]}";            
            $form = c_form_adp("BUSCAR Proveedor ",$CN, "FBuscarProveedor", "CuadroA", '', $uRLForm, "", '');
            $form = "<div style='width:600px;'>" . $form . "</div>";
            $style = "top:0px;z-index:6;";
            $FBusqueda = search($form, "Proveedor_FAlmacen", $style);

            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?IngresoProducto=Listado]optionbody}"; 
            $btn = Botones($btn, 'botones1','');        
            $btn = tituloBtnPn("<span>Registrar</span><p> REGISTRO DE STOCK</p><div class='bicel'></div>",$btn,"50px","TituloA");
        
            $tSelectD = array(
                'Producto'  => 'SELECT Codigo,Descripcion FROM maproducto',                        
                'Proveedor'  => "SELECT Codigo,CONCAT(Nombre,'',ApellidoPaterno) AS Nombres FROM maproveedor"
            );
         
            $uRLForm = "Guardar]".$enlace."?metodo=FAlmacen&transaccion=UPDATE&CodigoAlmacen={$CodigoAlmacen}]optionbody]F]}";
            $form = c_form_adp('',$CN,'FAlmacen', 'CuadroA', $path, $uRLForm, $CodigoAlmacen, $tSelectD,'Codigo');
        
            $form = "<div style='width:100%;'>".$FBusqueda.$btn.$form."</div>";
            $s = "<div class= 'PanelPadding'>".$form."</div>";             
            WE($s);
        break;  
       case 'BuscarProveedor':
       
        $Nombre = post("Nombre");
            
        $sql = "SELECT Nombre,ApellidoPaterno,RazonSocial,Tipo,Codigo AS CodigoAjax 
                FROM maproveedor WHERE Nombre LIKE '%{$Nombre}%'";
    
        $clase = 'reporteA';
        $enlaceCod = 'perfil_usuarios_entidad_cod';
        $url = $enlace . "?AdminPerfilEditar=editaReg";
        $panel = protect(get("Campo"));
        $reporte = ListR2("", $sql, $CN, $clase, '', $url, $enlaceCod, $panel, 'Proveedor_FAlmacen', 'Buscar', '');
       
        WE($reporte);

        break;
 
    }
    
}	
