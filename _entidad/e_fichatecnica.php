<?php
require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');
#require_once('../_classDatos/cd_producto.php');


error_reporting(E_ERROR);
$enlace = "./_entidad/e_fichatecnica.php";
$CN = GestionDC();


if (get('Ficha')){ Ficha(get('Ficha'));}

if (get("metodo") != ""){
    
    if(get("TipoDato") == "archivo"){
    }
    
    function p_interno($codigo,$campo){
        
        if(get("metodo") == "FFichatecnicaDet"){
            $CodigoFicha=get('CodigoFicha');
            switch ($campo) {
                case 'FichaTecnica':
                    $valor = $CodigoFicha;
                    break;                
                default:
                     $valor = "";
                    break;             
            }
             return $valor;                 
        }
            
    }
    function p_before($codigo){
        global $CN;
        if(get("metodo") == "FFichatecnicaDet"){  
            $CodigoFicha=get('CodigoFicha');         
            $SqlDetFicha = "SELECT Cantidad,Producto FROM defichatecnica WHERE Codigo={$codigo};";
            $row = fetch($SqlDetFicha);            
            $Cantidad = $row['Cantidad'];
            $Producto = $row['Producto'];
            $SqlProducto = "SELECT Codigo,Precio FROM maproducto WHERE Codigo={$row['Producto']} ;";
            $row = fetch($SqlProducto);        
            $Precio = $row['Precio'];
            $Importe = $Cantidad*$Precio;
            $SqlUpdate = "Update defichatecnica SET PrecioUnitario={$Precio},Importe={$Importe} WHERE Codigo={$codigo} ";             
            xSQL2($SqlUpdate, $CN);

            $SqlInsert = "INSERT INTO maalmacen(CantidadSalida,Producto,Usuario,TipoIngreso) VALUES ($Cantidad,$Producto,'admin',2) ";
            xSQL2($SqlInsert, $CN);

            Ficha('CrearDetalle',$CodigoFicha,'');
        }


    }			
    if(get("TipoDato") == "texto"){
        if(get("transaccion") == "UPDATE"){
            
            if(get("metodo") == "FFichatecnicaDet"){
                p_gf_udp("FFichatecnicaDet", $CN, "", 'Codigo');               
                Ficha("Listado");
            }    
            if(get("metodo") == "FFichaTecnica"){
                $CodigoFicha = get("CodigoFicha");             
                p_gf_udp("FFichaTecnica", $CN,$CodigoFicha, 'Codigo');
                Ficha("Listado");
            }            
        }

        if(get("transaccion") == "INSERT"){

            if(get("metodo") == "FFichatecnicaDet"){                
                p_gf_udp("FFichatecnicaDet", $CN, "", 'Codigo');                               
            }                       

            if(get("metodo") == "FFichaTecnica"){
                p_gf_udp("FFichaTecnica", $CN, "", 'Codigo');               
                Ficha("Listado");
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
function Ficha($Arg,$Ficha = null, $DetFicha = null){
    global $CN, $enlace;
    switch ($Arg) {
        case "Listado":
            $btn = "Agregar]".$enlace."?Ficha=Crear]optionbody}";
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Registro</span><p >FICHAS</p><div class='bicel'></div>",$btn,"70px","TituloA");
            
            $sql = "SELECT MFT.Fecha, CONCAT(MU.ApellidoPaterno,' ',MU.Nombre) AS Usuario ,MC.RazonSocial,MFT.Codigo AS CodigoAjax FROM mafichatecnica MFT
                    INNER JOIN macliente MC ON MFT.Cliente = MC.Codigo
                    INNER JOIN ma_usuario MU ON MFT.Tecnico = MU.Codigo";
           
            $clase = 'reporteA';
            $panel = 'optionbody';
            $enlaceCod = 'CodigoFicha';
            $url = $enlace."?Ficha=Editar";
            $reporte = ListR2('',$sql, $CN, $clase,'', $url, $enlaceCod, $panel,'maalmacen','','');
            $s = "<div>".$btn.$reporte."</div>";
            WE($s);	
            
            break;
        case "Crear":  

            $uRLForm  = "Buscar]" . $enlace . "?Ficha=BuscarCliente&transaccion=BUSCAR&Campo=Cliente_FFichaTecnica_C]Cliente_FFichaTecnica_B]F]}";            
            $form = c_form_adp("BUSCAR Cliente ",$CN, "FBuscarCliente", "CuadroA", '', $uRLForm, "", '');
            $form = "<div style='width:600px;'>" . $form . "</div>";
            $style = "top:0px;z-index:6;";
            $FBusquedaProveedor = search($form, "Cliente_FFichaTecnica", $style);

           $uRLForm  = "Buscar]" . $enlace . "?Ficha=BuscarUsuario&transaccion=BUSCAR&Campo=Tecnico_FFichaTecnica_C]Tecnico_FFichaTecnica_B]F]}";            
            $form = c_form_adp("BUSCAR Técnico ",$CN, "FBuscarUsuario", "CuadroA", '', $uRLForm, "", '');
            $form = "<div style='width:600px;'>" . $form . "</div>";
            $style = "top:0px;z-index:6;";
            $FBusquedaProducto = search($form, "Tecnico_FFichaTecnica", $style);


            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Ficha=Listado]optionbody}";	
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Registrar</span><p >FICHA</p><div class='bicel'></div>",$btn,"50px","TituloA");            

              $tSelectD = array(
                'Tecnico'  => "SELECT Codigo,CONCAT(ApellidoPaterno,' ',Nombre) Descripcion FROM ma_usuario",                        
                'Cliente'  => 'SELECT Codigo,RazonSocial FROM macliente'                        
            );
            
            $uRLForm = "Guardar]".$enlace."?metodo=FFichaTecnica&transaccion=INSERT]optionbody]F]}";
            $form = c_form_adp('', $CN,'FFichaTecnica', 'CuadroA', $path, $uRLForm, "'".$codEntidad."'", $tSelectD,'Codigo');
            $form = "<div style='width:100%;'>".$FBusquedaProducto.$FBusquedaProveedor.$btn.$form."</div>";
            $s = "<div class= 'PanelPadding'>".$form."</div>";             
            WE($s);
        break;    
        case "Editar":
            $CodigoFicha = get("CodigoFicha");

            $uRLForm  = "Buscar]" . $enlace . "?Ficha=BuscarCliente&transaccion=BUSCAR&Campo=Cliente_FFichaTecnica_C]Cliente_FFichaTecnica_B]F]}";            
            $form = c_form_adp("BUSCAR Cliente ",$CN, "FBuscarCliente", "CuadroA", '', $uRLForm, "", '');
            $form = "<div style='width:600px;'>" . $form . "</div>";
            $style = "top:0px;z-index:6;";
            $FBusquedaProveedor = search($form, "Cliente_FFichaTecnica", $style);

            $uRLForm  = "Buscar]" . $enlace . "?Ficha=BuscarUsuario&transaccion=BUSCAR&Campo=Tecnico_FFichaTecnica_C]Tecnico_FFichaTecnica_B]F]}";            
            $form = c_form_adp("BUSCAR Técnico ",$CN, "FBuscarUsuario", "CuadroA", '', $uRLForm, "", '');
            $form = "<div style='width:600px;'>" . $form . "</div>";
            $style = "top:0px;z-index:6;";
            $FBusquedaProducto = search($form, "Tecnico_FFichaTecnica", $style);

            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Ficha=Listado]optionbody}"; 
            $btn = Botones($btn, 'botones1','');        
            $btn = tituloBtnPn("<span>Registrar</span><p> FICHA</p><div class='bicel'></div>",$btn,"50px","TituloA");

             $tSelectD = array(
                'Tecnico'  => "SELECT Codigo,CONCAT(ApellidoPaterno,' ',Nombre) Descripcion FROM ma_usuario",                        
                'Cliente'  => 'SELECT Codigo,RazonSocial FROM macliente'                        
            );
            
        
            $uRLForm = "Guardar]".$enlace."?metodo=FFichaTecnica&transaccion=UPDATE&CodigoFicha={$CodigoFicha}]optionbody]F]}";
            $uRLForm .= "Detalle]".$enlace."?Ficha=CrearDetalle&CodigoFicha={$CodigoFicha}]optionbody]F]}";

            $form = c_form_adp('',$CN,'FFichaTecnica', 'CuadroA', $path, $uRLForm, $CodigoFicha, $tSelectD,'Codigo');
            $form = "<div style='width:100%;'>".$FBusquedaProducto.$FBusquedaProveedor.$btn.$form."</div>";
            $s = "<div class= 'PanelPadding'>".$form."</div>";             
            WE($s);
        break;     
        case 'BuscarCliente':
       
            $RazonSocial = post("RazonSocial");                
            $sql = "SELECT RazonSocial,Nombre,ApellidoPaterno,ApellidoPaterno,Codigo AS CodigoAjax 
                   FROM macliente WHERE RazonSocial LIKE '%{$RazonSocial}%'";        
            $clase = 'reporteA';
            $enlaceCod = 'perfil_usuarios_entidad_cod';
            $url = $enlace . "?AdminPerfilEditar=editaReg";
            $panel = protect(get("Campo"));
            $reporte = ListR2("", $sql, $CN, $clase, '', $url, $enlaceCod, $panel, 'Cliente_FFichaTecnica', 'Buscar', '');
           
            WE($reporte);

        break;

        case 'BuscarUsuario':                
            $Descripcion = post("Nombre");
                
            $sql = "SELECT ApellidoPaterno,Nombre, Codigo AS CodigoAjax 
                    FROM ma_usuario
                    WHERE ApellidoPaterno LIKE '%{$Descripcion}%' OR Nombre LIKE '%{$Descripcion}%'";
            $clase = 'reporteA';
            $enlaceCod = 'perfil_usuarios_entidad_cod';
            $url = $enlace . "?AdminPerfilEditar=editaReg";
            $panel = protect(get("Campo"));        
            $reporte = ListR2("", $sql, $CN, $clase, '', $url, $enlaceCod, $panel, 'Tecnico_FFichaTecnica', 'Buscar', '');
           
            WE($reporte);

        break;
        case "CrearDetalle":
            
            $CodigoFicha = (!get("CodigoFicha")?$Ficha:get("CodigoFicha"));                    
            
            $uRLForm  = "Buscar]" . $enlace . "?Ficha=BuscarProducto&transaccion=BUSCAR&Campo=Producto_FFichatecnicaDet_C]Producto_FFichatecnicaDet_B]F]}";            
            $form = c_form_adp("BUSCAR Producto ",$CN, "FBuscarProducto", "CuadroA", '', $uRLForm, "", '');
            $form = "<div style='width:600px;'>" . $form . "</div>";
            $style = "top:0px;z-index:6;";
            $FBusqueda = search($form, "Producto_FFichatecnicaDet", $style);

            //$btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Ficha=Editar&CodigoFicha={$CodigoFicha}]optionbody}";   
            //$btn = Botones($btn, 'botones1','');        
            $btn = tituloBtnPn("<span>Registrar</span><p >REGISTRO DE FICHA</p><div class='bicel'></div>",$btn,"50px","TituloA");            

            $tSelectD = array(
                'Producto'  => 'SELECT Codigo,Descripcion FROM maproducto'                        
            );

            $sql = "SELECT MP.CodigoProducto,MP.Descripcion,DFT.Cantidad,DFT.PrecioUnitario,DFT.Importe,DFT.Codigo AS CodigoAjax 
                    FROM defichatecnica DFT
                    INNER JOIN maproducto MP ON DFT.Producto = MP.Codigo 
                    WHERE FichaTecnica = {$CodigoFicha} ";
           
            $clase = 'reporteA';
            $panel = 'optionbody';
            $enlaceCod = 'CodigoDetFicha';
            $url = $enlace."?Ficha=EditarDetalle&CodigoFicha={$CodigoFicha}";
            $reporte = ListR2('',$sql, $CN, $clase,'', $url, $enlaceCod, $panel,'defichatecnica','','');
            $s = "<div>".$btn.$reporte."</div>";

            $uRLForm = "Guardar]".$enlace."?metodo=FFichatecnicaDet&transaccion=INSERT&CodigoFicha={$CodigoFicha}]optionbody]F]}";
            $form = c_form_adp('', $CN,'FFichatecnicaDet', 'CuadroA', $path, $uRLForm, "", $tSelectD,'Codigo');
            $form = "<div style='width:100%;'>".$FBusqueda.$btn.$form."</div>";
            $s = "<div class= 'PanelPadding'>".$form.$s."</div>";             
            WE($s);
        break;
        case "EditarDetalle":
            $CodigoFicha = (!get("CodigoFicha")?$Ficha:get("CodigoFicha"));  
            //$CodigoFicha = get("CodigoFicha");        
            $CodigoDetFicha = (!get("CodigoDetFicha")?$DetFicha:get("CodigoDetFicha"));  
            //$CodigoDetFicha = get('CodigoDetFicha');
            $uRLForm  = "Buscar]" . $enlace . "?Ficha=BuscarProducto&transaccion=BUSCAR&Campo=Producto_FFichatecnicaDet_C]Producto_FFichatecnicaDet_B]F]}";            
            $form = c_form_adp("BUSCAR Producto ",$CN, "FBuscarProducto", "CuadroA", '', $uRLForm, "", '');
            $form = "<div style='width:600px;'>" . $form . "</div>";
            $style = "top:0px;z-index:6;";
            $FBusqueda = search($form, "Producto_FFichatecnicaDet", $style);

           // $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Ficha=Editar&CodigoFicha={$CodigoFicha}]optionbody}";   
           // $btn = Botones($btn, 'botones1','');        
            $btn = tituloBtnPn("<span>Registrar</span><p >REGISTRO DE FICHA</p><div class='bicel'></div>",$btn,"50px","TituloA");            

            $tSelectD = array(
                'Producto'  => 'SELECT Codigo,Descripcion FROM maproducto'                        
            );

            $sql = "SELECT MP.CodigoProducto,MP.Descripcion,DFT.Cantidad,DFT.PrecioUnitario,DFT.Importe 
                    FROM defichatecnica DFT
                    INNER JOIN maproducto MP ON DFT.Producto = MP.Codigo";
           
            $clase = 'reporteA';
            $panel = 'optionbody';
            $enlaceCod = 'CodigoDetFicha';
            $url = $enlace."?Ficha=CrearDetalle&CodigoFicha={$CodigoFicha}";
            $reporte = ListR2('',$sql, $CN, $clase,'', $url, $enlaceCod, $panel,'defichatecnica','','');
            $s = "<div>".$btn.$reporte."</div>";



            $uRLForm = "Guardar]".$enlace."?metodo=FFichatecnicaDet&transaccion=INSERT&CodigoFicha={$CodigoFicha}]optionbody]F]}";
            $form = c_form_adp('', $CN,'FFichatecnicaDet', 'CuadroA', $path, $uRLForm, "", $tSelectD,'Codigo');
            $form = "<div style='width:100%;'>".$FBusqueda.$btn.$form."</div>";
            $s = "<div class= 'PanelPadding'>".$form.$s."</div>";             
            WE($s);

        break;
        case 'BuscarProducto':                
            $Descripcion = post("Nombre");
                
            $sql = "SELECT CodigoProducto,Descripcion,TipoProducto,Codigo AS CodigoAjax FROM maproducto
                    WHERE Descripcion LIKE '%{$Descripcion}%'";
            $clase = 'reporteA';
            $enlaceCod = 'perfil_usuarios_entidad_cod';
            $url = $enlace . "?AdminPerfilEditar=editaReg";
            $panel = protect(get("Campo"));        
            $reporte = ListR2("", $sql, $CN, $clase, '', $url, $enlaceCod, $panel, 'Producto_FFichatecnicaDet', 'Buscar', '');
           
            WE($reporte);

        break;


    }
    
}	
