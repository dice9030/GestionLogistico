<?php

session_start();

require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');
//require_once('../_vistas/gad_verify.php');

//error_reporting(E_ERROR);

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
if (!defined('BASE_PATH')) {
    define('BASE_PATH', filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . DS);
}
if (!defined('BASE_PATH')) {
    define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . DS);
}

$enlace = "./_vistas/adminTablasForms.php";
$vConex = conexSys();
$cnOwl = conexSys();
$FechaHora = FechaHoraSrv();

///////////////////////////////
$enterprise_user = $_SESSION["enterprise_user"];
$url_id = $_SESSION["url_id"];
$image_logo_url = $_SESSION["logo_url"];

if (get('ImportarExportar') != '') {
    ImportarExportar(get('ImportarExportar'));
}
if (get('muestra') != '') {
    detalleForm(get('muestra'));
}
if (get('accionCT') != '') {
    vistaCT(get('accionCT'));
}
if (get('actualizaTabla') != '') {
    actualizaTabla(get('actualizaTabla'));
}
if (get('accionDA') != '') {
    datosAlternos(get('accionDA'));
}
if (get('TipoCampoHtml') != '') {
    TipoCampoHtml(get('TipoCampoHtml'));
}
if (get('accionForm') != '') {
    EliminaCampos(get('accionForm'));
}
if (get('generarScrip') != '') {
    GeneraScript(get('codigoForm'));
}
if (get('Formularios') != '') {
    Formularios(get('Formularios'));
}
if (get('Reportes') != '') {
    Reportes(get('Reportes'));
}
if (get('Tablas') != '') {
    Tablas(get('Tablas'));
}
if (get('Tutorial') != '') {
    Tutorial(get('Tutorial'));
}
if (get('Soporte') != '') {
    Soporte(get('Soporte'));
}
///////////////////////----------
if(get('TipoProyectos')!=''){
 TipoProyectos(get('TipoProyectos'));
}
/////////////////////-----
if (get('ModTabla') != '') {
    ModTabla(get('ModTabla'));
}
if (get('MenuPerfil') != '') {
    MenuPerfil(get('MenuPerfil'));
}
if (get('Perfil') != '') {
    Perfil(get('Perfil'));
}
if (get('BDatos') != '') {
    BDatos(get('BDatos'));
}
if (get('FTrabajo') != '') {
    FTrabajo(get('FTrabajo'));
}
if (get('ProcesosSistema') != '') {
    ProcesosSistema(get('ProcesosSistema'));
}
if (get('ActualizaComponentes') != '') {
    ActualizaComponentes(get('ActualizaComponentes'));
}
if (get('ProcesosTab') != '') {
    ProcesosTab(get('ProcesosTab'));
}

if (get('BuscarFormulario') != '') {
    BuscarFormulario(get('BuscarFormulario'));
}
if (get("metodo") != "") {
    if (get("TipoDato") == "archivo") {
        if (get("metodo") == "register_tutorial") {
            $file = upload($enterprise_user, $enterprise_user, $vConex);
            echo json_encode($file);
        }
        if (protect( get( "metodo" ) )== "FImportarFormulario" ) {
            $file = upload( $usuarioEntidad, $entidadCreadora, $vConex );
            echo json_encode($file);
        }

    }

    function p_interno($codigo, $campo) {
        global $FechaHora, $vConex;

        if (get("metodo") == "register_tutorial") {
            //get data tutorial
            $path = post("path");

            if (!$path) {
                W(MsgE("Debes cargar un documento"));
                Tutorial("form");
            }

            $extension = pathinfo($path, PATHINFO_EXTENSION);

            $Q_tutorial = "
            SELECT _order 
            FROM tutorial
            ORDER BY _order DESC";

            $last_tutorial = fetchOne($Q_tutorial, $vConex);
            $_order = (int) $last_tutorial->_order;
            $_order++;

            if ($campo == "extension") {
                $valor = "'{$extension}'";
            } else if ($campo == "date_create") {
                $valor = "'{$FechaHora}'";
            } else if ($campo == "_order") {
                $valor = $_order;
            }
            return $valor;
        }

        if (protect(get("metodo")) == "ga_area") {
            if ($campo == "EntidadCreadora") {
                $valor = "'Sys'";
            } else {
                $valor = "";
            }
            return $valor;
        }

        if (protect(get("metodo")) == "reclamo_atencion_caso") {
            if ($campo == "EntidadCreadora") {
                $valor = "'Sys'";
            } else {
                $valor = "";
            }
            return $valor;
        }

        if (get("metodo") == "SysFomr1") {
            if ($campo == "Descripcion") {
                $vcamp = post($campo);
                $valor = "'Form_" . $vcamp . "' ";
            } elseif ($campo == "FechaHoraCreacion") {
                $valor = "'" . $FechaHora . "'";
            } else {
                $valor = "";
            }
            return $valor;
        }

        if (get("metodo") == "sysTabletDet") {
            if ($campo == "sys_tabla") {
                $valor = "'" . get("codigoSysTabla") . "'";
            } else {
                $valor = "";
            }
            return $valor;
        }

        if (get("metodo") == "sysformdet2") {
            if ($campo == "Form") {
                $valor = "'" . get("codigoForm") . "'";
            } else {
                $valor = "";
            }
            return $valor;
        }
        if (get("metodo") == "menu_empresa_det") {
            if ($campo == "Menu") {
                $valor = "'" . get("Menu") . "'";
            } else {
                $valor = "";
            }
            return $valor;
        }

        if (get("metodo") == "sys_tabla1") {
            if ($campo == "FechaRegistro") {
                $valor = "'" . $FechaHora . "'";
            } else {
                $valor = "";
            }
            return $valor;
        }
    }

    function p_before($codigo) {


        if(get("metodo") ==  "FImportarFormulario"){
            pro_sysform_file($codigo);
        }

    }

    if (get("TipoDato") == "texto") {
        if (get("transaccion") == "UPDATE") {
            if (get("metodo") == "SysFormDet1") {
                p_gf("SysFormDet1", $vConex, '"' . get("codformdet") . '"');
            }
            if (get("metodo") == "menu_empresa") {
                p_gf("menu_empresa", $vConex, get("codigo"));
                MenuPerfil("Listado");
            }
            if (get("metodo") == "menu_empresa_det") {
                p_gf("menu_empresa_det", $vConex, get("codigo"));
                MenuPerfil("Detalle");
            }
            if (get("metodo") == "sysformdet2") {
                p_gf("sysformdet2", $vConex, get("codformdet"));
                detalleForm('detalle');
            }
            if (get("metodo") == "sys_tipo_input") {
                p_gf("sys_tipo_input", $vConex, get("codigo"));
                datosAlternos("CreacionTipoDato");
            }

            if (get("metodo") == "sector_edit") {
                p_gf_udp('sector_edit', $vConex, get('Codigo'), 'CodPrograma');
                datosAlternos("Sectores");
            }
            if (get("metodo") == "pais_edit") {
                p_gf_udp('pais_edit', $vConex, get('Codigo'), 'CodPais');
                datosAlternos("Paises");
            }
            if (get("metodo") == "categoria_edit") {
                p_gf_udp('categoria_edit', $vConex, get('Codigo'), 'CategoriCod');
                datosAlternos("Categorias");
            }
            if (get("metodo") == "sectores_edit") {
                p_gf_udp('sectores_edit', $vConex, get('Codigo'), 'IdSectores');
                datosAlternos("TipoSectores");
            }
            if (get("metodo") == "tipoproducto_edit") {
                p_gf_udp('tipoproducto_edit', $vConex, get('Codigo'), 'TipoProductoId');
                datosAlternos("TipoProducto");
            }

            if (get("metodo") == "tipo_pregunta_edit") {
                p_gf_udp('tipo_pregunta_edit', $vConex, get('Codigo'), 'Codigo');
                datosAlternos("TipoPregunta");
            }

            if (get("metodo") == "tipoevaluacion_edit") {
                p_gf_udp('tipoevaluacion_edit', $vConex, get('Codigo'), 'Codigo');
                datosAlternos("TipoEvaluacion");
            }

            if (get("metodo") == "vcmaestroservidor") {
                p_gf_udp('vc_maestroservidor', $vConex, get('Codigo'), 'Codigo');
                datosAlternos("ServerVC");
            }
            if (get("metodo") == "moneda_edit") {
                p_gf_udp('moneda_edit', $vConex, get('Codigo'), 'Codigo');
                datosAlternos("Moneda");
            }
            if (get("metodo") == "ga_area") {
                p_gf_udp('ga_area', $vConex, get('Codigo'), 'Codigo');
                datosAlternos("Area");
            }
            if (get("metodo") == "reclamo_atencion_caso") {
                p_gf_udp('reclamo_atencion_caso', $vConex, get('Codigo'), 'Codigo');
                datosAlternos("Reclamo");
            }
            if (get("metodo") == "departamento_edit") {
                p_gf_udp('departamento_edit', $vConex, get('Codigo'), 'Codigo');
                datosAlternos("Departamentos");
            }
            if (get("metodo") == "empresa_edit") {
                p_gf_udp('empresa_edit', $vConex, get('Codigo'), 'CodEmpresa');
                datosAlternos("Empresas");
            }
            if (get("metodo") == "sys_tipo_ouput1") {
                p_gf("sys_tipo_ouput1", $vConex, get("codigo"));
                TipoCampoHtml("Lista");
            }
            if (get("metodo") == "sys_base_datos") {
                p_gf("sys_base_datos", $vConex, get("codigo"));
                BDatos("Lista");
            }
            if (get("metodo") == "menu_empresa_perfil_edit") {
                p_gf("menu_empresa_perfil_edit", $vConex, get("Codigo"));
                MenuPerfil("detallePerfilView");
            }
            if (get("metodo") == "empresa_corporacion") {
                p_gf("empresa_corporacion", $vConex, get("Codigo"));
                Soporte("view");
            }
		
			  if (get("metodo") == "empresa_TipoProyecto") {
                p_gf("empresa_TipoProyecto", $vConex, get("Codigo"));
                TipoProyectos("Tipos");
            }
		
		       if (get("metodo") == "menu_empresa_perfil_edit_empresa") {
                p_gf("menu_empresa_perfil_edit", $vConex, get("Codigo"));
                MenuPerfil("detallePerfilViewEmprPerfil");
            }
            if (get("metodo") == "sysTabletDet") {
                actualizaCampo();
            }
            if (get("metodo") == "update_tutorial") {
                $tutorial_id = get('tutorial_id');
                $_order = (int) post("_order");

                $Q_id = "SELECT Codigo
                FROM tutorial 
                WHERE _order = {$_order}";

                $tutorial_id_change = (int) fetchOne($Q_id, $vConex)->Codigo;

                $Q_order = "SELECT _order
                FROM tutorial 
                WHERE Codigo = {$tutorial_id}";

                $_order_change = (int) fetchOne($Q_order, $vConex)->_order;

  Update("tutorial", [ "_order" => $_order], [ "Codigo" => $tutorial_id], $vConex);

  Update("tutorial", [ "_order" => $_order_change], [ "Codigo" => $tutorial_id_change], $vConex);

                p_gf_udp('update_tutorial', $vConex, $tutorial_id, 'Codigo');
                Tutorial("view");
            }

                ######################################
                # Cambiar correo de usuario;
                ######################################
                if (get("metodo") == "Cambiarusuarios") {
                    $email = $_POST['Usuario'];
                    $OrigenEmail = $_POST['IdUsuario'];
                    W($email.$OrigenEmail);
                    //WE("HOLA".$email);
                     $Sql = " SELECT Usuario FROM usuarios WHERE Usuario='{$email}'  ";
				     $rg = fetch($Sql);
                     $NuevoUser = $rg["Usuario"];
					 
					 $Sql2 = " SELECT Usuario FROM usuarios WHERE Usuario='{$OrigenEmail }'  ";
				     $rg = fetch($Sql2);
                     $OrigenEmail = $rg["Usuario"];
		    
         			if(empty($NuevoUser)  &&  !empty($OrigenEmail)){
					
                    $SqlUpdate = "  UPDATE profesores SET Usuario='{$email}Profesor'  WHERE  Usuario LIKE '{$OrigenEmail}Profesor' ";
                    W($SqlUpdate.'<br>');
                    xSQL2($SqlUpdate,$vConex);
                    $SqlUpdate = "  UPDATE matriculas SET Cliente='{$email}Alumno' WHERE Cliente  LIKE '{$OrigenEmail}Alumno' ";
                    W($SqlUpdate.'<br>');
                    xSQL2($SqlUpdate,$vConex);
                    $SqlUpdate = "  UPDATE usuarios SET Usuario='{$email}' WHERE Usuario LIKE '{$OrigenEmail}' ";
                    W($SqlUpdate.'<br>');
                    xSQL2($SqlUpdate,$vConex);
                    $SqlUpdate = "  UPDATE usuarios SET IdUsuario='{$email}Profesor' WHERE IdUsuario LIKE '{$OrigenEmail}Profesor' ";
                    W($SqlUpdate.'<br>');
                    xSQL2($SqlUpdate,$vConex);
                    $SqlUpdate = "  UPDATE usuarios SET IdUsuario='{$email}Alumno' WHERE IdUsuario LIKE '{$OrigenEmail}Alumno' ";
                    W($SqlUpdate.'<br>');
                    xSQL2($SqlUpdate,$vConex);
                    $SqlUpdate = "  UPDATE usuarios SET CodigoParlante='{$email}' WHERE CodigoParlante LIKE '{$OrigenEmail}' ";
                    W($SqlUpdate.'<br>');
                    xSQL2($SqlUpdate,$vConex);
                    $SqlUpdate = "  UPDATE alumnos SET Usuario='{$email}Alumno' WHERE Usuario LIKE '{$OrigenEmail}Alumno' ";
                    W($SqlUpdate.'<br>');
                    xSQL2($SqlUpdate,$vConex);
                    $SqlUpdate = "  UPDATE alumnos SET Email='{$email}' WHERE Email LIKE '{$OrigenEmail}' ";
                    W($SqlUpdate.'<br>');
                    xSQL2($SqlUpdate,$vConex);
                    $SqlUpdate = "  UPDATE usuario_entidad SET Usuario='{$email}' WHERE Usuario LIKE '{$OrigenEmail}' ";
                    W($SqlUpdate.'<br>');
                    xSQL2($SqlUpdate,$vConex);
					$SqlUpdate = "  UPDATE eltransrespuesta SET Usuario='{$email}Alumno' WHERE Usuario LIKE '{$OrigenEmail}Alumno' ";
                    W($SqlUpdate.'<br>');
                    xSQL2($SqlUpdate,$vConex);
                    $SqlUpdate = "  UPDATE eltransrespuesta_cab SET Alumno='{$email}Alumno' WHERE Alumno LIKE '{$OrigenEmail}Alumno' ";
                    W($SqlUpdate.'<br>');
                    xSQL2($SqlUpdate,$vConex);
                    $SqlUpdate = "  UPDATE elasistencia SET Alumno='{$email}Alumno' WHERE Alumno LIKE '{$OrigenEmail}Alumno' ";
                    W($SqlUpdate.'<br>');
                    xSQL2($SqlUpdate,$vConex);
                    $SqlUpdate = "  UPDATE elevaluacionalumno SET Alumno='{$email}Alumno' WHERE Alumno LIKE '{$OrigenEmail}Alumno' ";
                    W($SqlUpdate.'<br>');
                    xSQL2($SqlUpdate,$vConex);
                    $SqlUpdate = "  UPDATE elalumnotareas SET Alumno='{$email}Alumno' WHERE Alumno LIKE '{$OrigenEmail}Alumno' ";
                    W($SqlUpdate.'<br>');
                    xSQL2($SqlUpdate,$vConex);
                    $SqlUpdate = "  UPDATE cursos SET Entidad='{$email}Profesor' WHERE Entidad LIKE '{$OrigenEmail}Profesor'";
                    W($SqlUpdate.'<br>');
				
                    xSQL2($SqlUpdate,$vConex);
					$SqlUpdate = "  UPDATE almacen SET Origen='{$email}Profesor'  WHERE Origen LIKE '{$OrigenEmail}Profesor'";
                    W($SqlUpdate.'<br>');
                    xSQL2($SqlUpdate,$vConex);
					
                     }ELSE {
					 
					 W(" <BR> <DIV  STYLE='COLOR:RED;'> <B>ASEGURESE DE HABER ESCRITO BIEN LOS CORREOS</B></DIV>");
					 
					 }


                }





            }

        if (get("transaccion") == "INSERT") {
            if (get("metodo") == "SysFomr1") {
                pro_sysform();
            }
            if (get("metodo") == "sys_tabla1") {
                pro_systabla();
            }
            if (get("metodo") == "sysTabletDet") {
                pro_sysTabletDet();
            }
            if (get("metodo") == "sys_tipo_input") {
                p_gf("sys_tipo_input", $vConex, "");
                datosAlternos("CreacionTipoDato");
            }
            if (get("metodo") == "sys_tipo_ouput1") {
                p_gf("sys_tipo_ouput1", $vConex, "");
                TipoCampoHtml("Lista");
            }
            if (get("metodo") == "sysformdet2") {
                p_gf("sysformdet2", $vConex, "");
                detalleForm("detalle");
            }
            if (get("metodo") == "menu_empresa") {
                p_gf("menu_empresa", $vConex, "");
                MenuPerfil("Listado");
            }
            if (get("metodo") == "menu_empresa_det") {
                p_gf("menu_empresa_det", $vConex, "");
                MenuPerfil("Detalle");
            }
            if (get("metodo") == "sys_base_datos") {
                p_gf("sys_base_datos", $vConex, "");
                BDatos("Lista");
            }
            if (get("metodo") == "form_FTrabajo") {
                if (crear_ptrabajo(post('Nombre'))) {
                    p_gf("form_FTrabajo", $vConex, "");
                }
                FTrabajo("Lista");
            }
            if (get("metodo") == "pais_edit") {
                p_gf_udp('pais_edit', $vConex, get('Codigo'), 'CodPais');
                datosAlternos("Paises");
            }
            if (get("metodo") == "categoria_edit") {
                p_gf_udp('categoria_edit', $vConex, get('Codigo'), 'CategoriCod');
                datosAlternos("Categorias");
            }
            if (get("metodo") == "sectores_edit") {
                p_gf_udp('sectores_edit', $vConex, get('Codigo'), 'IdSectores');
                datosAlternos("TipoSectores");
            }
            if (get("metodo") == "tipoevaluacion_edit") {
                p_gf_udp('tipoevaluacion_edit', $vConex, get('Codigo'), 'Codigo');
                datosAlternos("TipoEvaluacion");
            }
            if (get("metodo") == "moneda_edit") {
                p_gf_udp('moneda_edit', $vConex, get('Codigo'), 'Codigo');
                datosAlternos("Moneda");
            }
            if (get("metodo") == "ga_area") {
                p_gf_udp('ga_area', $vConex, get('Codigo'), 'Codigo');
                datosAlternos("Area");
            }
            if (get("metodo") == "reclamo_atencion_caso") {
                p_gf_udp('reclamo_atencion_caso', $vConex, get('Codigo'), 'Codigo');
                datosAlternos("Reclamo");
            }
            if (get("metodo") == "departamento_edit") {
                p_gf_udp('departamento_edit', $vConex, get('Codigo'), 'Codigo');
                datosAlternos("Departamentos");
            }

            if (get("metodo") == "tipoproducto_edit") {
                p_gf_udp('tipoproducto_edit', $vConex, get('Codigo'), 'TipoProductoId');
                datosAlternos("TipoProducto");
            }

            if (get("metodo") == "tipo_pregunta_edit") {
                p_gf_udp('tipo_pregunta_edit', $vConex, get('Codigo'), 'Codigo');
                datosAlternos("TipoPregunta");
            }
			
            if (get("metodo") == "register_tutorial") {
			//W("2222222222222222222222222222222222");
                p_gf('register_tutorial', $vConex, "");
                Tutorial("view");
            }
			
            if (get("metodo") == "empresa_corporacion") {
                p_gf('empresa_corporacion', $vConex, "");
                Soporte("view");
            }
			
		    if (get("metodo") == "empresa_TipoProyecto") {
                p_gf("empresa_TipoProyecto", $vConex, get("Codigo"));
                TipoProyectos("Tipos");
            }
	
            if (get("metodo") == "vcmaestroservidor") {
                //p_gf_udp('vc_maestroservidor', $vConex,"", 'Codigo');
                p_gf("vc_maestroservidor", $vConex, "");
                datosAlternos("ServerVC");
            }
            if (get("metodo") == "FImportarFormulario") {
                p_gf("FImportarFormulario", $vConex, "");
                pro_sysform_file();
            }


        }
}

function ConceptoEvaluacionXalumno($vConex, $CodCursoAlmacen,$Alumno){
					
		$sql = "SELECT 
		 ACTIVIDAD.Codigo  AS ActividadCod
		, RECURSO.Codigo AS CodRecurso
		, RECURSO.RecursoTipo 
		, CONFIG_CONCEPTO.Codigo AS ConfigConcepto		
		FROM elevaluaciondetallecurso AS ACTIVIDAD
		INNER JOIN elevaluacionconfcurso AS CONFIG_CONCEPTO  ON ACTIVIDAD.EvalConfigCurso = CONFIG_CONCEPTO.Codigo
		INNER JOIN elrecursoevaluacion AS RECURSO  ON ACTIVIDAD.Codigo = RECURSO.EvaluacionDetalleCurso 		
		INNER JOIN eltransrespuesta_cab AS ETRC ON RECURSO.Codigo = ETRC.Recurso
		WHERE CONFIG_CONCEPTO.Almacen = '$CodCursoAlmacen'  AND  ETRC.Alumno='$Alumno' "   ;
		
		$consultaB2 = Matris_Datos( $sql, $vConex );
		while ( $regB = mysql_fetch_array( $consultaB2 ) ) {
		   
			$RecursoTipo = $regB["RecursoTipo"];	// 1 = Examen , 2 = Cuestionario, 5 = ArchivoAdjunto	
			$CodRecurso = $regB["CodRecurso"];	
			$Actividad = $regB["ActividadCod"];	
		    
			      	if($RecursoTipo==1 || $RecursoTipo==5    ){
				     // W("RECURSO TIPO EXAMEN  .<BR>");
			           $vSQL1= "DELETE FROM eltransrespuesta WHERE  Usuario='{$Alumno}'   AND   RecursoEvaluacion='{$CodRecurso}'  ";
				       xSQL($vSQL1, $vConex); 
                       W($vSQL1);
					    
					  $vSQL2="DELETE FROM elevaluacionalumno WHERE  Alumno='{$Alumno}'   AND   EvalDetCurso='{$Actividad}' ";
				      xSQL($vSQL2, $vConex);
			          W($vSQL2);
					 
	                  $vSQL3="DELETE FROM eltransrespuesta_cab WHERE  Alumno='{$Alumno}'   AND   Recurso='{$CodRecurso}' ";
				      xSQL($vSQL3, $vConex);					   
					  W($vSQL3);
			
					} 		
		     
		 }
				 
	return true;	
}

    if (get("transaccion") == "DELETE") {

        if (get("metodo") == "usuarios") {
             EliminarUsuario();
        }
      ////////////////////////////////////////////////////////Eliminar eebok

      if (get("metodo") == "EliminarEbook") {

      
            $Alumno = post("Usuario");
            $Producto = post("IdCodCorrelativo");
			
			if( !empty($Alumno) AND $Producto != 0 ){
			
			$SQL=" SELECT Entidad FROM almacen WHERE AlmacenCod ={$Producto}   ";
			$rg = fetch($SQL);
			$Entidad = $rg["Entidad"];			 

///////////////////////////////////
            $delusuarios = "DELETE from usuarios WHERE Usuario='".$Alumno."' ";
            $afectados = xSQL($delusuarios, $vConex);
            W('response usuarios> :' . $afectados . '<br/>');
            W('usuarios> :' . $delusuarios . '<br/><br/>');

            $delusuario_det = "DELETE from usuario_entidad WHERE Usuario='".$Alumno."'  AND EntidadCreadora='{$Entidad}' ";
            $afectados = xSQL($delusuario_det, $vConex);
            W('response usuario_entidad> :' . $afectados . '<br/>');
            W('usuario_entidad> :' . $delusuario_det . '<br/><br/>');
			
			$delmatricula = "DELETE from matriculas WHERE Cliente='".$Alumno.'Alumno'."' AND Producto=".$Producto. "  and TipoAccesoMatricula = 'Ebook'   ";
            $afectados = xSQL($delmatricula, $vConex);
            W('response matriculas> :' . $afectados . '<br/>');
            W('matriculas> :' . $delmatricula . '<br/><br/>');

            $delalmacen_entidad = "DELETE from almacen_entidad WHERE Entidad='".$Alumno.'Alumno'."' AND   CodAlmacenContenedor ='".$Producto."'  ";
            $afectados = xSQL($delalmacen_entidad, $vConex);
            W('response almacen_entidad> :' . $afectados . '<br/>');
            W('almacen_entidad> :' . $delalmacen_entidad . '<br/><br/>');

            $delalumnos = "DELETE from alumnos WHERE Usuario='".$Alumno.'Alumno'."' OR Usuario='".$Alumno.'Profesor'."'";
            $afectados = xSQL($delalumnos, $vConex);
            W('response alumnos> :' . $afectados . '<br/>');
            W('alumnos> :' . $delalumnos . '<br/><br/>');

            $delprofesores = "DELETE from profesores WHERE Usuario='".$Alumno.'Alumno'."' OR Usuario='".$Alumno.'Profesor'."' ";
            $afectados = xSQL($delprofesores, $vConex);
            W('response profesores> :' . $afectados . '<br/>');
            W('profesores> :' . $delprofesores . '<br/><br/>');
			
	     	//aquiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii
			
              $email=$Alumno;
                $fields = array(
                    'action'        => urlencode("delUsuario"),
                    'email'         => urlencode($email)
                );

                $fields_string = '';
                foreach ($fields as $key => $value) {
                    $fields_string .= $key.'='.$value.'&';
                }
                rtrim($fields_string, '&');
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://owlecomerce.com/_vistas/Services/Usuarios.php");
                curl_setopt($ch, CURLOPT_POST, count($fields));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
                $result = curl_exec($ch);
                curl_close($ch);
				
			
				$MSJ=" <DIV STYLE='WIDTH: 600PX; HEIGHT:50PX ; BACKGROUND-COLOR: GREEN; COLOR: WHITE; PADDING-TOP:20PX   '>  <CENTER><B>Se ha eliminado el usuario correctamente ...!!  </B> </CENTER></DIV>  "; 		
				W($MSJ);
				datosAlternos("EliminarEbook");
				
			}else{
				$MSJ=" <DIV  STYLE='WIDTH: 600PX; HEIGHT:50PX ; BACKGROUND-COLOR: RED; COLOR: WHITE; PADDING-TOP:20PX '> <CENTER><B>  No se ingreso ningun dato..!!! </B></CENTER></DIV>  "; 		
				W($MSJ);
				
			   datosAlternos("EliminarEbook");
			
			}
      
        }
		
		
		
		
        if (get("metodo") == "matriculas") {

            $email = $_POST['Cliente'];

            $delmatriculas = "DELETE from matriculas WHERE Cliente='" . $email . "' OR Cliente='" . $email . "Alumno'";
            $afectados = xSQL($delmatriculas, $vConex);
            W('response matriculas> :' . $afectados . '<br/>');
            W('matriculas> :' . $delmatriculas . '<br/><br/>');

            $delalmacen_entidad = "DELETE from almacen_entidad WHERE Entidad='" . $email . " OR Entidad='" . $email . "Alumno''";
            $afectados = xSQL($delmatriculas, $vConex);
            W('response almacen_entidad> :' . $afectados . '<br/>');
            W('almacen_entidad> :' . $delalmacen_entidad . '<br/><br/>');

            datosAlternos("Matriculas");
        }

        if (get("metodo") == "programa") {

            $CodPrograma = $_POST['CodPrograma'];

            $delprograma = "DELETE from programas WHERE CodPrograma=" . $CodPrograma . " ";
            $afectados = xSQL($delprograma, $vConex);
            W('response programa> :' . $afectados . '<br/>');
            W('programa> :' . $delprograma . '<br/><br/>');



            $delprograma = "DELETE from log_programas WHERE Programa=" . $CodPrograma . " ";
            $afectados = xSQL($delprograma, $vConex);
            W('response log_programas> :' . $afectados . '<br/>');
            W('log_programas> :' . $delprograma . '<br/><br/>');

            $delprograma = "DELETE from lista_trabajo_det WHERE CodigoProducto=" . $CodPrograma . " ";
            $afectados = xSQL($delprograma, $vConex);
            W('response lista_trabajo_det> :' . $afectados . '<br/>');
            W('lista_trabajo_det> :' . $delprograma . '<br/><br/>');
//////////////////
            $delprograma = "DELETE from introduccion WHERE Producto=" . $CodPrograma . " ";
            $afectados = xSQL($delprograma, $vConex);
            W('response introduccion> :' . $afectados . '<br/>');
            W('introduccion> :' . $delprograma . '<br/><br/>');

            $delprograma = "DELETE from curricula WHERE ProgramaCod=" . $CodPrograma . " ";
            $afectados = xSQL($delprograma, $vConex);
            W('response curricula> :' . $afectados . '<br/>');
            W('curricula> :' . $delprograma . '<br/><br/>');

            datosAlternos("Programa");
        }

        if (get("metodo") == "curso") {

            $CodCursos = $_POST['CodCursos'];

            $delCurso = "DELETE from cursos where CodCursos=" . $CodCursos . " ";
            $afectados = xSQL($delCurso, $vConex);
            W('response curso> :' . $afectados . '<br/>');
            W('Cursos> :' . $delCurso . '<br/><br/>');

            $SqlTema = "SELECT CodTema FROM tema where Curso=" . $CodCursos . " ";
            $rg = fetch($SqlTema);
            $CodTema = ($rg["CodTema"] != "" ? $rg["CodTema"] : "0");

            $delCurso = "DELETE from subtema where Tema=" . $CodTema . " ";
            $afectados = xSQL($delCurso, $vConex);
            W('response curso> :' . $afectados . '<br/>');
            W('SubTema> :' . $delCurso . '<br/><br/>');

            $delCurso = "DELETE from tema where Curso=" . $CodCursos . " ";
            $afectados = xSQL($delCurso, $vConex);
            W('response curso> :' . $afectados . '<br/>');
            W('Tema> :' . $delCurso . '<br/><br/>');

            $delCurso = "DELETE from elconfiguracioncronograma where CodCurso=" . $CodCursos . " ";
            $afectados = xSQL($delCurso, $vConex);
            W('response curso> :' . $afectados . '<br/>');
            W('elconfiguracioncronograma> :' . $delCurso . '<br/><br/>');

            $SqlAlmacen = "SELECT AlmacenCod FROM almacen where Producto='CU-" . $CodCursos . "' ";
            $rg = fetch($SqlAlmacen);
            $AlmacenCod = ($rg["AlmacenCod"] != "" ? $rg["AlmacenCod"] : "0");

            $delCurso = "DELETE from curricula where ProductoCod=" . $AlmacenCod . " ";
            $afectados = xSQL($delCurso, $vConex);
            W('response curso> :' . $afectados . '<br/>');
            W('curricula> :' . $delCurso . '<br/><br/>');

            $delCurso = "DELETE from almacen where Producto='CU-" . $CodCursos . "'";
            $afectados = xSQL($delCurso, $vConex);
            W('response curso> :' . $afectados . '<br/>');
            W('almacen> :' . $delCurso . '<br/><br/>');

            $delCurso = "DELETE from articulos where ProductoFab=" . $CodCursos . " ";
            $afectados = xSQL($delCurso, $vConex);
            W('response curso> :' . $afectados . '<br/>');
            W('articulos> :' . $delCurso . '<br/><br/>');

            /*             * *
              select * from cursos where CodCursos=392;
              select * from tema where Curso=392;
              select * from subtema where Tema=574;
              select * from elconfiguracioncronograma where CodCurso=392;
              select * from almacen where Producto='CU-393';
              select * from curricula where ProductoCod=3704;
              select * from articulos where ProductoFab=393;

             * ** */
            datosAlternos("Curso");
        }


        if (get("metodo") == "sys_tipo_input") {
            DReg("sys_tipo_input", "Codigo", "'" . get("codigo") . "'", $vConex);
            datosAlternos("CreacionTipoDato");
        }
        if (get("metodo") == "sys_tipo_ouput1") {
            DReg("sys_tipo_ouput", "Codigo", "'" . get("codigo") . "'", $vConex);
            TipoCampoHtml("Lista");
        }
        if (get("metodo") == "pais_edit") {
            DReg("pais", "CodPais", "'" . get("Codigo") . "'", $vConex);
            datosAlternos("Paises");
        }
        if (get("metodo") == "categoria_edit") {
            DReg("categorias", "CategoriCod", "'" . get("Codigo") . "'", $vConex);
            datosAlternos("Categorias");
        }
        if (get("metodo") == "sectores_edit") {
            DReg("sectores", "IdSectores", "'" . get("Codigo") . "'", $vConex);
            datosAlternos("TipoSectores");
        }
        if (get("metodo") == "tipoevaluacion_edit") {
            DReg("elevaluaciontipo", "Codigo", "'" . get("Codigo") . "'", $vConex);
            datosAlternos("TipoEvaluacion");
        }

        if (get("metodo") == "vcmaestroservidor") {
            $codvcmaestroservidor = get("Codigo");
            $sql1 = "Select count(*) as Reg from vc_controlserver where codvcmaestroservidor=$codvcmaestroservidor";
            $rg = fetch($sql1);
            if ($rg['Reg'] == 0) {
                DReg("vc_maestroservidor", "Codigo", "'" . get("Codigo") . "'", $vConex);
            } else {
                W(MsgE("El servidor tiene registros."));
            }
            datosAlternos("ServerVC");
        }

        if (get("metodo") == "moneda_edit") {
            DReg("moneda", "Codigo", "'" . get("Codigo") . "'", $vConex);
            datosAlternos("Moneda");
        }
        if (get("metodo") == "ga_area") {
            DReg("ga_area", "Codigo", "'" . get("Codigo") . "'", $vConex);
            datosAlternos("Area");
        }
        if (get("metodo") == "reclamo_atencion_caso") {
            DReg("reclamo_atencion_caso", "Codigo", "'" . get("Codigo") . "'", $vConex);
            datosAlternos("Reclamo");
        }
        if (get("metodo") == "departamento_edit") {
            DReg("departamentos", "Codigo", "'" . get("Codigo") . "'", $vConex);
            datosAlternos("Departamentos");
        }
        /* if (get("metodo") == "empresa_edit") {
          DReg("empresas", "Codigo", "'" . get("Codigo") . "'", $vConex);
          datosAlternos("Empresas");
          } */
        if (get("metodo") == "sysTabletDet") {
            EliminaCampo();
        }
        if (get("metodo") == "menu_empresa") {
            DReg("menu_empresa", 'Codigo', "'" . get("codigo") . "'", $vConex);
            MenuPerfil("Listado");
        }
        if (get("metodo") == "menu_empresa_det") {
            DReg("menu_empresa_det", 'Codigo', "'" . get("codigo") . "'", $vConex);
            MenuPerfil("Detalle");
        }
        if (get("metodo") == "sys_base_datos") {
            DReg("sys_base_datos", 'Codigo', get("codigo"), $vConex);
            BDatos("Lista");
        }
        //bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb
        if (get("metodo") == "form_FTrabajo") {
            if (eliminar_ptrabajo(get('codigo'))) {
                DReg("FTrabajo", 'Codigo', get("codigo"), $vConex);
            }
        }
        FTrabajo("Lista");
    }
    exit();
}

$action = get('action');
if (!empty($action)) {
    switch ($action) {
        case 'seleccion-db':
            selectionDb();
            break;
        case 'listado-tablas':
            listadoTablas();
            break;
        case 'actualizar-tabla':
            actualizarTabla();
            break;
        case 'ordenar-datos':
            ordenarDatos();
            break;
        default:
            break;
    }
    exit;
}

function EliminarUsuario(){
global $vConex;

           $Alumno = $_POST['Usuario'];
			if( !empty($Alumno)){
			
                $deleltransrespuesta_cab="DELETE FROM eltransrespuesta_cab WHERE  Alumno='". $Alumno .'Alumno'."' OR  Alumno='".$Alumno.'Profesor'."'";
                $afectados=xSQL($deleltransrespuesta_cab, $vConex);
                W('response alumnos> :' . $afectados . '<br/>');
                W(' Respuesta Alumno> :' . $deleltransrespuesta_cab . '<br/><br/>');

                $deleltransrespuesta= "DELETE FROM eltransrespuesta WHERE  Usuario='".$Alumno.'Alumno'."' OR Usuario='".$Alumno.'Profesor'."'";
                $afectados=xSQL( $deleltransrespuesta, $vConex);
                W('response alumnos> :' . $afectados . '<br/>');
                W('Actividades Alumnos> :' .  $deleltransrespuesta . '<br/><br/>');


                $delevaluacionalumno="DELETE FROM elevaluacionalumno WHERE  Alumno='".$Alumno.'Alumno'."' OR  Alumno='".$Alumno.'Profesor'."'";
                $afectados=xSQL($delevaluacionalumno, $vConex);
                W('response alumnos> :' . $afectados. '<br/>');
                W('Evaluacion Alumno> :' .  $delevaluacionalumno . '<br/><br/>');

                $delusuarios = "DELETE from usuarios WHERE Usuario='".$Alumno."'";
                $afectados = xSQL($delusuarios, $vConex);
                W('response usuarios> :' . $afectados . '<br/>');
                W('usuarios> :' . $delusuarios . '<br/><br/>');

                $delusuario_det = "DELETE from usuario_entidad WHERE Usuario='".$Alumno."'";
                $afectados = xSQL($delusuario_det, $vConex);
                W('response usuario_entidad> :' . $afectados . '<br/>');
                W('usuario_entidad> :' . $delusuario_det . '<br/><br/>');

                $delmatricula = "DELETE from matriculas WHERE Cliente='".$Alumno.'Alumno'."' OR Cliente='".$Alumno.'Profesor'."'";
                $afectados = xSQL($delmatricula, $vConex);
                W('response matriculas> :' . $afectados . '<br/>');
                W('matriculas> :' . $delmatricula . '<br/><br/>');

                $delalmacen_entidad = "DELETE from almacen_entidad WHERE Entidad='".$Alumno.'Alumno'."' OR Entidad='".$Alumno.'Profesor'."'";
                $afectados = xSQL($delalmacen_entidad, $vConex);
                W('response almacen_entidad> :' . $afectados . '<br/>');
                W('almacen_entidad> :' . $delalmacen_entidad . '<br/><br/>');

                $delalumnos = "DELETE from alumnos WHERE Usuario='".$Alumno.'Alumno'."' OR Usuario='".$Alumno.'Profesor'."'";
                $afectados = xSQL($delalumnos, $vConex);
                W('response alumnos> :' . $afectados . '<br/>');
                W('alumnos> :' . $delalumnos . '<br/><br/>');

                $delprofesores = "DELETE from profesores WHERE Usuario='".$Alumno.'Alumno'."' OR Usuario='".$Alumno.'Profesor'."'";
                $afectados = xSQL($delprofesores, $vConex);
                W('response profesores> :' . $afectados . '<br/>');
                W('profesores> :' . $delprofesores . '<br/><br/>');

                $SqlAsis=" SELECT Codigo FROM asistencia WHERE Participante = '".$Alumno."' ";
                $Mx = fetchAll($SqlAsis, $vConex);
                foreach ($Mx as $Asis) {
					$Codigo = $Asis->Codigo;
					$Delete = "DELETE FROM interacciones WHERE Asistencia = ".$Codigo." ";
					xSQL($Delete, $vConex);
					W(MsgE('interacciones: '.$Delete).'<br>');
                }
				
                $Delete = "DELETE FROM asistencia WHERE Participante = '".$Alumno."' ";
                xSQL($Delete, $vConex);
                W(MsgE('asistencia: '.$Delete).'<br>');

                $SqlAsis_2=" SELECT Codigo FROM asistencia_alumno_control WHERE Profesor = '".$Alumno.'Profesor'."' ";
                $Mx = fetchAll($SqlAsis_2, $vConex);
                foreach ($Mx as $Asis) {
					$Codigo = $Asis->Codigo;
					$Delete = "DELETE FROM asistencia_alumno WHERE Control = ".$Codigo." ";
					xSQL($Delete, $vConex);
					W(MsgE('asistencia_alumno: '.$Delete).'<br>');
                }
                $Delete = "DELETE FROM asistencia_alumno_control WHERE Profesor = '".$Alumno.'Profesor'."' ";
                xSQL($Delete, $vConex);
                W(MsgE('asistencia_alumno_control: '.$Delete).'<br>');

                $Delete = "DELETE FROM programapresentaciones WHERE Alumno = '".$Alumno.'Alumno'."' ";
                xSQL($Delete, $vConex);
                W(MsgE('programapresentaciones: '.$Delete).'<br>');


            /** NOTIFICANDO A ECOMMERCE DE ELIMINACION DE UN USUARIO **/
            // fyupanquia 05:57 p.m. 20/01/2016
            // ALUMNO
                $email=$Alumno;
                $fields = array(
                    'action'        => urlencode("delUsuario"),
                    'email'         => urlencode($email),
                );

                $fields_string = '';
                foreach ($fields as $key => $value) {
                    $fields_string .= $key.'='.$value.'&';
                }
                rtrim($fields_string, '&');
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://owlecomerce.com/_vistas/Services/Usuarios.php");
                curl_setopt($ch, CURLOPT_POST, count($fields));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
                $result = curl_exec($ch);
                curl_close($ch);
            /****/
			
		//aquiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii
			
		 	$MSJ=" <DIV STYLE='WIDTH: 600PX; HEIGHT:50PX ; BACKGROUND-COLOR: GREEN; COLOR: WHITE; PADDING-TOP:20PX   '>  <CENTER><B>Se ha eliminado el usuario correctamente ...!!  </B> </CENTER></DIV>  "; 		
			W($MSJ);
			datosAlternos("Usuarios");
		}else{
			$MSJ=" <DIV  STYLE='WIDTH: 600PX; HEIGHT:50PX ; BACKGROUND-COLOR: RED; COLOR: WHITE; PADDING-TOP:20PX '> <CENTER><B>  No se ingreso ningun dato..!!! </B></CENTER></DIV>  "; 		
			W($MSJ);
		   datosAlternos("Usuarios");
		
		}

}

function ProcesosSistema($Arg) {
    global $vConex, $enlace;
    switch ($Arg) {

        case 'Menu':
            $pestanas = getPestanasHtml($enlace, 'ProcesoSistema');
            $tituloBtn = Titulo("<span>Procesos </span><p>DEL SISTEMA</p><div class='bicel'></div>", "", "200px", "TituloA");
            $menu = " Actuliza tipo program en la lista de trabajo]" . $enlace . "?ActualizaComponentes=ListasTrabajo]panelB-R}";
            $menu .= " Curso-por-curso]" . $enlace . "?ActualizaComponentes=ListasTrabajoCursos]panelB-R}";
            $menu .= " planeacion-por-Activo]" . $enlace . "?ActualizaComponentes=ListasTrabajoCursosCPlaneacion]panelB-R}";
            $menu .= " progespecial-por-progespecial]" . $enlace . "?ActualizaComponentes=progespecial-por-progespecial]panelB-R}";
            // $menu = "Actualiza Almacen Entidad]" . $enlace . "?MenuPerfil=Listado]panelB-R}";
            // $menu .= "Actualiza componentes]" . $enlace . "?ActualizaComponentes=Listado]panelB-R}";
            // $menu .= "Actualiza Cronograma]" . $enlace . "?ActualizaCronograma=Listado]panelB-R}";
            // $menu .= "Actualiza Cronograma]" . $enlace . "?ActualizaCronograma=Listado]panelB-R}";
            $mv = menuVertical($menu, 'menu4');
            $s = layoutLH($pestanas, $tituloBtn . $mv);
            WE($s);
            break;
    }
}

function Reportes($Arg) {
    global $vConex, $enlace;
    switch ($Arg) {
        case 'Menu':
            $pestanas = getPestanasHtml($enlace, 'Reportes');

            $tituloBtn = Titulo("<span>Gestión </span><p>REPORTES</p><div class='bicel'></div>", "", "200px", "TituloA");
            $menu = "Control Matriculados]" . $enlace . "?Reportes=MAAP]panelB-R}";
            $menu .= "Detalle Matriculados]" . $enlace . "?Reportes=DetMtrclds]panelB-R}";

            $menu .= "Detalle Concurrencias]" . $enlace . "?Reportes=DetConcurrentes]panelB-R}";


            $mv = menuVertical($menu, 'menu4');
            $s = layoutLH($pestanas, $tituloBtn . $mv);
            WE($s);
            break;
        case 'MAAP':
            $email = post('Usuario');

            $btn = "<div class='buscar'></div>]" . $enlace . "?Reportes=BusquedaListadoMAAP]panelB-R3}";
            $btn .= "Todos]" . $enlace . "?Reportes=MAAP]panelB-R}";
            $btn = Botones($btn, 'botones1');
            $subMenu = tituloBtnPn("Reporte <p>Matrícula-Almacen-Artículo-Programas</p>", $btn, "200px", "TituloA");

            $sql = ' 
						SELECT M.Producto, AR.Titulo, AL.TipoProducto,M.cliente , U.Nombres AS Empresa
						FROM matriculas AS M
						INNER JOIN almacen AS AL ON M.Producto = AL.AlmacenCod
						INNER JOIN articulos AS AR ON AR.Producto = AL.Producto
						INNER JOIN programas AS P ON P.CodPrograma = AR.ProductoFab
						INNER JOIN usuarios AS U ON U.Usuario = AL.Entidad
					 ';

            if (!empty($email)) {
                $sql .= ' WHERE  M.cliente LIKE "%' . $email . '%" ';
            }
            $sql .= ' AND AL.TipoProducto LIKE "programa%" LIMIT 50';
            $clase = 'reporteA';

            $panel = 'panelB-R2';
            $reporte = ListR2($titulo, $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_tipo_inputB', '');


            $pnl_busca_maap = "<div style='float:left;padding-bottom:10px' id='panelB-R3'></div>";
            $s = $divFloat . $subMenu . $pnl_busca_maap . $reporte;
            WE($s);
            break;

        case 'BusquedaListadoMAAP':
            $uRLForm = "Buscar]" . $enlace . "?Reportes=MAAP]panelB-R]F]}";
            $form = c_form("Buscar Usuario", $vConex, "busqueda_usurio_productos", "CuadroA", $path, $uRLForm, "", $tSelectD);
            $form = "<div style='width:400px;'>" . $form . "</div>";
            $s = PanelInferior($form, "panel_001", '800px');
            WE($s);
            break;
        case 'DetMtrclds':
            $AlmacenCod = post('AlmacenCod');
            // QUERY PARA BUSCAR EL COORDINADOR O EL SOPORTE TECNICO  --> A TRAVES DEL TIPO DE CODIGO ALMACEN

            $sql = 'SELECT 
						 AL.AlmacenCod as CodigoAjax ,AR.titulo 
						 ,U.Nombres AS Empresa 
						 ,CONCAT(_ue.Nombres ," ", _ue.Apellidos) AS Coordinador,
						 CONCAT(_uee.Nombres ," ", _uee.Apellidos) AS SoporteTecnico 
						FROM matriculas AS M 
						INNER JOIN almacen AS AL ON M.Producto = AL.AlmacenCod 
						INNER JOIN articulos AS AR ON AR.Producto = AL.Producto 
						INNER JOIN programas AS P ON P.CodPrograma = AR.ProductoFab 
						INNER JOIN usuarios AS U ON U.Usuario = AL.Entidad 
						LEFT JOIN usuario_entidad AS _ue ON _ue.Codigo = AL.Coordinador 
						LEFT JOIN usuario_entidad AS _uee ON _uee.Codigo = AL.SoporteTecnico 
						';
            if (!empty($AlmacenCod)) {
                $sql .= ' WHERE AL.AlmacenCod=' . $AlmacenCod . ' ';
            }
            $sql .= ' GROUP BY M.Producto  LIMIT 50 ';
            $clase = 'reporteA';
            $panel = 'panelB-R22';
            $url = $enlace . "?Reportes=DetalleMatricula";
            $enlaceCod = 'CodAlmacen';
            $reporte = ListR2($titulo, $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_tipo_inputB', '');
            $btn = "<div class='buscar'></div>]" . $enlace . "?Reportes=BusquedaLisResponsables]panelB-R3}";
            $btn = Botones($btn, 'botones1');

            $subMenu = tituloBtnPn("<span>RESPONSABLES DE </span> <p>Coordinación y Soporte</p>", $btn, "100px", "TituloA");

            $panelBusqueda = "<div style='float:left;padding-bottom:10px' id='panelB-R3'></div>";


            WE('<div width="100%" id="micontenedor">' . $subMenu . $panelBusqueda . $reporte . '</div>');

            break;
        case 'BusquedaLisResponsables':
            $uRLForm = "Buscar]" . $enlace . "?Reportes=DetMtrclds]panelB-R]F]}";
            $form = c_form("Buscar Responsable", $vConex, "BUSCA-PRODUCTO", "CuadroA", $path, $uRLForm, "", $tSelectD);
            $form = "<div style='width:400px;'>" . $form . "</div>";
            $s = PanelInferior($form, "panel_001", '800px');
            WE($s);
            break;

        case 'ConcuUser':
            $email = post('Usuario');

            $btn = "<div class='buscar'></div>]" . $enlace . "?Reportes=BuscarComcurrencias]panelB-R3}";
            $btn .= "Todos]" . $enlace . "?Reportes=ConcuUser]panelB-R}";
            $btn = Botones($btn, 'botones1');
            $subMenu = tituloBtnPn("Reporte <p>Listado-Concurrencias</p>", $btn, "200px", "TituloA");
            $sql = 'SELECT l.codigo, l.FechaEntrada,l.FechaSalida,l.Empresa,l.Usuario,l.Direccion					        
                    FROM log_concurrencia_curso l';

            if (!empty($email)) {
                $sql .= ' WHERE  l.Usuario LIKE "%' . $email . '%" ';
            }

            $clase = 'reporteA';

            $panel = 'panelB-R22';
            $reporte = ListR2($titulo, $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_tipo_inputB', '');
            $pnl_busca_maap = "<div style='float:left;padding-bottom:10px' id='panelB-R3'></div>";
            $s = $divFloat . $subMenu . $pnl_busca_maap . $reporte;
            WE($s);
            break;
        case 'DetConcurrentes':

            $btn = "<div class='buscar'></div>]" . $enlace . "?Reportes=BuscarComcurrencias]panelB-R3}";
            $btn .= "Todos]" . $enlace . "?Reportes=DetConcurrentes]panelB-R}";
            $btn = Botones($btn, 'botones1');
            $subMenu = tituloBtnPn("Reporte <p>Concurrencias de Usuarios</p>", $btn, "200px", "TituloA");

            $sql = "SELECT  *, COUNT(*) AS total 
    
                          FROM  (  SELECT   DATE_FORMAT(l.FechaEntrada, '%d %M %Y') AS fechaInicioFormat,
                                  l.FechaSalida, l.Usuario, l.Direccion                                  
                        FROM
                                log_concurrencia_curso l
						
                        INNER JOIN almacen ON l.CodAlmCurso = almacen.AlmacenCod
                        WHERE l.Usuario <>''
                        GROUP BY
                  Usuario, FechaEntrada 
                           ) AS reporte           
                     GROUP BY
                    reporte.fechaInicioFormat desc ";



            $clase = 'reporteA';

            $panel = 'panelB-R22';
            $reporte = ListR2($titulo, $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_tipo_inputB', '');


            $pnl_busca_maap = "<div style='float:left;padding-bottom:10px' id='panelB-R3'></div>";
            $s = $divFloat . $subMenu . $pnl_busca_maap . $reporte;
            WE($s);



            break;

        case 'BuscarComcurrencias':

            $uRLForm = "Buscar]" . $enlace . "?Reportes=ConcuUser]panelB-R]F]}";
            $form = c_form("Buscar Usuario", $vConex, "BUSCAR_USUARIO", "CuadroA", $path, $uRLForm, "", $tSelectD);
            $form = "<div style='width:400px;'>" . $form . "</div>";
            $s = PanelInferior($form, "panel_001", '800px');
            WE($s);

            break;


        case 'DetalleMatricula':

            $sql = 'Select _up.Descripcion,_ue.Usuario,_ue.Nombres,_ue.Apellidos  from almacen AS _aa
						INNER JOIN usuario_entidad as _ue 
						ON _ue.Codigo = _aa.Coordinador OR _ue.Codigo = _aa.SoporteTecnico
						INNER JOIN usuario_perfil AS _up 
						ON _up.Codigo = _ue.Perfil 
					 	WHERE _aa.AlmacenCod=' . get('CodAlmacen') . ' AND _aa.Coordinador!=0 OR _aa.SoporteTecnico!=0';



            $clase = 'reporteA';
            $panel = 'panelB-R22';
            $url = $enlace . "?Reportes=DetalleMatricula";
            $enlaceCod = 'codigoSys_tipo_input';

            $reporte = ListR2($titulo, $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_tipo_inputB', '');
            WE($reporte);
            break;
    }
}

function ActualizaComponentes($Arg) {

    global $vConex, $enlace;
    switch ($Arg) {
        case 'Listado':
            $TipoProducto = "VideoEmb";
            $Categoria = 6;
            $entidadCreadora = "fri.com.pe";

            // SELECT * FROM elconfiguracioncronograma 
            // WHERE Codigo = 562
            // 559
            // 562
            // 558			

            $Sql = "SELECT  ST.NombreArchivo, ST.TituloArticulo, ST.Componente, ST.TipoSubtema, ST.TipoTema , ST.ContenidoArticulo
			, AL.Origen , AL.Entidad , ST.SubTemaCod
			FROM elconfiguracioncronograma AS CN
			INNER JOIN tema AS T ON CN.CodUnidad = T.CodTema
			INNER JOIN subtema AS ST ON CN.CodTema = ST.SubTemaCod
			INNER JOIN almacen AS AL ON CN.CodAlmacen = AL.AlmacenCod
			WHERE  ST.TipoSubtema = 'video' AND T.Curso = 76 AND ST.Componente = 0
			";
            $consulta = Matris_Datos($Sql, $vConex);

            while ($reg = mysql_fetch_array($consulta)) {

                $sql = " INSERT INTO archivocontenido  
					(
					Nombre,TipoProducto,Categoria
					,Formato,Archivo,Embebido
					,Entidad, Usuario, UNegocio
					, Escuela, FechReg , Estado
					,TemaReferencia , SubTemaReferencia
					) VALUES (
					 '" . $reg["TituloArticulo"] . "','" . $TipoProducto . "', " . $Categoria . ",
					 '','', '" . $reg["ContenidoArticulo"] . "',
					 'fri.com.pe',133,0,
					 0,'" . $FechaHora . "', 'Revisado',
					 0,0
					)
					  ";
                W(xSQL($sql, $vConex) . "<BR>");
                $codigoProducto = mysql_insert_id($vConex);

                $sql = 'Select Nombres from usuarios where Usuario="' . $entidadCreadora . '"';
                $fabricante = fetch($sql);

                W("Paso 1ER Insert <br>");
                insert('articulos', array(
                    'Titulo' => $reg["TituloArticulo"],
                    'Descripcion' => $reg["TituloArticulo"],
                    'Fabricante' => $fabricante["Nombres"],
                    'Entidad' => $entidadCreadora,
                    'TipoProductpro_sysformo' => "VideoEmb",
                    'TipoOrigen' => 1,
                    'Producto' => "ARC-" . $codigoProducto,
                    'ProductoFab' => $codigoProducto,
                    'Categoria' => $Categoria
                ));
                W("Paso 2do Insert <br>");

                $CodigoAlmacen = insert('almacen', array(
                    'Origen' => $entidadCreadora,
                    'Entidad' => $entidadCreadora,
                    'producto' => "ARC-" . $codigoProducto,
                    'TipoProducto' => "VideoEmb",
                    'Estado' => "Abierto",
                    'Ingreso' => "1"
                ));

                W("Paso 3re Insert <br>");

                $sqlU = " UPDATE  subtema SET 
					   Componente = " . $CodigoAlmacen["lastInsertId"] . "
					   WHERE  SubTemaCod = " . $reg["SubTemaCod"] . "
					";
                W($sqlU);
                xSQL($sqlU, $vConex);
                W("Paso 4 update <br>");
            }
            $s = "hola";
            WE($s);
            break;
	    case 'ListasTrabajo':
		//Este proceso cambia los tipos de programa a un tipo mas detallado como programaD, programaC
            $Sql = "SELECT  TP.AliasCod, LTD.Codigo FROM lista_trabajo_det   AS LTD
			INNER JOIN programas AS PR ON LTD.CodigoProducto = PR.CodPrograma
			INNER JOIN tipoprograma AS TP ON PR.TipoPrograma = TP.IDTipoPrograma
			WHERE  TipoProducto = 'Programa' ";
            $consulta = Matris_Datos($Sql, $vConex);
            while ($reg = mysql_fetch_array($consulta)) {
			    
				$sqlU = " UPDATE  lista_trabajo_det SET 
				TipoProducto = '".$reg["AliasCod"]."'
				WHERE  Codigo = " . $reg["Codigo"] . "
				";
				W($sqlU."<br>");
				xSQL($sqlU, $vConex);
               
            }			
		break;
		
	    case 'ListasTrabajoCursos':
		    
			W("ListasTrabajoCursos <BR>");
            $Sql = "SELECT   LTD.Codigo FROM lista_trabajo_det   AS LTD
			WHERE  TipoProducto = 'Curso' ";
            $consulta = Matris_Datos($Sql, $vConex);
            while ($reg = mysql_fetch_array($consulta)) {
			    
				$sqlU = " UPDATE  lista_trabajo_det SET 
				TipoProducto = 'curso'
				WHERE  Codigo = " . $reg["Codigo"] . "
				";
				W($sqlU."<br>");
				xSQL($sqlU, $vConex);
               
            }			
		break;

	    case 'progespecial-por-progespecial':
		    
			W("ListasTrabajoCursos <BR>");
            $Sql = "SELECT   LTD.Codigo FROM lista_trabajo_det   AS LTD
			WHERE  TipoProducto = 'PEspecial' ";
            $consulta = Matris_Datos($Sql, $vConex);
            while ($reg = mysql_fetch_array($consulta)) {
			    
				$sqlU = " UPDATE  lista_trabajo_det SET 
				TipoProducto = 'progespecial'
				WHERE  Codigo = " . $reg["Codigo"] . "
				";
				W($sqlU."<br>");
				xSQL($sqlU, $vConex);
               
            }			
		break;

		
	    case 'ListasTrabajoCursosCPlaneacion':
		    
			W("Cambia el estado de planeacion <BR>");
            $Sql = "SELECT   LTD.Codigo FROM lista_trabajo_det   AS LTD
			WHERE  Estado = 'Planeacion' ";
            $consulta = Matris_Datos($Sql, $vConex);
            while ($reg = mysql_fetch_array($consulta)) {
			    
				$sqlU = " UPDATE  lista_trabajo_det SET 
				Estado = 'Activo'
				WHERE  Codigo = " . $reg["Codigo"] . "
				";
				W($sqlU."<br>");
				xSQL($sqlU, $vConex);
               
            }			
		break;		
		
    }
}

function ActualizaCronograma($Arg) {

    global $vConex, $enlace;
    switch ($Arg) {
        case 'Listado':
            $TipoProducto = "VideoEmb";
            $Categoria = 6;
            $entidadCreadora = "fri.com.pe";

            $Sql = "SELECT  ST.NombreArchivo, ST.TituloArticulo, ST.Componente, ST.TipoSubtema, ST.TipoTema , ST.ContenidoArticulo
			, AL.Origen , AL.Entidad , ST.SubTemaCod
			FROM elconfiguracioncronograma AS CN
			INNER JOIN tema AS T ON CN.CodUnidad = T.CodTema
			INNER JOIN subtema AS ST ON CN.CodTema = ST.SubTemaCod
			INNER JOIN almacen AS AL ON CN.CodAlmacen = AL.AlmacenCod
			WHERE  ST.TipoSubtema = 'video' AND T.Curso = 76 AND ST.Componente = 0
			";
            $consulta = Matris_Datos($Sql, $vConex);

            while ($reg = mysql_fetch_array($consulta)) {
                
            }
            $s = "hola";
            WE($s);
            break;
    }
}

function GeneraScript($form) {
    global $vConex;
    $resultado = "";
    //agregamos condiciones de busqueda donde cada es un elemnto del array condiciones
    $condiciones[0] = "codigo='$form'";
    //Genera script del formulario Cabecera
    $resultado.=GeneraScriptGen($vConex, "sys_form", $condiciones) . "<br/>";
    //Consulta para obtener todas los detalles de un determinado formulario cabecera en $codForm
    $sql = "SELECT * FROM sys_form_det WHERE Form='$form'";
    $rg = fetchMx($sql);
    $codForm = array();
    for ($i = 0; $i < count($rg); $i++) {
        $codForm[$i] = $rg[$i]['Codigo'];
    }
    //generar scripts de todos los detalles del formulario cabecera
    for ($i = 0; $i < count($codForm); $i++) {
        $condiciones[0] = "Codigo='$codForm[$i]'";
        $resultado.=GeneraScriptGen($vConex, "sys_form_det", $condiciones) . "<br/>";
    }

    WE($resultado);
}

function EliminaCampo() {
    global $vConex;
    $codigoSTD = get("cod");
    $tabla = get("codigoSysTabla");

    $sql = "SELECT  Descripcion FROM sys_tabla_det WHERE  Codigo =  " . $codigoSTD . " ";
    $rg = fetch($sql);
    $nombre_campT = $rg["Descripcion"];

    $query_columnas = mysql_query('SHOW COLUMNS FROM ' . $tabla . '');
    $num_cmp = mysql_num_rows($query_columnas);
    while ($row_columnas = mysql_fetch_assoc($query_columnas)) {
        $nombre_camp = $row_columnas['Field'];
        $type_camp = $row_columnas['Type'];
        if ($nombre_camp == $nombre_campT) {
            $cmp_valid = 1;
        }
    }

    if ($cmp_valid == 1) {
        $sql = " ALTER TABLE " . $tabla . " DROP " . $nombre_campT . "";
        W(xSQL($sql, $vConex));
    }
    DReg("sys_tabla_det", "Codigo", $codigoSTD, $vConex);
    vistaCT("FormDet");
}

function EliminaCampos() {
    global $vConex;
    $campos = post("ky");
    for ($j = 0; $j < count($campos); $j++) {
        DReg("sys_form_det", "Codigo", "'" . $campos[$j] . "'", $vConex);
    }
    detalleForm('detalle');
}

function actualizaCampo() {

    global $vConex;
    $sys_tabla = get("codigoSysTabla");

    $campoActual = post("Descripcion");
    $tipoCampo = post("TipoCampo");
    $size = post("Size");

    $sql = 'SELECT Descripcion FROM sys_tabla_det WHERE  Codigo = ' . get("cod") . '  ';
    $rg = fetch($sql);
    $campoAntiguo = $rg["Descripcion"];
    // W($campoAntiguo);

    $sql = " ALTER TABLE " . $sys_tabla . " ";
    $sql .= " CHANGE " . $campoAntiguo . "  " . $campoActual . " " . $tipoCampo . " ";
    if ($tipoCampo == "int" || $tipoCampo == "decimal") {
        if ($size > 0) {
            $sql .= " (" . $size . ")";
        } else {
            $sql .= "";
        }
    }

    if ($tipoCampo == "varchar") {
        $sql .= " (" . $size . ") CHARACTER SET utf8 ";
    }
    if ($tipoCampo == "char") {
        $sql .= " (" . $size . ") CHARACTER SET utf8 ";
    }
    if ($tipoCampo == "datetime" || $tipoCampo == "date") {
        
    }
    if ($tipoCampo == "text") {
        $sql .= " CHARACTER SET utf8 NOT NULL";
    }

    $sql .= " ; ";
    W(xSQL($sql, $vConex));

    p_gf("sysTabletDet", $vConex, get("cod"));
    vistaCT("FormDet");
}

function pro_sysTabletDet() {

    global $vConex;
    $tabla = get("codigoSysTabla");
    $descripcion = post("Descripcion");
    $tipoCampo = post("TipoCampo");
    $size = post("Size");

    if ($tipoCampo == "varchar" || $tipoCampo == "char") {
        $sql = "ALTER TABLE " . $tabla . " ADD " . $descripcion . " " . $tipoCampo . "(" . $size . ") CHARACTER SET utf8  NOT NULL";
        xSQL($sql, $vConex);
    }

    if ($tipoCampo == "int" || $tipoCampo == "decimal") {
        $sql = "ALTER TABLE " . $tabla . " ADD " . $descripcion . " " . $tipoCampo . "(" . $size . ") NOT NULL ";
        xSQL($sql, $vConex);
    }

    if ($tipoCampo == "text") {
        $sql = "ALTER TABLE " . $tabla . " ADD COLUMN  " . $descripcion . " " . $tipoCampo . "  CHARACTER SET utf8 NOT NULL";
        xSQL($sql, $vConex);
    }

    if ($tipoCampo == "datetime" || $tipoCampo == "date" || $tipoCampo == "time") {
        $sql = "ALTER TABLE " . $tabla . " ADD " . $descripcion . " " . $tipoCampo . " NOT NULL ";
        xSQL($sql, $vConex);
    }
    if ($tipoCampo == "double") {
        $sql = "ALTER TABLE " . $tabla . " ADD " . $descripcion . " " . $tipoCampo . " ";
        xSQL($sql, $vConex);
    }
    p_gf("sysTabletDet", $vConex, "");
    vistaCT("FormDet");
}

function pro_systabla() {
    global $vConex;

    $sql = 'SELECT Codigo,Descripcion FROM sys_tabla WHERE  Codigo = "' . post("Codigo") . '" ';
    $rg = fetch($sql);
    $codigo = $rg["Codigo"];
    if ($codigo != "") {

        W("La tabla ya existe");
        vistaCT("tablas");
    } else {

        p_gf("sys_tabla1", $vConex, "");
        crea_tabla(post("Codigo"), $vConex);

        vistaCT("tablas");
    }
}

function crea_tabla($tabla, $conexion) {
    $entero = post("Entero");
    $Log = post("Log");
    $size = post("Size");
    $sql = " CREATE TABLE " . $tabla . " (";
    if ($entero == "SI") {
        if ($size > 0) {
            $sql .= " Codigo INT(" . $size . ") NOT NULL, ";
        } else {
            $sql .= " Codigo INT NOT NULL, ";
        }
        $tipo = "INT";
    } else {
        if ($size > 0) {
            $sql .= " Codigo VARCHAR(" . $size . ") NOT NULL, ";
        } else {
            $sql .= " Codigo VARCHAR NOT NULL, ";
        }

        $tipo = "VARCHAR";
    }
    $sql .= " PRIMARY KEY (Codigo)";
    $sql .= " ); ";
    W(xSQL($sql, $conexion));

    $cod_sys_tabla_det = numerador("sys_tabla_det", 0, "");
    $sql = 'INSERT  INTO sys_tabla_det (Codigo,Descripcion,TipoCampo,sys_tabla) VALUES (' . $cod_sys_tabla_det . ',"Codigo","' . cmn($tipo) . '","' . $tabla . '")';
    xSQL($sql, $conexion);

    if ($Log != "") {
        $sql = " CREATE TABLE log_" . $tabla . " (";
        $sql .= " Codigo INT NOT NULL auto_increment, ";
        $sql .= " Usuario VARCHAR(100) NOT NULL ,";
        $sql .= " Empresa VARCHAR(100) NOT NULL ,";
        $sql .= " Operacion VARCHAR(50) NOT NULL ,";
        if ($entero == "SI") {
            $sql .= " " . $tabla . " INT(30) NOT NULL, ";
        } else {
            $sql .= " " . $tabla . " VARCHAR(20) NOT NULL ,";
        }
        $sql .= " Fecha_Hora DATETIME NOT NULL ,";
        $sql .= " PRIMARY KEY (Codigo)";
        $sql .= " ); ";
        W(xSQL($sql, $conexion));
    }
}

function pro_sysform() {
    global $vConex;

    $sql = 'SELECT Codigo,Descripcion FROM sys_tabla WHERE  Codigo = "' . post("Tabla") . '" ';
    $rg = fetch($sql);
    $codigo = $rg["Codigo"];

    if ($codigo != "") {

        // WE(post("Tabla"));
        $vSQL = 'SELECT Codigo,Descripcion,TipoCampo,sys_tabla  FROM  sys_tabla_det WHERE  sys_tabla = "' . post("Tabla") . '" ';
        $consulta = mysql_query($vSQL, $vConex);

        while ($r = mysql_fetch_array($consulta)) {

            $cod_sys_form_det = numerador("sys_form_det", 5, "");
            $sql = 'INSERT  INTO sys_form_det (Codigo,NombreCampo,Alias,TipoInput,TipoOuput,Form,Visible,TamanoCampo)
		VALUES ("' . $cod_sys_form_det . '","' . $r["Descripcion"] . '","' . $r["Descripcion"] . '","' . cmn($r["TipoCampo"]) . '","text","' . post("Codigo") . '","SI",130)';
            xSQL($sql, $vConex);

        }

        p_gf("SysFomr1", $vConex, "");
    } else {

        WE("La Tabla No existe" . post("Tabla"));
    }
}
function pro_sysform_file($Codigo) {
    global $vConex;

    $sql            = "SELECT Entidad,Archivo FROM reporte_notas_importar WHERE Codigo = {$Codigo};";
    $rg             = fetch($sql);
    $Path           = "../../from/{$rg["Archivo"]}";
    #$Path          = "../../from/{$rg["Archivo"]}";
    $Data           = LeerExcel3($Path);
    $Json           = json_decode($Data);

    foreach($JsonRE as $EvalRec){
        $Alias=$EvalRec->Alias;
        foreach($Json as $objeto){
            $Nota=($objeto->$Alias=="")?0:$objeto->$Alias;
            $SqlUpdate="UPDATE elevaluacionalumno SET Nota={$Nota} WHERE EvalDetCurso=".$EvalRec->RECURSO." AND EvalConfigCurso=".$EvalRec->CONCEPTO."  AND Alumno='".$objeto->Usuario."Alumno';";
            #W($SqlUpdate."<BR>");
            xSQL2($SqlUpdate,$vConex);
        }
    }

    $sql = 'SELECT Codigo,Descripcion FROM sys_tabla WHERE  Codigo = "' . post("Tabla") . '" ';
    $rg = fetch($sql);
    $codigo = $rg["Codigo"];


    if ($codigo != "") {

        // WE(post("Tabla"));
        $vSQL = 'SELECT Codigo,Descripcion,TipoCampo,sys_tabla  FROM  sys_tabla_det WHERE  sys_tabla = "' . post("Tabla") . '" ';
        $consulta = mysql_query($vSQL, $vConex);

        while ($r = mysql_fetch_array($consulta)) {

            $cod_sys_form_det = numerador("sys_form_det", 5, "");
            $sql = 'INSERT  INTO sys_form_det (Codigo,NombreCampo,Alias,TipoInput,TipoOuput,Form,Visible,TamanoCampo)
		VALUES ("' . $cod_sys_form_det . '","' . $r["Descripcion"] . '","' . $r["Descripcion"] . '","' . cmn($r["TipoCampo"]) . '","text","' . post("Codigo") . '","SI",130)';
            xSQL($sql, $vConex);

        }



    } else {

        WE("La Tabla No existe" . post("Tabla"));
    }
}

function actualizaTabla($parm) {
    global $vConex, $enlace;

    mysql_select_db("owlgroup_owl") or die("Imposible seleccionar base de datos");
    $result = mysql_list_tables("owlgroup_owl");
    If (!$result) {

        echo "DB Error, No se pueden listar las tablas";
        echo 'MySQL Error: ' . mysql_error();
    }

    $tablasImport = array('meta', 'meta_detalle', 'meta_transaccion', 'plan_educativo');

    While ($row = mysql_fetch_row($result)) {

//	 if($row[0] =="meta"){
        if (in_array($row[0], $tablasImport)) {
            $conta = 0;
            $sql = 'SELECT Codigo,Descripcion FROM sys_tabla WHERE  Codigo = "' . $row[0] . '" ';
            $rg = fetch($sql);
            $codigo = $rg["Codigo"];

            $_sql = 'SELECT * FROM ' . $row[0];
            $consulta = mysql_query($_sql, $vConex);
            $resultado = $consulta or die(mysql_error());

            $datos = array();
            for ($i = 0; $i < mysql_num_fields($consulta); ++$i) {
                $campo = mysql_field_name($consulta, $i);
                $type = mysql_field_type($consulta, $i);
                $size = mysql_field_len($consulta, $i);
                if ($type == 'string') {
                    $type = 'varchar';
                }
                $datos[$i] = array('Campo' => $campo, 'Tipo' => $type, 'Tamano' => $size);
                $conta++;
            }

            if ($codigo == "") {
                $sql = 'INSERT  INTO sys_tabla(Codigo,Descripcion,Estado) VALUES ("' . $row[0] . '","' . $row[0] . '","Activo")';
                W(xSQL($sql, $vConex) . "<br>");

                for ($j = 0; $j < $conta; ++$j) {
                    $cod_sys_tabla_det = numerador("sys_tabla_det", 1, "");
                    $_sql2 = 'INSERT  INTO sys_tabla_det (Codigo,Descripcion,TipoCampo,sys_tabla,Size) VALUES (' . $cod_sys_tabla_det . ',"' . $datos[$j]['Campo'] . '","' . $datos[$j]['Tipo'] . '","' . $row[0] . '","' . $datos[$j]['Tamano'] . '")';
                    xSQL($_sql2, $vConex);
                }
            } else {
                W($codigo . "<br>");
            }
        }
    }

    mysql_free_result($result);
    vistaCT("tablas");
}

function datosAlternos($parm) {
    global $vConex, $enlace;
    if ($parm == "DAlternos") {

        $pestanas = getPestanasHtml($enlace, 'datosalternos');

        $tituloBtn = Titulo("<span>Configuraci&oacute;n</span><p>DEL SISTEMA</p><div class='bicel'></div>", "", "200px", "TituloA");
        // $tituloBtn = Titulo("<span>Administrador </span><p>DE GESTIÃ“N</p><div class='bicel'></div>","","200px","TituloA");

        $menu = "<i class='icon-edit'></i>Menu Empresa]" . $enlace . "?MenuPerfil=Listado]panelB-R}";
        $menu .= "<i class='icon-edit'></i>Crear Empresa]./_vistas/gad_empresas.php?CrearEmpresa=ListarEmpresa]panelB-R}";
        $menu .= "<i class='icon-edit'></i>Master]" . $enlace . "?MenuPerfil=updtaePerflMaster]panelB-R}";
        $menu .= "<i class='icon-edit'></i>Perfiles Sys]" . $enlace . "?MenuPerfil=PerfilSysView]panelB-R}";
        $menu .= "<i class='icon-edit'></i>Perfiles x Empresa]" . $enlace . "?MenuPerfil=PerfilSys]panelB-R}";
        $menu .= "<i class='icon-edit'></i>Eliminar Menus Fallidos]" . $enlace . "?MenuPerfil=PerfilDelete]panelB-R}";
        $menu .= "<i class='icon-edit'></i>Videoconferencia]./_vistas/gad_admin_videoconferencia.php?Main=Listado]panelB-R}";
        $menu .= "<i class='icon-cog'></i>Tipo de Datos]" . $enlace . "?accionDA=CreacionTipoDato]panelB-R}";
        $menu .= "<i class='icon-cog'></i>Tipo Campo Html]" . $enlace . "?TipoCampoHtml=Lista]panelB-R}";
        $menu .= "<i class='icon-cog'></i>Base de Datos]" . $enlace . "?BDatos=Lista]panelB-R}";
        // $menu .= "<i class='icon-cog'></i>Archivo de Trabajo]" . $enlace . "?FTrabajo=Lista]panelB-R}";
        // $menu .= "<i class='icon-cog'></i> Anuncios]./_vistas/procesos_temporales.php?ActualizaAnuncio=Listado]panelB-R}";
        // $menu .= "<i class='icon-cog'></i> Sectores]" . $enlace . "?accionDA=Sectores]panelB-R}";
        $menu .= "<i class='icon-cog'></i> Eliminar Usuario]" . $enlace . "?accionDA=Usuarios]panelB-R}";
     
        $menu .= "<i class='icon-cog'></i> Cambiar Usuario]" . $enlace . "?accionDA=CambiarUsuarios]panelB-R}";
        $menu .= "<i class='icon-cog'></i> Eliminar Matricula]" . $enlace . "?accionDA=Matriculas]panelB-R}";
        $menu .= "<i class='icon-cog'></i> Eliminar Programa E- Almacen]./_vistas/gad_Admin_productos.php?PanelAdmin=EliminaPrograma]panelB-R}";
        // $menu .= "<i class='icon-cog'></i> Eliminar Programa Curso - Almacen]./_vistas/gad_Admin_productos.php?PanelAdmin=EliminaPrograma]panelB-R}";
        // $menu .= "<i class='icon-cog'></i> Eliminar Matricula]" . $enlace . "?accionDA=Matriculas]panelB-R}";
        $menu .= "<i class='icon-cog'></i> Eliminar Programa]" . $enlace . "?accionDA=Programa]panelB-R}";
        $menu .= "<i class='icon-cog'></i> Eliminar Curso]" . $enlace . "?accionDA=Curso]panelB-R}";
		
        $menu .= "<i class='icon-cog'></i> Crear Tema (color) ]./_vistas/gad_empresas.php?CrearEmpresa=CrearTemaColor]panelB-R}";
        $menu .= "<i class='icon-cog'></i> Eliminar UsuarioEbook]" . $enlace . "?accionDA=EliminarEbook]panelB-R}";
        $menu .= "<i class='icon-cog'></i> Reporte Usuarios Matriculados]" . $enlace . "?accionDA=ReporteMatriculas]panelB-R}";

        $mv = menuVertical($menu, 'menu4', '');
        $s = layoutLH($pestanas, $tituloBtn . $mv, '');
    }

    if ($parm == "DMaestros") {

        $pestanas = getPestanasHtml($enlace, 'datosmaestros');
        $tituloBtn = Titulo("<span>Configuraci&oacute;n</span><p>DATOS MAESTROS</p><div class='bicel'></div>", "", "200px", "TituloA");
        $menu .= "<i class='icon-cog'></i> Paises]" . $enlace . "?accionDA=Paises]panelB-R}";
        $menu .= "<i class='icon-cog'></i> Departamentos]" . $enlace . "?accionDA=Departamentos]panelB-R}";
        $menu .= "<i class='icon-cog'></i> Empresas]" . $enlace . "?accionDA=Empresas]panelB-R}";
        $menu .= "<i class='icon-cog'></i> Categorias]" . $enlace . "?accionDA=Categorias]panelB-R}";
        $menu .= "<i class='icon-cog'></i> Sectores]" . $enlace . "?accionDA=TipoSectores]panelB-R}";
        $menu .= "<i class='icon-cog'></i> Tipo Evaluación]" . $enlace . "?accionDA=TipoEvaluacion]panelB-R}";
        $menu .= "<i class='icon-cog'></i> Moneda]" . $enlace . "?accionDA=Moneda]panelB-R}";
        $menu .= "<i class='icon-cog'></i> Tipo Producto]" . $enlace . "?accionDA=TipoProducto]panelB-R}";
        $menu .= "<i class='icon-cog'></i> Tipo Pregunta]" . $enlace . "?accionDA=TipoPregunta]panelB-R}";
        $menu .= "<i class='icon-cog'></i> Servidores Video Conferencia]" . $enlace . "?accionDA=ServerVC]panelB-R}";
        $menu .= "<i class='icon-cog'></i> Areas]" . $enlace . "?accionDA=Area]panelB-R}";
        $menu .= "<i class='icon-cog'></i> Reclamo Atencion]" . $enlace . "?accionDA=Reclamo]panelB-R}";
        $menu .= "<i class='icon-cog'></i> Tutoriales]{$enlace}?Tutorial=view]panelB-R}";

		
        $menu .= "<i class='icon-cog'></i> Soporte Master]{$enlace}?Soporte=view]panelB-R}";
		////////////////////////////////-----------------------
        $menu .= "<i class='icon-cog'></i> Tipos de Proyectos]{$enlace}?TipoProyectos=Tipos]panelB-R}";

        $mv = menuVertical($menu, 'menu4');
        $s = layoutLH($pestanas, $tituloBtn . $mv);
    }
    if($parm == "ReporteMatriculas"){

        $sql = "select TipoAccesoMatricula AS 'Tipo de Acceso Matricula',count(*) AS 'Usuarios',TipoAccesoMatricula as CodigoAjax from matriculas
        where TipoAccesoMatricula IN ('Programa','Ebook')
        group by  TipoAccesoMatricula";

        $suma = "select(select count(*) AS 'Usuarios' from matriculas
        where TipoAccesoMatricula IN ('Programa','Ebook')
        )as Total  ";
        $ftchO=fetchOne($suma);
        $Total=$ftchO->Total;

        $ftch=fetchAll($sql);
        $ftchC=count($ftch);

            $Url_Raiz_Link = "{$enlace}?accionDA=ReporteMatriculasDetalle&CantUsuarios=$CantUsuarios";
            $Url_Add_Link = array("CodigoAjax" => "TipoAccesoMatricula","Usuarios" => "Usuarios");
            $Panel_Destino = 'panelB-R';
            $reporte = ListR("", $sql, $vConex, 'reporteA', 'd,d', $Url_Raiz_Link, $Url_Add_Link, $Panel_Destino);
            $subMenu = tituloBtnPn("<label style='color:#9E9E9E;font-weight: bold;'>$Total</label><p>Usuarios Matriculados</p><div class='bicel'></div>", null, "auto", "TituloA");
            $s = PanelUnico($subMenu, $reporte, 'panelB-R2', '500px');



    }
    if($parm == "ReporteMatriculasDetalle"){

        $TipoAccesoMatricula=get('TipoAccesoMatricula');
        $usuarios=get('Usuarios');
		
        $sql = "select Cliente,Producto,Entidad,FechaInscripcion from matriculas where TipoAccesoMatricula='$TipoAccesoMatricula'
        order by Entidad desc";

        $btn = "Retornar]$enlace?accionDA=ReporteMatriculas]panelB-R}";
        $btn = Botones($btn, 'botones1');

        $subMenu = tituloBtnPn("<label style='color:#9E9E9E;font-weight: bold;'>$usuarios</label><br></label></label><span>Usuarios por </span><p>$TipoAccesoMatricula</p><div class='bicel'></div>", $btn, "auto", "TituloA");

        $Url_Raiz_Link = "{$enlace}?accionDA=ReporteMatriculasDetalle&CantUsuarios=$CantUsuarios";
        $Url_Add_Link = array("CodigoAjax" => "TipoAccesoMatricula","Usuarios" => "Usuarios");
        $Panel_Destino = 'panelB-R';
        $reporte = ListR("", $sql, $vConex, 'reporteA', 'd,d,d,d', $Url_Raiz_Link, $Url_Add_Link, $Panel_Destino);

        $cab = PanelUnico($subMenu, $reporte, 'panelB-R2', '500px');
        $s= layoutV($cab,'panelB-R2');


    }
    if ($parm == "CreacionTipoDato") {

        $btn = "Crea Tipo]Abrir]panel-FloatB}";
        $btn = Botones($btn, 'botones1');
        $subMenu = tituloBtnPn("Listado <p>TIPOS DE CAMPOS</p>", $btn, "100px", "TituloA");

        $sql = 'SELECT Codigo, Descripcion, Codigo AS CodigoAjax FROM sys_tipo_input ';
        $clase = 'reporteA';
        $enlaceCod = 'codigoSys_tipo_input';
        $url = $enlace . "?accionDA=editaReg";
        $panel = 'panelB-R2';
        $reporte = ListR2($titulo, $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_tipo_inputB', '');

        // $path = array('Imagen' => '../_files/','ImagenMarca' => '../_files/');
        $uRLForm = "Guardar]" . $enlace . "?metodo=sys_tipo_input&transaccion=INSERT]panelB-R]F]panel-FloatB}";
        $titulo = "Crear tipo de campo";
        $form = c_form($titulo, $vConex, "sys_tipo_input", "CuadroA", $path, $uRLForm, "", $tSelectD);
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $style = "left:170px;top:0px;";
        $divFloat = panelFloat($form, "panel-FloatB", $style);
        $s = layoutLSB($divFloat . $subMenu, $reporte, "panelB-R2");
    }

    if ($parm == "editaReg") {
        $codigo = get("codigoSys_tipo_input");
        // $btn = "Crea Tipo]Abrir]panel-FloatB}";
        // $btn = Botones($btn,'botones1');
        $subMenu = tituloBtnPn("Editar Registro", $btn, "100px", "TituloA");

        $uRLForm = "Actualizar]" . $enlace . "?metodo=sys_tipo_input&transaccion=UPDATE&codigo=" . $codigo . "]panelB-R]F]}";
        $uRLForm .="Eliminar]" . $enlace . "?metodo=sys_tipo_input&transaccion=DELETE&codigo=" . $codigo . "]panelB-R]]}";
        $form = c_form($titulo, $vConex, "sys_tipo_input", "CuadroA", $path, $uRLForm, "'" . $codigo . "'", $tSelectD);
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $s = layoutV($subMenu, $form);
    }

    #########
    if ($parm == "Usuarios") {

        $subMenu = tituloBtnPn("Eliminar Usuario", $btn, "100px", "TituloA");
        $uRLForm = "Eliminar]" . $enlace . "?metodo=usuarios&transaccion=DELETE]panelB-R]F]}";
        $form = c_form_adp($titulo . $btn, $vConex, "del_usuarios", "CuadroA", $path, $uRLForm, $Codigo, $tSelectD, 'CodPrograma');
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $s = layoutV($subMenu, $form);
    }

	 if ($parm == "EliminarEbook") {

        $subMenu = tituloBtnPn("Eliminar Usuario - Ebook", $btn, "100px", "TituloA");
        $uRLForm = "Eliminar]" . $enlace . "?metodo=EliminarEbook&transaccion=DELETE]panelB-R]F]}";
        $form = c_form_adp($titulo . $btn, $vConex, "del_EbookEiminar", "CuadroA", $path, $uRLForm, $Codigo, $tSelectD, 'CodPrograma');
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $s = layoutV($subMenu, $form);
    }
	
	
	
    #########
    if ($parm == "CambiarUsuarios") {

        $subMenu = tituloBtnPn("Modificar Usuario", $btn, "100px", "TituloA");
        $uRLForm = "Modificar]" . $enlace . "?metodo=Cambiarusuarios&transaccion=UPDATE]panelB-R]F]}";
        $form = c_form_adp($titulo . $btn, $vConex, "FUpdateUsuarios", "CuadroA", $path, $uRLForm, $Codigo, $tSelectD, 'CodPrograma');
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $s = layoutV($subMenu, $form);
    }
    #########
    if ($parm == "Matriculas") {

        $subMenu = tituloBtnPn("Eliminar Matriculas", $btn, "100px", "TituloA");
        $uRLForm = "Eliminar]" . $enlace . "?metodo=matriculas&transaccion=DELETE]panelB-R]F]}";
        $form = c_form_adp($titulo . $btn, $vConex, "del_matriculas", "CuadroA", $path, $uRLForm, $Codigo, $tSelectD, 'IdMatricula');
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $s = layoutV($subMenu, $form);
    }
    ########
    ########
    if ($parm == "Programa") {

        $subMenu = tituloBtnPn("Eliminar Programa", $btn, "100px", "TituloA");
        $uRLForm = "Eliminar]" . $enlace . "?metodo=programa&transaccion=DELETE]panelB-R]F]}";
        $form = c_form_adp($titulo . $btn, $vConex, "FElimprograma", "CuadroA", $path, $uRLForm, $Codigo, $tSelectD, 'CodPrograma');
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $s = layoutV($subMenu, $form);
    }
    ########
    if ($parm == "Curso") {

        $subMenu = tituloBtnPn("Eliminar Curso", $btn, "100px", "TituloA");
        $uRLForm = "Eliminar]" . $enlace . "?metodo=curso&transaccion=DELETE]panelB-R]F]}";
        $form = c_form_adp($titulo . $btn, $vConex, "FElimcurso", "CuadroA", $path, $uRLForm, $Codigo, $tSelectD, 'CodCursos');
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $s = layoutV($subMenu, $form);
    }
    #########

    if ($parm == "TipoSectores") {
        $subMenu = tituloBtnPn("Listado <p>DE PROGRAMAS</p>", $btn, "100px", "TituloA");

        $sql = 'SELECT IdSectores AS Codigo,Descripcion AS Nombre, TipoInterface,  IdSectores AS CodigoAjax FROM sectores 
        ORDER BY Codigo';

        /*
          $sql = '  SELECT AR.Titulo AS Programa,CT.Descripcion AS Categoria,TP.Descripcion AS "Tipo de Programa",SC.Descripcion AS Sector ,PR.CodPrograma AS CodigoAjax FROM almacen AS AL';
          $sql .= ' INNER JOIN articulos AS AR  ON AR.Producto = AL.Producto ';
          $sql .= ' INNER JOIN categorias AS CT  ON CT.CategoriCod = AR.Categoria ';
          $sql .= ' INNER JOIN programas AS PR ON PR.CodPrograma = AR.Productofab  ';
          $sql .= ' INNER JOIN tipoprograma AS TP ON TP.IDTipoPrograma = PR.TipoPrograma  ';
          $sql .= ' LEFT JOIN sectores AS SC ON SC.IdSectores = PR.Sector  ';
          $sql .= ' WHERE AL.TipoProducto LIKE "programa%" ';
          $sql .= ' ORDER BY Programa';
         */
        $clase = 'reporteA';
        $enlaceCod = 'IdSectores';
        $url = $enlace . "?accionDA=DetalleSector";
        $panel = 'panelB-R';
        $reporte = ListR2($titulo, $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_tipo_inputB', '');
        $s = layoutLSB($divFloat . $subMenu, $reporte, "panelB-R");
    }

    if ($parm == "DetalleSector") {
        $Codigo = get("codigoSys_tipo_input");

        $subMenu = tituloBtnPn("Editar Sector", $btn, "100px", "TituloA");
        $uRLForm = "Actualizar]" . $enlace . "?metodo=sector_edit&transaccion=UPDATE&Codigo=" . $Codigo . "]VpanelB-R2]F]}";
        $tSelectD = array('Sector' => 'SELECT IdSectores,Descripcion FROM sectores ORDER BY Descripcion');
        $form = c_form_adp($titulo . $btn, $vConex, "sector_edit", "CuadroA", $path, $uRLForm, $Codigo, $tSelectD, 'CodPrograma');
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $s = layoutV($subMenu, $form);
    }

    if ($parm == "Paises") {

        $btn = "Crear Pais]Abrir]panel-FloatB}";
        $btn = Botones($btn, 'botones1');
        $subMenu = tituloBtnPn("Listado <p>DE PAISES</p>", $btn, "100px", "TituloA");

        $sql = '  SELECT CodPais AS Codigo,Nombre,CodPostal AS "Codigo Postal",CodPais AS CodigoAjax FROM pais';
        $sql .= ' ORDER BY Codigo';
        $clase = 'reporteA';
        $enlaceCod = 'codigoSys_tipo_input';
        $url = $enlace . "?accionDA=DetallePaises";
        $panel = 'panelB-R2';
        $reporte = ListR2($titulo, $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_tipo_inputB', '');

        $uRLForm = "Guardar]" . $enlace . "?metodo=pais_edit&transaccion=INSERT]panelB-R]F]panel-FloatB}";
        $titulo = "Crear País";
        $form = c_form($titulo, $vConex, "pais_edit", "CuadroA", $path, $uRLForm, "", $tSelectD);
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $style = "left:170px;top:0px;";
        $divFloat = panelFloat($form, "panel-FloatB", $style);
        $s = layoutLSB($divFloat . $subMenu, $reporte, "panelB-R2");
    }

    if ($parm == "DetallePaises") {

        $Codigo = get("codigoSys_tipo_input");

        $subMenu = tituloBtnPn("Editar País", $btn, "100px", "TituloA");
        $uRLForm = "Actualizar]" . $enlace . "?metodo=pais_edit&transaccion=UPDATE&Codigo=" . $Codigo . "]panelB-R]F]}";
        $uRLForm .="Eliminar]" . $enlace . "?metodo=pais_edit&transaccion=DELETE&Codigo=" . $Codigo . "]panelB-R]]}";

        $form = c_form_adp($titulo . $btn, $vConex, "pais_edit", "CuadroA", $path, $uRLForm, $Codigo, $tSelectD, 'CodPAis');
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $s = layoutV($subMenu, $form);
    }

    if ($parm == "Departamentos") {

        $btn = "Crear Depa.]Abrir]panel-FloatB}";
        $btn = Botones($btn, 'botones1');
        $subMenu = tituloBtnPn("Listado <p>DE DEPARTAMENTOS</p>", $btn, "100px", "TituloA");

        $sql = '  SELECT DP.Codigo,DP.Descripcion AS Departamento,PA.Nombre AS Pais ,DP.Codigo AS CodigoAjax FROM departamentos AS DP';
        $sql .= ' INNER JOIN pais AS PA ON PA.CodPais = DP.Pais';
        $sql .= ' ORDER BY Codigo';
        $clase = 'reporteA';
        $enlaceCod = 'codigoSys_tipo_input';
        $url = $enlace . "?accionDA=DetalleDepartamentos";
        $panel = 'panelB-R2';
        $reporte = ListR2($titulo, $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_tipo_inputB', '');

        $uRLForm = "Guardar]" . $enlace . "?metodo=departamento_edit&transaccion=INSERT]panelB-R]F]panel-FloatB}";
        $titulo = "Crear Departamento";
        $tSelectD = array('Pais' => 'SELECT CodPais AS Codigo,Nombre FROM pais ORDER BY Codigo');
        $form = c_form($titulo, $vConex, "departamento_edit", "CuadroA", $path, $uRLForm, "", $tSelectD);
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $style = "left:170px;top:0px;";
        $divFloat = panelFloat($form, "panel-FloatB", $style);
        $s = layoutLSB($divFloat . $subMenu, $reporte, "panelB-R2");
    }

    if ($parm == "DetalleDepartamentos") {

        $Codigo = get("codigoSys_tipo_input");

        $subMenu = tituloBtnPn("Editar Departamento", $btn, "100px", "TituloA");
        $uRLForm = "Actualizar]" . $enlace . "?metodo=departamento_edit&transaccion=UPDATE&Codigo=" . $Codigo . "]panelB-R]F]}";
        $uRLForm .="Eliminar]" . $enlace . "?metodo=departamento_edit&transaccion=DELETE&Codigo=" . $Codigo . "]panelB-R]]}";
        $tSelectD = array('Pais' => 'SELECT CodPais AS Codigo,Nombre FROM pais ORDER BY Codigo');
        $form = c_form_adp($titulo . $btn, $vConex, "departamento_edit", "CuadroA", $path, $uRLForm, $Codigo, $tSelectD, 'Codigo');
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $s = layoutV($subMenu, $form);
    }

    if ($parm == "Empresas") {

        $subMenu = tituloBtnPn("Listado <p>DE EMPRESAS</p>", $btn, "100px", "TituloA");

        $sql = '  SELECT CodEmpresa AS Codigo,NombreEmpresa AS Empresa,PA.Nombre AS Pais,DP.Descripcion AS Departamento ,CodEmpresa AS CodigoAjax FROM empresa AS EM';
        $sql .= ' LEFT JOIN pais AS PA ON PA.CodPais = EM.Pais';
        $sql .= ' LEFT JOIN departamentos AS DP ON DP.Codigo = EM.Departamento';
        $sql .= ' ORDER BY CodEmpresa';
        $clase = 'reporteA';
        $enlaceCod = 'codigoSys_tipo_input';
        $url = $enlace . "?accionDA=DetalleEmpresas";
        $panel = 'panelB-R2';
        $reporte = ListR2($titulo, $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_tipo_inputB', '');
        $s = layoutLSB($divFloat . $subMenu, $reporte, "panelB-R2");
    }

    if ($parm == "DetalleEmpresas") {

        $Codigo = get("codigoSys_tipo_input");

        $subMenu = tituloBtnPn("Editar País y Departamento", $btn, "100px", "TituloA");
        $uRLForm = "Actualizar]" . $enlace . "?metodo=empresa_edit&transaccion=UPDATE&Codigo=" . $Codigo . "]panelB-R]F]}";
        $tSelectD = array(
            'Pais' => 'SELECT CodPais AS Codigo,Nombre FROM pais ORDER BY Codigo',
            'Departamento' => 'SELECT Codigo,Descripcion FROM departamentos ORDER BY Codigo',
            'CategoriaEmpresa' => 'SELECT IdSectores AS Codigo,Descripcion FROM sectores ORDER BY Codigo',
            'TipoEmpresa' => 'SELECT IDTipoEmpresa AS Codigo,TipEmpDescripcion FROM tipoempresa ORDER BY Codigo',
            'Categoria' => 'SELECT CategoriCod AS Codigo,Descripcion FROM categorias ORDER BY Codigo'
        );
        $form = c_form_adp($titulo . $btn, $vConex, "empresa_edit", "CuadroA", $path, $uRLForm, $Codigo, $tSelectD, 'CodEmpresa');
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $s = layoutV($subMenu, $form);
    }

    if ($parm == "Categorias") {

        $btn = "Crea_Categoria]Abrir]panel-FloatB}";
        $btn = Botones($btn, 'botones1');
        $subMenu = tituloBtnPn("Listado <p>DE CATEGORIAS</p>", $btn, "100px", "TituloA");

        $sql = '  SELECT CategoriCod AS Codigo,Descripcion AS Nombre, TipoInterface , Orden,CategoriCod AS CodigoAjax FROM categorias';
        $sql .= ' ORDER BY Codigo';
        $clase = 'reporteA';
        $enlaceCod = 'codigoSys_tipo_input';
        $url = $enlace . "?accionDA=DetalleCategorias";
        $panel = 'panelB-R2';
        $reporte = ListR2($titulo, $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_tipo_inputB', '');

        $uRLForm = "Guardar]" . $enlace . "?metodo=categoria_edit&transaccion=INSERT]panelB-R]F]panel-FloatB}";
        $titulo = "Crear Categoria";
        $form = c_form($titulo, $vConex, "categoria_edit", "CuadroA", $path, $uRLForm, "", $tSelectD);
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $style = "left:170px;top:0px;";
        $divFloat = panelFloat($form, "panel-FloatB", $style);
        $s = layoutLSB($divFloat . $subMenu, $reporte, "panelB-R2");
    }

    if ($parm == "DetalleCategorias") {

        $Codigo = get("codigoSys_tipo_input");

        $subMenu = tituloBtnPn("Editar Categoria", $btn, "100px", "TituloA");
        $uRLForm = "Actualizar]" . $enlace . "?metodo=categoria_edit&transaccion=UPDATE&Codigo=" . $Codigo . "]panelB-R]F]}";
        $uRLForm .="Eliminar]" . $enlace . "?metodo=categoria_edit&transaccion=DELETE&Codigo=" . $Codigo . "]panelB-R]]}";

        $form = c_form_adp($titulo . $btn, $vConex, "categoria_edit", "CuadroA", $path, $uRLForm, $Codigo, $tSelectD, 'CategoriCod');
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $s = layoutV($subMenu, $form);
    }

    if ($parm == "TipoSectores") {

        $btn = "Crear_Sector]Abrir]panel-FloatB}";
        $btn = Botones($btn, 'botones1');
        $subMenu = tituloBtnPn("Listado <p>DE SECTORES</p>", $btn, "100px", "TituloA");

        $sql2 = '  SELECT IdSectores AS Codigo,Descripcion AS Nombre, TipoInterface, Orden,IdSectores AS CodigoAjax FROM sectores';
        $sql2 .= ' ORDER BY Codigo';

        $clase = 'reporteA';
        $enlaceCod = 'codigoSys_tipo_input';
        $url = $enlace . "?accionDA=DetalleTipoSectores";
        $panel = 'panelB-R';

        $reporte = ListR2($titulo, $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_tipo_inputB', '');

        $uRLForm = "Guardar]" . $enlace . "?metodo=sectores_edit&transaccion=INSERT]panelB-R]F]panel-FloatB}";
        $titulo = "Crear Sector";
        $form = c_form($titulo, $vConex, "sectores_edit", "CuadroA", $path, $uRLForm, "", $tSelectD);
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $style = "left:170px;top:0px;";
        $divFloat = panelFloat($form, "panel-FloatB", $style);
        $s = layoutLSB($divFloat . $subMenu, $reporte, "panelB-R");
    }

    if ($parm == "DetalleTipoSectores") {

        $Codigo = get("codigoSys_tipo_input");
        $subMenu = tituloBtnPn("Editar Sector", $btn, "100px", "TituloA");
        $uRLForm = "Actualizar]" . $enlace . "?metodo=sectores_edit&transaccion=UPDATE&Codigo=" . $Codigo . "]panelB-R]F]}";
        $uRLForm .="Eliminar]" . $enlace . "?metodo=sectores_edit&transaccion=DELETE&Codigo=" . $Codigo . "]panelB-R]]}";

        $form = c_form_adp($titulo . $btn, $vConex, "sectores_edit", "CuadroA", $path, $uRLForm, $Codigo, $tSelectD, 'IdSectores');
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $s = layoutV($subMenu, $form);
    }


    if ($parm == "TipoProducto") {

        $btn = "Crear Tipo]Abrir]panel-FloatB}";
        $btn = Botones($btn, 'botones1');
        $subMenu = tituloBtnPn("Tipo <p>DE PRODUCTOS</p>", $btn, "100px", "TituloA");

        $sql = '  SELECT TipoProductoId AS Codigo,Descripcion AS Nombre, TipoInterface, Orden,TipoProductoId AS CodigoAjax FROM tipoproducto';
        $sql .= ' ORDER BY Codigo';
        $clase = 'reporteA';
        $enlaceCod = 'codigo_tipoproducto';
        $url = $enlace . "?accionDA=TipoProducto_edit";
        $panel = 'panelB-R2';
        $reporte = ListR2($titulo, $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_tipo_inputB', '');
        //W($);   
        $uRLForm = "Guardar]" . $enlace . "?metodo=tipoproducto_edit&transaccion=INSERT]panelB-R]F]panel-FloatB}";
        $titulo = "Crear Tipo de Producto";
        $form = c_form_adp($titulo, $vConex, "tipoproducto_edit", "CuadroA", $path, $uRLForm, "", $tSelectD, 'TipoProductoId');
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $style = "left:170px;top:0px;";
        $divFloat = panelFloat($form, "panel-FloatB", $style);


        $s = layoutLSB($divFloat . $subMenu, $reporte, "panelB-R2");
    }

    if ($parm == "TipoProducto_edit") {

        $Codigo = get("codigo_tipoproducto");
        //WE( $Codigo);
        $subMenu = tituloBtnPn("Editar Sector", $btn, "100px", "TituloA");
        $uRLForm = "Actualizar]" . $enlace . "?metodo=tipoproducto_edit&transaccion=UPDATE&Codigo=" . $Codigo . "]panelB-R]F]}";
        $uRLForm .="Eliminar]" . $enlace . "?metodo=tipoproducto_edit&transaccion=DELETE&Codigo=" . $Codigo . "]panelB-R]]}";

        $form = c_form_adp($titulo . $btn, $vConex, "tipoproducto_edit", "CuadroA", $path, $uRLForm, "'" . $Codigo . "'", $tSelectD, 'TipoProductoId');
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $s = layoutV($subMenu, $form);
    }

    if ($parm == "TipoPregunta") {

        $btn = "Crear Tipo]Abrir]panel-FloatB}";
        $btn = Botones($btn, 'botones1');
        $subMenu = tituloBtnPn("Tipo <p>DE PREGUNTAS</p>", $btn, "100px", "TituloA");

        $sql = '  SELECT Codigo, Descripcion FROM tipo_pregunta';
        $sql .= ' ORDER BY Codigo';
        $clase = 'reporteA';
        $enlaceCod = 'codigo_tipo_pregunta_edit';
        $url = $enlace . "?accionDA=TipoPregunta_edit";
        $panel = 'panelB-R2';
        $reporte = ListR2($titulo, $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_tipo_inputB', '');

        $uRLForm = "Guardar]" . $enlace . "?metodo=tipo_pregunta_edit&transaccion=INSERT]panelB-R]F]panel-FloatB}";
        $titulo = "Crear Tipo de Producto";
        $form = c_form_adp($titulo, $vConex, "tipo_pregunta_edit", "CuadroA", $path, $uRLForm, "", $tSelectD, 'Codigo');
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $style = "left:170px;top:0px;";
        $divFloat = panelFloat($form, "panel-FloatB", $style);
        $s = layoutLSB($divFloat . $subMenu, $reporte, "panelB-R2");
    }

    if ($parm == "TipoPregunta_edit") {

        $Codigo = get("codigo_tipo_pregunta_edit");

        $subMenu = tituloBtnPn("Editar Sector", $btn, "100px", "TituloA");
        $uRLForm = "Actualizar]" . $enlace . "?metodo=tipo_pregunta_edit&transaccion=UPDATE&Codigo=" . $Codigo . "]panelB-R]F]}";
        $uRLForm .="Eliminar]" . $enlace . "?metodo=tipo_pregunta_edit&transaccion=DELETE&Codigo=" . $Codigo . "]panelB-R]]}";

        $form = c_form_adp($titulo . $btn, $vConex, "tipo_pregunta_edit", "CuadroA", $path, $uRLForm, "'" . $Codigo . "'", $tSelectD, 'Codigo');
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $s = layoutV($subMenu, $form);
    }

    if ($parm == "ServerVC") {

        $btn = "Agregar]Abrir]panel-FloatB}";
        $btn = Botones($btn, 'botones1');
        $subMenu = tituloBtnPn("Maestro de Servidores <p>Video Conferencia</p>", $btn, "100px", "TituloA");

        $sql = "SELECT Codigo as CodigoAjax,Descripcion,CantSalas 'Cant. Sala',CantUsuarioSala 'Usuarios por Sala',IpServidor,Dominio
                FROM vc_maestroservidor
                ORDER BY Codigo";
        $clase = 'reporteA';
        $enlaceCod = 'codvcmaestroservidor';
        $url = "$enlace?accionDA=ServerVC_edit";
        $panel = 'panelB-R2';
        $reporte = ListR2($titulo, $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'Codigo', '', '');

        $uRLForm = "Guardar]$enlace?metodo=vcmaestroservidor&transaccion=INSERT]panelB-R]F]panel-FloatB}";
        $titulo = "Agregar Servidor de Video Conferencia";
        $form = c_form_adp($titulo, $vConex, "vc_maestroservidor", "CuadroA", $path, $uRLForm, "", $tSelectD, 'Codigo');
        $form = "<div style='width:400px;'>" . $form . "</div>";
        $style = "left:170px;top:0px;";
        $divFloat = panelFloat($form, "panel-FloatB", $style);
        $s = layoutLSB($divFloat . $subMenu, $reporte, "panelB-R2");
    }
    if ($parm == "ServerVC_edit") {
        $Codigo = get("codvcmaestroservidor");
        $subMenu = tituloBtnPn("Editar Servidor", $btn, "100px", "TituloA");
        $uRLForm = "Actualizar]$enlace?metodo=vcmaestroservidor&transaccion=UPDATE&Codigo=$Codigo]panelB-R]F]}";
        $uRLForm .="Eliminar]$enlace?metodo=vcmaestroservidor&transaccion=DELETE&Codigo=$Codigo]panelB-R]]}";
        $form = c_form_adp($titulo . $btn, $vConex, "vc_maestroservidor", "CuadroA", $path, $uRLForm, "'" . $Codigo . "'", $tSelectD, 'Codigo');
        $form = "<div style='width:500px;'>$form</div>";
        $s = layoutV($subMenu, $form);
    }

    if ($parm == "TipoEvaluacion") {

        $btn = "Crear Tipo Evaluación]Abrir]panel-FloatB}";
        $btn = Botones($btn, 'botones1');
        $subMenu = tituloBtnPn("Listado <p>DE TIPO EVALUACIÓN</p>", $btn, "100px", "TituloA");

        $sql = '  SELECT Codigo,Tipo,Concepto,Codigo AS CodigoAjax FROM elevaluaciontipo';
        $sql .= ' ORDER BY Codigo';
        $clase = 'reporteA';
        $enlaceCod = 'codigoSys_tipo_input';
        $url = $enlace . "?accionDA=DetalleTipoEvaluacion";
        $panel = 'panelB-R2';
        $reporte = ListR2($titulo, $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_tipo_inputB', '');

        $uRLForm = "Guardar]" . $enlace . "?metodo=tipoevaluacion_edit&transaccion=INSERT]panelB-R]F]panel-FloatB}";
        $titulo = "Crear Tipo Evaluación";
        $form = c_form($titulo, $vConex, "tipoevaluacion_edit", "CuadroA", $path, $uRLForm, "", $tSelectD);
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $style = "left:170px;top:0px;";
        $divFloat = panelFloat($form, "panel-FloatB", $style);
        $s = layoutLSB($divFloat . $subMenu, $reporte, "panelB-R2");
    }

    if ($parm == "DetalleTipoEvaluacion") {

        $Codigo = get("codigoSys_tipo_input");

        $subMenu = tituloBtnPn("Editar Tipo Evaluación", $btn, "100px", "TituloA");
        $uRLForm = "Actualizar]" . $enlace . "?metodo=tipoevaluacion_edit&transaccion=UPDATE&Codigo=" . $Codigo . "]panelB-R]F]}";
        $uRLForm .="Eliminar]" . $enlace . "?metodo=tipoevaluacion_edit&transaccion=DELETE&Codigo=" . $Codigo . "]panelB-R]]}";

        $form = c_form_adp($titulo . $btn, $vConex, "tipoevaluacion_edit", "CuadroA", $path, $uRLForm, $Codigo, $tSelectD, 'Codigo');
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $s = layoutV($subMenu, $form);
    }
    if ($parm == "Moneda") {

        $btn = "Crear Tipo Moneda]Abrir]panel-FloatB}";
        $btn = Botones($btn, 'botones1');
        $subMenu = tituloBtnPn("Listado <p>DE TIPO DE MONEDAS</p>", $btn, "100px", "TituloA");

        $sql = '  SELECT Codigo,Descripcion,Codigo AS CodigoAjax FROM moneda';
        $sql .= ' ORDER BY Codigo';
        $clase = 'reporteA';
        $enlaceCod = 'codigoSys_tipo_input';
        $url = $enlace . "?accionDA=DetalleMoneda";
        $panel = 'panelB-R2';
        $reporte = ListR2($titulo, $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_tipo_inputB', '');

        $uRLForm = "Guardar]" . $enlace . "?metodo=moneda_edit&transaccion=INSERT]panelB-R]F]panel-FloatB}";
        $titulo = "Crear Tipo Moneda";
        $form = c_form($titulo, $vConex, "moneda_edit", "CuadroA", $path, $uRLForm, "", $tSelectD);
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $style = "left:170px;top:0px;";
        $divFloat = panelFloat($form, "panel-FloatB", $style);
        $s = layoutLSB($divFloat . $subMenu, $reporte, "panelB-R2");
    }

    if ($parm == "DetalleMoneda") {

        $Codigo = get('codigoSys_tipo_input');
        $CodigoA = $Codigo;
        $Codigo = "'$Codigo'";

        $subMenu = tituloBtnPn("Editar Tipo Moneda", $btn, "100px", "TituloA");
        $uRLForm = 'Actualizar]' . $enlace . '?metodo=moneda_edit&transaccion=UPDATE&Codigo=' . $CodigoA . ']panelB-R]F]}';
        $uRLForm .='Eliminar]' . $enlace . '?metodo=moneda_edit&transaccion=DELETE&Codigo=' . $CodigoA . ']panelB-R]]}';

        $form = c_form_adp($titulo . $btn, $vConex, "moneda_edit", "CuadroA", $path, $uRLForm, $Codigo, $tSelectD, 'Codigo');
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $s = layoutV($subMenu, $form);
    }

    if ($parm == "Area") {

        $btn = "Crear Area]Abrir]panel-FloatB}";
        $btn = Botones($btn, 'botones1');
        $subMenu = tituloBtnPn("Listado <p>DE AREAS</p>", $btn, "120px", "TituloA");

        $sql = '  SELECT Descripcion,InterfazUso,Codigo AS CodigoAjax FROM ga_area WHERE EntidadCreadora = "Sys" ';
        $sql .= ' ORDER BY Codigo';
        $clase = 'reporteA';
        $enlaceCod = 'codigoSys_tipo_input';
        $url = $enlace . "?accionDA=DetalleArea";
        $panel = 'panelB-R2';
        $reporte = ListR2($titulo, $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_tipo_inputB', '');

        $uRLForm = "Guardar]" . $enlace . "?metodo=ga_area&transaccion=INSERT]panelB-R]F]panel-FloatB}";
        $titulo = "Crear Area";
        $form = c_form($titulo, $vConex, "ga_area", "CuadroA", $path, $uRLForm, "", $tSelectD);
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $style = "left:170px;top:0px;";
        $divFloat = panelFloat($form, "panel-FloatB", $style);
        $s = layoutLSB($divFloat . $subMenu, $reporte, "panelB-R2");
    }

    if ($parm == "DetalleArea") {

        $Codigo = get('codigoSys_tipo_input');
        $CodigoA = $Codigo;
        $Codigo = "'$Codigo'";

        $subMenu = tituloBtnPn("Editar Tipo Area", $btn, "120px", "TituloA");
        $uRLForm = 'Actualizar]' . $enlace . '?metodo=ga_area&transaccion=UPDATE&Codigo=' . $CodigoA . ']panelB-R]F]}';
        $uRLForm .='Eliminar]' . $enlace . '?metodo=ga_area&transaccion=DELETE&Codigo=' . $CodigoA . ']panelB-R]]}';

        $form = c_form_adp($titulo . $btn, $vConex, "ga_area", "CuadroA", $path, $uRLForm, $Codigo, $tSelectD, 'Codigo');
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $s = layoutV($subMenu, $form);
    }

    if ($parm == "Reclamo") {

        $btn = "Crear Reclamo Caso]Abrir]panel-FloatB}";
        $btn = Botones($btn, 'botones1');
        $subMenu = tituloBtnPn("Listado <p>DE RECLAMOS CASOS</p>", $btn, "160px", "TituloA");

        $sql = '  SELECT RR.Nombre,AA.Descripcion as Area ,RR.Descripcion,RR.Codigo AS CodigoAjax FROM reclamo_atencion_caso as RR LEFT JOIN ga_area AS AA ON AA.Codigo = RR.Area ';
        $sql .= ' ORDER BY RR.Codigo';
        $clase = 'reporteA';
        $enlaceCod = 'codigoSys_tipo_input';
        $url = $enlace . "?accionDA=DetalleReclamo";
        $panel = 'panelB-R2';
        $reporte = ListR2($titulo, $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_tipo_inputB', '');

        $tSelectD = array('Area' => 'SELECT Codigo,Descripcion FROM ga_area WHERE EntidadCreadora = "Sys" AND InterfazUso = "Reclamo" ORDER BY Codigo');

        $uRLForm = "Guardar]" . $enlace . "?metodo=reclamo_atencion_caso&transaccion=INSERT]panelB-R]F]panel-FloatB}";
        $titulo = "Crear Reclamo Caso";
        $form = c_form($titulo, $vConex, "reclamo_atencion_caso", "CuadroA", $path, $uRLForm, "", $tSelectD);
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $style = "left:170px;top:0px;";
        $divFloat = panelFloat($form, "panel-FloatB", $style);
        $s = layoutLSB($divFloat . $subMenu, $reporte, "panelB-R2");
    }

    if ($parm == "DetalleReclamo") {

        $Codigo = get('codigoSys_tipo_input');
        $CodigoA = $Codigo;
        $Codigo = "'$Codigo'";

        $subMenu = tituloBtnPn("Editar Reclamo Caso", $btn, "120px", "TituloA");
        $uRLForm = 'Actualizar]' . $enlace . '?metodo=reclamo_atencion_caso&transaccion=UPDATE&Codigo=' . $CodigoA . ']panelB-R]F]}';
        $uRLForm .='Eliminar]' . $enlace . '?metodo=reclamo_atencion_caso&transaccion=DELETE&Codigo=' . $CodigoA . ']panelB-R]]}';

        $tSelectD = array('Area' => 'SELECT Codigo,Descripcion FROM ga_area WHERE EntidadCreadora = "Sys" AND InterfazUso = "Reclamo" ORDER BY Codigo');

        $form = c_form_adp($titulo . $btn, $vConex, "reclamo_atencion_caso", "CuadroA", $path, $uRLForm, $Codigo, $tSelectD, 'Codigo');
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $s = layoutV($subMenu, $form);
    }
    WE($s);
}

function TipoCampoHtml($parm) {
    global $vConex, $enlace;

    if ($parm == "Lista") {

        $btn = "Crea Tipo]Abrir]panel-FloatB}";
        $btn = Botones($btn, 'botones1');
        $subMenu = tituloBtnPn("Listado <p>CAMPOS HTML</p>", $btn, "100px", "TituloA");

        $sql = 'SELECT Codigo, Descripcion, Codigo AS CodigoAjax FROM sys_tipo_ouput ';
        $clase = 'reporteA';
        $enlaceCod = 'codigoSys_tipo_ouput';
        $url = $enlace . "?TipoCampoHtml=editaReg";
        $panel = 'panelB-R2';
        $reporte = ListR2($titulo, $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_tipo_ouput', '');
        // TipoCampoHtml=Lista
        // $path = array('Imagen' => '../_files/','ImagenMarca' => '../_files/');
        $uRLForm = "Guardar]" . $enlace . "?metodo=sys_tipo_ouput1&transaccion=INSERT]panelB-R]F]panel-FloatB}";
        $titulo = "Crear campo Html";
        $form = c_form($titulo, $vConex, "sys_tipo_ouput1", "CuadroA", $path, $uRLForm, "", $tSelectD);
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $style = "left:170px;top:0px;";
        $divFloat = panelFloat($form, "panel-FloatB", $style);
        $s = layoutLSB($divFloat . $subMenu, $reporte, "panelB-R2");
    }

    if ($parm == "editaReg") {

        $codigo = get("codigoSys_tipo_ouput");
        // $btn = "Crea Tipo]Abrir]panel-FloatB}";
        // $btn = Botones($btn,'botones1');
        $subMenu = tituloBtnPn("Editar Registro", $btn, "100px", "TituloA");

        $uRLForm = "Actualizar]" . $enlace . "?metodo=sys_tipo_ouput1&transaccion=UPDATE&codigo=" . $codigo . "]panelB-R]F]}";
        $uRLForm .="Eliminar]" . $enlace . "?metodo=sys_tipo_ouput1&transaccion=DELETE&codigo=" . $codigo . "]panelB-R]]}";
        $form = c_form($titulo, $vConex, "sys_tipo_ouput1", "CuadroA", $path, $uRLForm, "'" . $codigo . "'", $tSelectD);
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $s = layoutV($subMenu, $form);
    }
    WE($s);
}

function BDatos($parm) {
    global $vConex, $enlace;

    if ($parm == "Lista") {

        $btn = "<div class='botIconS'><i class='icon-edit'></i></div>]Abrir]panel-FloatB}";
        $btn = Botones($btn, 'botones1');
        $subMenu = tituloBtnPn("Listado <p>BASE DE DATOS</p>", $btn, "100px", "TituloA");

        $sql = 'SELECT Codigo, Nombre, Estado , Codigo AS CodigoAjax FROM sys_base_datos ';
        $clase = 'reporteA';
        $enlaceCod = 'Codigo';
        $url = $enlace . "?BDatos=editaReg";
        $panel = 'panelB-R2';
        $reporte = ListR2($titulo, $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_base_datos', '');
        // TipoCampoHtml=Lista
        // $path = array('Imagen' => '../_files/','ImagenMarca' => '../_files/');

        $uRLForm = "Guardar]" . $enlace . "?metodo=sys_base_datos&transaccion=INSERT]panelB-R]F]panel-FloatB}";

        $form = c_form('Crear Base de Datos', $vConex, "sys_base_datos", "CuadroA", $path, $uRLForm, "", $tSelectD);
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $style = "left:170px;top:0px;";
        $divFloat = panelFloat($form, "panel-FloatB", $style);

        $s = layoutLSB($divFloat . $subMenu, $reporte, "panelB-R2");
    }

    if ($parm == "editaReg") {

        $codigo = get("Codigo");

        $subMenu = tituloBtnPn("Editar Registro", $btn, "100px", "TituloA");
        $uRLForm = "Actualizar]" . $enlace . "?metodo=sys_base_datos&transaccion=UPDATE&codigo=" . $codigo . "]panelB-R]F]}";
        $uRLForm .="Eliminar]" . $enlace . "?metodo=sys_base_datos&transaccion=DELETE&codigo=" . $codigo . "]panelB-R]]}";
        $form = c_form($titulo, $vConex, "sys_base_datos", "CuadroA", $path, $uRLForm, $codigo, $tSelectD);
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $s = layoutV($subMenu, $form);
    }


    WE($s);
}

function FTrabajo($parm) {
    global $vConex, $enlace;
    if ($parm == "Lista") {
        $btn = "<div class='botIconS'><i class='icon-edit'></i></div>]Abrir]panel-FloatB}";
        $btn = Botones($btn, 'botones1');
        $subMenu = tituloBtnPn("Listado <p>ARCHIVOS DE TRABAJO</p>", $btn, "100px", "TituloA");

        $sql = 'SELECT Codigo, Nombre, Fecha, Codigo AS CodigoAjax FROM FTrabajo';
        $clase = 'reporteA';
        $enlaceCod = 'Codigo';
        $url = $enlace . "?FTrabajo=editaReg";
        $panel = 'panelB-R2';
        $reporte = ListR2($titulo, $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, '', '');
        // TipoCampoHtml=Lista
        // $path = array('Imagen' => '../_files/','ImagenMarca' => '../_files/');
        $uRLForm = "Guardar]" . $enlace . "?metodo=form_FTrabajo&transaccion=INSERT]panelB-R]F]panel-FloatB}";

        $form = c_form('Crear Archivo de Trabajo', $vConex, "form_FTrabajo", "CuadroA", $path, $uRLForm, "", $tSelectD);
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $style = "left:170px;top:0px;";
        $divFloat = panelFloat($form, "panel-FloatB", $style);

        $s = layoutLSB($divFloat . $subMenu, $reporte, "panelB-R2");
    }
    if ($parm == "editaReg") {
        $codigo = get("Codigo");

        $subMenu = tituloBtnPn("Editar Registro", $btn, "100px", "TituloA");
        //$uRLForm ="Actualizar]".$enlace."?metodo=form_FTrabajo&transaccion=UPDATE&codigo=".$codigo."]panelB-R]F]}";
        $uRLForm .="Eliminar]" . $enlace . "?metodo=form_FTrabajo&transaccion=DELETE&codigo=" . $codigo . "]panelB-R]]}";
        $form = c_form($titulo, $vConex, "form_FTrabajo", "CuadroA", $path, $uRLForm, $codigo, $tSelectD);
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $s = layoutV($subMenu, $form);
    }
    WE($s);
}

function crear_ptrabajo($nom_FTrabajo) {
    if ($nom_FTrabajo != "") {
        $Origen = BASE_PATH . DS . "owlgroup" . DS . "_vistas" . DS . "ModeloPHP.php";
        $Destino = BASE_PATH . DS . "owlgroup" . DS . "_vistas" . DS . $nom_FTrabajo . ".php";
        if (CopiaArchivos($Origen, $Destino)) {
            W("Se creo el archivo en la ruta: " . $Destino . "<br>");
            return true;
        } else {
            W("ERROR! El archivo ya existe: " . $Destino . "<br>");
            return false;
        }
    } else {
        W('A ocurrido un problema al intentar crear el Archivo de Trabajo<br>');
        return false;
    }
}

function eliminar_ptrabajo($cod_FTrabajo) {
    global $vConex;
    $Query = "SELECT Nombre FROM FTrabajo WHERE Codigo=" . $cod_FTrabajo;
    $rg = fetch($Query);
    $nom_FTrabajo = $rg['Nombre'];
    if ($nom_FTrabajo != "") {
        $Origen = BASE_PATH . DS . "owlgroup" . DS . "_vistas" . DS . $nom_FTrabajo . ".php";
        if (Elimina_Archivo($Origen)) {
            W("Se elimino el siguiente archivo en la ruta: " . $Origen . "<br>");
        } else {
            W("El archivo no existe: " . $Origen . "<br>");
        }
        return true;
    } else {
        W('A ocurrido un problema al intentar eliminar el Archivo de Trabajo');
        return false;
    }
}

function MenuPerfil($parm) {
    global $vConex, $enlace, $cnOwl;

    switch ($parm) {


        case "Listado":
            $btn = "Crear ]" . $enlace . "?MenuPerfil=Form]panelB-R2}";
            $btn .= "<div class='actualizar'></div>]" . $enlace . "?MenuPerfil=Listado]panelB-R}";
            $btn = Botones($btn, 'botones1');
            $subMenu = tituloBtnPn("Listado <p>MENÚ EMPRESA</p>", $btn, "150px", "TituloA");

            $sql = 'SELECT Codigo, Nombre, Url,Estado, Codigo AS CodigoAjax FROM menu_empresa ORDER BY Codigo ASC ';
            $clase = 'reporteA';
            $enlaceCod = 'codigo_menu_empresa';
            $url = $enlace . "?MenuPerfil=menuDetalle";

            $panel = 'panelB-R2';
            $reporte = ListR2($titulo, $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_tipo_ouput', '');

            $divFloat = panelFloat($form, "panel-FloatB", $style);
            $s = layoutLSB($divFloat . $subMenu, $reporte, "panelB-R2");
            break;
        case "Form":
            $uRLForm = "Crear]" . $enlace . "?metodo=menu_empresa&transaccion=INSERT]panelB-R]F]}";
            $titulo = "Crear MenÃº";
            $form = c_form($titulo, $vConex, "menu_empresa", "CuadroA", $path, $uRLForm, "", $tSelectD);
            $s = PanelInferior($FBusqueda . $form, "panel_edit_menu", '370px');
            WE($s);
            break;
        case "editaReg":
            $codigo = get("codigo_menu_empresa");
            $titulo = "Editar MenÃº";
            $uRLForm = "Actualizar]" . $enlace . "?metodo=menu_empresa&transaccion=UPDATE&codigo=" . $codigo . "]panelB-R]F]}";
            $uRLForm .="Eliminar]" . $enlace . "?metodo=menu_empresa&transaccion=DELETE&codigo=" . $codigo . "]panelB-R]]}";
            $form = c_form($titulo, $vConex, "menu_empresa", "CuadroA", $path, $uRLForm, $codigo, $tSelectD);
            $form = "<div style='width:400px;'>" . $form . "</div>";
            $s = PanelInferior($form, "panel_edit_menu", '320px');
            break;
        case "Detalle":
            $btn .= "Atrás]" . $enlace . "?MenuPerfil=Listado]panelB-R}";
            $btn = Botones($btn, 'botones1');
            $subMenu = tituloBtnPn("Listado <p>MENÃš EMPRESA</p>", $btn, "100px", "TituloA");

            $sql = 'SELECT Codigo, Nombre, Url,Estado, Codigo AS CodigoAjax FROM menu_empresa ORDER BY Codigo ASC ';

            $clase = 'reporteA';
            $enlaceCod = 'codigo_menu_empresa';
            $url = $enlace . "?MenuPerfil=menuDetalle";
            $panel = 'panelB-R2';
            $reporte = ListR2($titulo, $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_tipo_ouput', '');

            $divFloat = panelFloat($form, "panel-FloatB", $style);
            $s = layoutLSB($divFloat . $subMenu, $reporte, "panelB-R2");
            break;
        case "menuDetalle":
            $cod = get('codigo_menu_empresa');

            $btn = "Crear ]" . $enlace . "?MenuPerfil=FormDetalle&CodSubMenu=" . $cod . "]panelB-R2}";
            $btn .= "Editar ]" . $enlace . "?MenuPerfil=editaReg&codigo_menu_empresa=" . $cod . "]panelB-R2}";
            $btn = Botones($btn, 'botones1');
            $subMenu = tituloBtnPn("Detalle <p>MENÃš</p>", $btn, "150px", "TituloA");

            $sql = "SELECT Codigo, Nombre, TipoMenu, Url,Orden,Estado, Codigo AS CodigoAjax
                                    FROM menu_empresa_det
                                    WHERE Menu = '$cod'
                                    ORDER BY Codigo ASC ";

            $clase = 'reporteA';
            $enlaceCod = 'codigo_menu_empresa_det';
            $url = $enlace . "?MenuPerfil=editaRegDet";
            $panel = 'PanelInter';
            $reporte = ListR2($titulo, $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_tipo_ouput', '');

            $divFloat = panelFloat($form, "panel-FloatB", $style);
            $s = layoutLSB2($divFloat . $subMenu, $reporte, "panelB-R2");
            break;
        case "FormDetalle":
            $codMenu = get('CodSubMenu');
            $uRLForm = "Crear]" . $enlace . "?metodo=menu_empresa_det&transaccion=INSERT&Menu=" . $codMenu . "]panelB-R]F]}";
            $titulo = "Crear Sub-MenÃº";
            $form = c_form($titulo, $vConex, "menu_empresa_det", "CuadroA", $path, $uRLForm, "", $tSelectD);
            $s = PanelInferior($FBusqueda . $form, "panel_edit_menu", '370px');
            WE($s);
            break;
        case "editaRegDet":
            $codigo = get("codigo_menu_empresa_det");
            $titulo = "Editar Submenú";
            $uRLForm = "Actualizar]" . $enlace . "?metodo=menu_empresa_det&transaccion=UPDATE&codigo=" . $codigo . "]panelB-R]F]}";
            $uRLForm .="Eliminar]" . $enlace . "?metodo=menu_empresa_det&transaccion=DELETE&codigo=" . $codigo . "]panelB-R]]}";
            $form = c_form($titulo, $vConex, "menu_empresa_det", "CuadroA", $path, $uRLForm, $codigo, $tSelectD);
            $form = "<div style='width:400px;'>" . $form . "</div>";
            $s = PanelInferior($form, "panel_edit_menu", '320px');
            break;
        case "updtaePerflMaster":
            $btn = "Actualizar ]" . $enlace . "?MenuPerfil=actMaster]panelB-R}";
            $btn = Botones($btn, 'botones1');
            $subMenu = tituloBtnPn("Actualizar <p>MASTER</p>", $btn, "150px", "TituloA");

            $sql = "SELECT p.Codigo, m.Nombre,d.TipoMenu,p.Estado,p.Perfil, p.Codigo AS CodigoAjax
                                    FROM menu_empresa as m
                                    LEFT JOIN menu_empresa_perfil as p  ON m.Codigo = p.Menu
                                    LEFT JOIN menu_empresa_det as d ON p.MenuDetalle = d.Codigo WHERE Perfil = '1'
                                    Group by p.Codigo  ORDER BY p.Menu, p.MenuDetalle ASC";

            $clase = 'reporteA';
            $enlaceCod = 'codigo_menu_empresa';
            $url = $enlace . "?MenuPerfil=menuDetalle";
            $panel = 'panelB-R';
            $reporte = ListR2($titulo, $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, '', '');

            $s = 'AquÃ­ AutomÃ¡ticamente el usuario "Master" de cada empresa, podrÃ¡ tener acceso a todos los menÃºs y submenus y tambien se actualizarÃ¡ si se ingreso nuevos menÃºs y submenus';
            $s .= layoutLSB($divFloat . $subMenu, $reporte, "panelB-R2");
            break;
        case "actMaster":
            $perfil = $codigo;

            $n_s = "SELECT PaginaWeb FROM empresa";
            $resu = mysql_query($n_s, $vConex);
            while ($reg = mysql_fetch_array($resu)) {
                $s .= $reg["PaginaWeb"];
                $sql = "SELECT Codigo, Menu FROM menu_empresa_det ";
                $consulta = mysql_query($sql, $vConex);
                while ($r = mysql_fetch_array($consulta)) {

                    $sql = "SELECT Codigo
                                                            FROM menu_empresa_perfil
                                                            WHERE Menu ='" . $r["Menu"] . "' AND MenuDetalle = '" . $r["Codigo"] . "' AND Perfil ='1' AND Entidad = '" . $reg["PaginaWeb"] . "' ";
                    $rg = fetch($sql);
                    $codigo = $rg["Codigo"];
                    if ($codigo) {

                        $s .= 'ya ingreso<br>';
                    } else {

                        $sql = 'INSERT INTO menu_empresa_perfil (Menu,MenuDetalle,Estado,Perfil,Entidad)
                                                                    VALUES ("' . $r["Menu"] . '","' . ($r["Codigo"]) . '","Activo","1","' . $reg["PaginaWeb"] . '")';
                        xSQL($sql, $vConex);
                        $s .= 'deberia ingresa';
                    }
                }
            }
            break;
        case "PerfilSys":
            $subMenu = tituloBtnPn("<span>ACTUALIZA  PERFILES</span><p>Actualiza  en base en la plantilla</p><div class='bicel'></div>", $btn, "200px", "TituloA");

            $Q_Programas = "SELECT DISTINCT Entidad AS 'Nombre de la Empresa',
                            Entidad AS CodigoAjax
                            FROM  menu_empresa_perfil";

            $Url_Raiz_Link = "{$enlace}?MenuPerfil=PerfilesEmpresa";
            $Url_Add_Link = array("CodigoAjax" => "CodMEP");
            $Panel_Destino = 'panelB-R';
            $tablaReporte = ListR("", $Q_Programas, $cnOwl, 'reporteA', 'd', $Url_Raiz_Link, $Url_Add_Link, $Panel_Destino);

            $s = PanelUnico($subMenu, $tablaReporte, 'panelB-R2', '600px');
            break;
        case "PerfilDelete":
            $btn = "Eliminar ]" . $enlace . "?MenuPerfil=PerfilDeleteProcess]panelB-R}";
            $btn = Botones($btn, 'botones1');
            $subMenu = tituloBtnPn("<span>ELIMINACION</span><p>PERFILES FALLIDOS</p><div class='bicel'></div>", $btn, "200px", "TituloA");
            $s = PanelUnico($subMenu, $tablaReporte, 'panelB-R2', '600px');
            break;            
        case "PerfilDeleteProcess":
            $sql = "DELETE FROM menu_empresa_perfil WHERE Entidad = '' ";
            xSQL($sql, $vConex);
            W(MsgE("Eliminación Exitosa."));
            MenuPerfil('PerfilDelete');
            break;
        case "PerfilSysView":
            //Listar todos los perfiles
            $sql = "SELECT Descripcion,
                    Codigo AS CodigoAjax 
                    FROM usuario_perfil
                    WHERE Usuario = 'Sys'";

            $Url_Raiz_Link = "{$enlace}?MenuPerfil=detallePerfilView";
            $Url_Add_Link = array("CodigoAjax" => "perfil_cod");
            $Panel_Destino = 'panelB-R';
            $reporte = ListR("", $sql, $cnOwl, 'reporteA', 'd', $Url_Raiz_Link, $Url_Add_Link, $Panel_Destino);

            $subMenu = tituloBtnPn("<span>PERFILES SYS</span><p>Plantilla master de menu para las empresas</p><div class='bicel'></div>", null, "auto", "TituloA");
            $s = PanelUnico($subMenu, $reporte, 'panelB-R2', '600px');
            break;
        case "PerfilesEmpresa":
            $CodMEP = get("CodMEP");

            //Listar todos los perfiles de la empresa que esten en menu_empresa_perfil
            $Q_A = "SELECT DISTINCT Perfil 
                    FROM menu_empresa_perfil
                    WHERE  Entidad = '{$CodMEP}'";

            $sql = "SELECT
  UP.Descripcion,
                (
                    CASE
                        WHEN MED.Perfil <> ''  THEN '<div style=color:green >Definido</div>' ELSE 'Pendiente'
                    END
                ) AS Estado,
  UP.Codigo  AS CodigoAjax
                FROM usuario_perfil AS UP
                LEFT JOIN ({$Q_A}) AS MED ON MED.Perfil = UP.Codigo
                WHERE UP.Usuario = 'Sys'
                GROUP BY UP.Codigo";

            $Url_Raiz_Link = "{$enlace}?MenuPerfil=detallePerfilViewEmprPerfil&CodMEP={$CodMEP}";
            $Url_Add_Link = array("CodigoAjax" => "perfil_cod");
            $Panel_Destino = 'panelB-R';
            $reporte = ListR("", $sql, $cnOwl, 'reporteA', 'd,d', $Url_Raiz_Link, $Url_Add_Link, $Panel_Destino);

            $btn = "<i class=' icon-chevron-left'></i> Atrás]{$enlace}?MenuPerfil=PerfilSys]panelB-R}";
            $btn = Botones($btn, 'botones1');
            $subMenu = tituloBtnPn("<span>ACTUALIZA  EN BASE EN LA PLANTILLA</span><p>Perfiles de empresa - {$CodMEP}</p><div class='bicel'></div>", $btn, "auto", "TituloA");

            $s = PanelUnico($subMenu, $reporte, 'panelB-R2', '600px');
            break;
        case "detallePerfilView":
            $codigo = get("perfil_cod");

            $Q_Descripcion_perfil = "SELECT UP.Descripcion AS PerfilDesc
                    FROM usuario_perfil AS UP
                    WHERE UP.Codigo = {$codigo}";

            $PerfilDesc = (string) fetchOne($Q_Descripcion_perfil, $vConex)->PerfilDesc;

            $btn = "<i class=' icon-chevron-left'></i> Atrás]{$enlace}?MenuPerfil=PerfilSysView]panelB-R}";
            $btn .= "<i class='icon-ok-circle'></i> Activar]{$enlace}?MenuPerfil=OnOffItemItemPerfil&status=on&perfil_cod={$codigo}]panelB-R]CHECK}";
            $btn .= "<i class='icon-minus-sign'></i> Desactivar]{$enlace}?MenuPerfil=OnOffItemItemPerfil&status=off&perfil_cod={$codigo}]panelB-R]CHECK}";
            $btn .= "<i class='icon-refresh'></i> Actualizar / Definir]{$enlace}?MenuPerfil=PActualizaItemPerfil&perfil_cod={$codigo}]panelB-R]CHECK}";
            $btn = Botones($btn, 'botones1', "menu_layout");
            $subMenu = tituloBtnPn("<span>ACTUALIZAR ITEMS de SYS</span><p>PERFIL {$PerfilDesc}</p><div class='bicel'></div>", $btn, "auto", "TituloA");

            $sql = "SELECT 
                ME.Codigo AS CodigoAjax,
                ME.Nombre,
                IF(ISNULL(TAB1.Menu),'<div style=color:red>No hay ningún item definido</div>','<div style=color:green>Hay items definidos</div>') AS Estado
                FROM menu_empresa AS ME
                LEFT JOIN (
                    SELECT DISTINCT MEP.Menu 
                    FROM menu_empresa_perfil AS MEP
                    WHERE MEP.Entidad = 'Sys'
                    AND MEP.Perfil = {$codigo}
                ) AS TAB1 ON ME.Codigo = TAB1.Menu
                ORDER BY ME.Codigo ASC;";

            $Url_Raiz_Link = "{$enlace}?MenuPerfil=detalle_submenu&perfil_cod={$codigo}";
            $enlaceCod = "CodMenu";
            $Panel_Destino = 'panelB-R';
            $reporte = ListR2("", $sql, $cnOwl, 'reporteA', "", $Url_Raiz_Link, $enlaceCod, $Panel_Destino, "menu_layout", "checks");

            $s = PanelUnico($subMenu, $reporte, 'panelB-R2', '600px');
            break;
        case "detallePerfilViewEmprPerfil":
            $codigo = get("perfil_cod");
            $CodMEP = get("CodMEP");

            $Q_Descripcion_perfil = "SELECT UP.Descripcion AS PerfilDesc
                    FROM usuario_perfil AS UP
                    WHERE UP.Codigo = {$codigo}";

            $PerfilDesc = (string) fetchOne($Q_Descripcion_perfil, $vConex)->PerfilDesc;

            $btn = "<i class=' icon-chevron-left'></i> Atrás]{$enlace}?MenuPerfil=PerfilesEmpresa&CodMEP={$CodMEP}]panelB-R}";
            $btn .= "<i class='icon-ok-circle'></i> Activar]{$enlace}?MenuPerfil=OnOffItemItemPerfil_empresa&status=on&perfil_cod={$codigo}&CodMEP={$CodMEP}]panelB-R]CHECK}";
            $btn .= "<i class='icon-minus-sign'></i> Desactivar]{$enlace}?MenuPerfil=OnOffItemItemPerfil_empresa&status=off&perfil_cod={$codigo}&CodMEP={$CodMEP}]panelB-R]CHECK}";
            $btn .= "<i class=' icon-refresh'></i> Actualizar / Definir]{$enlace}?MenuPerfil=PActualizaItemPerfilEmpresa&perfil_cod={$codigo}&CodMEP={$CodMEP}]panelB-R]CHECK}";
            $btn = Botones($btn, 'botones1', "menu_layout");
            $subMenu = tituloBtnPn("<span>ACTUALIZAR ITEMS SEGUN CONFIGURACIÓN Sys</span><p>PERFIL {$PerfilDesc}</p><div class='bicel'></div>", $btn, "auto", "TituloA");

            $sql = "SELECT 
                ME.Codigo AS CodigoAjax,
                ME.Nombre,
                IF(ISNULL(TAB1.Menu),'<div style=color:red>No hay ningún item definido</div>','<div style=color:green>Hay items definidos</div>') AS Estado
                FROM menu_empresa AS ME
                LEFT JOIN (
                    SELECT DISTINCT MEP.Menu 
                    FROM menu_empresa_perfil AS MEP
                    WHERE MEP.Entidad = '{$CodMEP}'
                    AND MEP.Perfil = {$codigo}
                ) AS TAB1 ON ME.Codigo = TAB1.Menu
                ORDER BY ME.Codigo ASC;";

            $Url_Raiz_Link = "{$enlace}?MenuPerfil=detalle_submenu_empresa&CodMEP={$CodMEP}&perfil_cod={$codigo}";
            $enlaceCod = "CodMenu";
            $Panel_Destino = 'panelB-R';
            $reporte = ListR2("", $sql, $cnOwl, 'reporteA', "", $Url_Raiz_Link, $enlaceCod, $Panel_Destino, "menu_layout", "checks");

            $s = PanelUnico($subMenu, $reporte, 'panelB-R2', '600px');
            break;
        case "detalle_submenu":
            $codigo = (int) get("perfil_cod");
            $CodMenu = (int) get("CodMenu");

            $Q_Descripcion_perfil = "SELECT UP.Descripcion AS PerfilDesc
                    FROM usuario_perfil AS UP
                    WHERE UP.Codigo = {$codigo}";

            $PerfilDesc = (string) fetchOne($Q_Descripcion_perfil, $vConex)->PerfilDesc;

            $Q_DescMenu = "SELECT Nombre 
                FROM menu_empresa 
                WHERE Codigo = {$CodMenu}";

            $DescMenu = (string) fetchOne($Q_DescMenu, $vConex)->Nombre;

            $btn = "<i class=' icon-chevron-left'></i> Atrás]{$enlace}?MenuPerfil=detallePerfilView&perfil_cod={$codigo}]panelB-R}";
            $btn .= "<i class='icon-ok-circle'></i> Activar]{$enlace}?MenuPerfil=OnOffItemSubItemPerfil&status=on&perfil_cod={$codigo}&CodMenu={$CodMenu}]panelB-R]CHECK}";
            $btn .= "<i class='icon-minus-sign'></i> Desactivar]{$enlace}?MenuPerfil=OnOffItemSubItemPerfil&status=off&perfil_cod={$codigo}&CodMenu={$CodMenu}]panelB-R]CHECK}";
            $btn .= "<i class=' icon-refresh'></i> Actualizar / Definir]{$enlace}?MenuPerfil=PUpdateSubItem&perfil_cod={$codigo}&CodMenu={$CodMenu}]panelB-R]CHECK}";
            $btn = Botones($btn, 'botones1', "menu_layout");
            $subMenu = tituloBtnPn("<span>{$DescMenu} - Submenus de Sys</span><p>PERFIL {$PerfilDesc}</p><div class='bicel'></div>", $btn, "auto", "TituloA");

            $sql = "SELECT 
                MED.Codigo AS CodigoAjax,
                MED.Nombre,
                IF(ISNULL(TAB1.Estado),'<div style=color:red>No definido</div>',IF(TAB1.Estado='Activo','<div style=color:green><i class=icon-ok-sign></i> Activado</div>','<div style=color:grey><i class=icon-minus-sign></i> Desactivado</div>')) AS Estado
                FROM menu_empresa_det AS MED
                LEFT JOIN (
                    SELECT DISTINCT MEP.MenuDetalle, MEP.Menu, MEP.Estado
                    FROM menu_empresa_perfil AS MEP
                    WHERE MEP.Entidad = 'Sys'
                    AND MEP.Perfil = {$codigo}
                    AND  MEP.Menu = {$CodMenu}
                ) AS TAB1 ON MED.Codigo = TAB1.MenuDetalle
                ORDER BY MED.Codigo ASC";

            $Url_Raiz_Link;
            $enlaceCod;
            $Panel_Destino;
            $reporte = ListR2("", $sql, $cnOwl, "reporteA", "", $Url_Raiz_Link, $enlaceCod, $Panel_Destino, "menu_layout", "checks");

            $s = PanelUnico($subMenu, $reporte, 'panelB-R2', '600px');
            break;
        case "detalle_submenu_empresa":
            $codigo = (int) get("perfil_cod");
            $CodMEP = (string) get("CodMEP");
            $CodMenu = (int) get("CodMenu");

            $Q_Descripcion_perfil = "SELECT UP.Descripcion AS PerfilDesc
                    FROM usuario_perfil AS UP
                    WHERE UP.Codigo = {$codigo}";

            $PerfilDesc = (string) fetchOne($Q_Descripcion_perfil, $vConex)->PerfilDesc;

            $Q_DescMenu = "SELECT Nombre 
                FROM menu_empresa 
                WHERE Codigo = {$CodMenu}";

            $DescMenu = (string) fetchOne($Q_DescMenu, $vConex)->Nombre;

            $btn = "<i class=' icon-chevron-left'></i> Atrás]{$enlace}?MenuPerfil=detallePerfilViewEmprPerfil&CodMEP={$CodMEP}&perfil_cod={$codigo}]panelB-R}";
            $btn .= "<i class='icon-ok-circle'></i> Activar]{$enlace}?MenuPerfil=OnOffItemSubItemPerfil_empresa&status=on&perfil_cod={$codigo}&CodMEP={$CodMEP}&CodMenu={$CodMenu}]panelB-R]CHECK}";
            $btn .= "<i class='icon-minus-sign'></i> Desactivar]{$enlace}?MenuPerfil=OnOffItemSubItemPerfil_empresa&status=off&perfil_cod={$codigo}&CodMEP={$CodMEP}&CodMenu={$CodMenu}]panelB-R]CHECK}";
            $btn .= "<i class=' icon-refresh'></i> Actualizar / Definir]{$enlace}?MenuPerfil=PUpdateSubItem_empresa&perfil_cod={$codigo}&CodMEP={$CodMEP}&CodMenu={$CodMenu}]panelB-R]CHECK}";
            $btn = Botones($btn, 'botones1', "menu_layout");
            $subMenu = tituloBtnPn("<span>{$DescMenu} - Submenus </span><p>PERFIL {$PerfilDesc}</p><div class='bicel'></div>", $btn, "auto", "TituloA");

            $sql = "SELECT 
                MED.Codigo AS CodigoAjax,
                MED.Nombre,
                IF(ISNULL(TAB1.Estado),'<div style=color:red>No definido</div>',IF(TAB1.Estado='Activo','<div style=color:green><i class=icon-ok-sign></i> Activado</div>','<div style=color:grey><i class=icon-minus-sign></i> Desactivado</div>')) AS Estado
                FROM menu_empresa_det AS MED
                LEFT JOIN (
                    SELECT DISTINCT MEP.MenuDetalle, MEP.Menu, MEP.Estado
                    FROM menu_empresa_perfil AS MEP
                    WHERE MEP.Entidad = '{$CodMEP}'
                    AND MEP.Perfil = {$codigo}
                    AND  MEP.Menu = {$CodMenu}
                ) AS TAB1 ON MED.Codigo = TAB1.MenuDetalle
                ORDER BY MED.Codigo ASC";

            $Url_Raiz_Link;
            $enlaceCod;
            $Panel_Destino;
            $reporte = ListR2("", $sql, $cnOwl, "reporteA", "", $Url_Raiz_Link, $enlaceCod, $Panel_Destino, "menu_layout", "checks");

            $s = PanelUnico($subMenu, $reporte, 'panelB-R2', '600px');
            break;
        case "OnOffItemItemPerfil":
            //Este proceso barre todo los submenus de un menu y los activa
            $perfil = (int) get("perfil_cod");
            $status = get("status");

            switch ($status) {
                case "on":
                    $Estado = "Activo";
                    break;
                case "off":
                    $Estado = "Inactivo";
                    break;
                default:
                    MenuPerfil("detallePerfilView");
                    break;
            }

            //Definiendo si el array de codigo de menus existe para aplicar un filtro
            $MxcodMenu = post("ky");

            if (!$MxcodMenu) {
                W("Debes seleccionar un Módulo de menu para poder cambiar el estado a {$Estado} a los items...<br>");
                MenuPerfil("detallePerfilView");
            }

            $FILTER = implode(",", $MxcodMenu);

            $Q_UPDSubmenu = "UPDATE menu_empresa_perfil
                    SET Estado = '{$Estado}'
                    WHERE Menu IN({$FILTER})
                    AND Perfil = {$perfil} 
                    AND Entidad = 'Sys'";
            xSQL2($Q_UPDSubmenu, $vConex);

            W("Se cambio el estado a {$Estado} a todos los items de el(los) menu(s)<br>");
            MenuPerfil("detallePerfilView");
            break;
        case "OnOffItemItemPerfil_empresa":
            //Este proceso barre todo los submenus de un menu y los activa
            $perfil = (int) get("perfil_cod");
            $CodMEP = (string) get("CodMEP");
            $status = get("status");

            switch ($status) {
                case "on":
                    $Estado = "Activo";
                    break;
                case "off":
                    $Estado = "Inactivo";
                    break;
                default:
                    MenuPerfil("detallePerfilViewEmprPerfil");
                    break;
            }

            //Definiendo si el array de codigo de menus existe para aplicar un filtro
            $MxcodMenu = post("ky");

            if (!$MxcodMenu) {
                W("Debes seleccionar un Módulo de menu para poder cambiar el estado a {$Estado} a los items...<br>");
                MenuPerfil("detallePerfilViewEmprPerfil");
            }

            $FILTER = implode(",", $MxcodMenu);

            $Q_UPDSubmenu = "UPDATE menu_empresa_perfil
                    SET Estado = '{$Estado}'
                    WHERE Menu IN({$FILTER})
                    AND Perfil = {$perfil} 
                    AND Entidad = '{$CodMEP}'";
            xSQL2($Q_UPDSubmenu, $vConex);

            W("Se cambio el estado a {$Estado} a todos los items de el(los) menu(s)<br>");
            MenuPerfil("detallePerfilViewEmprPerfil");
            break;
        case "OnOffItemSubItemPerfil":
            //Este proceso barre todo los submenus de un menu y los activa
            $perfil = (int) get("perfil_cod");
            $CodMenu = (int) get("CodMenu");
            $status = get("status");

            switch ($status) {
                case "on":
                    $Estado = "Activo";
                    break;
                case "off":
                    $Estado = "Inactivo";
                    break;
                default:
                    MenuPerfil("detalle_submenu");
                    break;
            }

            //Definiendo si el array de codigo de menus existe para aplicar un filtro
            $MxcodSubMenu = post("ky");

            if (!$MxcodSubMenu) {
                W("Debes seleccionar un sub-menu para poder cambiar el estado a {$Estado}...<br>");
                MenuPerfil("detalle_submenu");
            }

            $FILTER = implode(",", $MxcodSubMenu);

            $Q_UPDSubmenu = "UPDATE menu_empresa_perfil
                    SET Estado = '{$Estado}'
                    WHERE Menu = {$CodMenu}
                    AND MenuDetalle IN({$FILTER})
                    AND Perfil = {$perfil} 
                    AND Entidad = 'Sys'";
            xSQL2($Q_UPDSubmenu, $vConex);

            W("Se cambio el estado a {$Estado} a el(los) submenu(s)<br>");
            MenuPerfil("detalle_submenu");
            break;
        case "OnOffItemSubItemPerfil_empresa":
            //Este proceso barre todo los submenus de un menu y los activa
            $perfil = (int) get("perfil_cod");
            $CodMenu = (int) get("CodMenu");
            $CodMEP = (string) get("CodMEP");
            $status = get("status");

            switch ($status) {
                case "on":
                    $Estado = "Activo";
                    break;
                case "off":
                    $Estado = "Inactivo";
                    break;
                default:
                    MenuPerfil("detalle_submenu_empresa");
                    break;
            }

            //Definiendo si el array de codigo de menus existe para aplicar un filtro
            $MxcodSubMenu = post("ky");

            if (!$MxcodSubMenu) {
                W("Debes seleccionar un sub-menu para poder cambiar el estado a {$Estado}...<br>");
                MenuPerfil("detalle_submenu_empresa");
            }

            $FILTER = implode(",", $MxcodSubMenu);

            $Q_UPDSubmenu = "UPDATE menu_empresa_perfil
                    SET Estado = '{$Estado}'
                    WHERE Menu = {$CodMenu}
                    AND MenuDetalle IN({$FILTER})
                    AND Perfil = {$perfil} 
                    AND Entidad = '{$CodMEP}'";
            xSQL2($Q_UPDSubmenu, $vConex);

            W("Se cambio el estado a {$Estado} a el(los) submenu(s)<br>");
            MenuPerfil("detalle_submenu_empresa");
            break;
        case "PActualizaItemPerfil":
            //Este proceso barre todo los submenus de un menu
            //Luego valida si el submenu esta ligado al perfil en la tabla menu_empresa_perfil
            $perfil = (int) get("perfil_cod");

            //Definiendo si el array de codigo de menus existe para aplicar un filtro
            $MxcodMenu = post("ky");

            if (!$MxcodMenu) {
                W("Debes seleccionar un Módulo de menu para poder actualizar los items...<br>");
                MenuPerfil("detallePerfilView");
            }

            $FILTER = implode(",", $MxcodMenu);

            $Q_Submenu = "SELECT Codigo,Menu 
                    FROM menu_empresa_det
                    WHERE Menu IN({$FILTER})";

            $MxSubMenu = fetchAll($Q_Submenu, $vConex);

            if (!$MxSubMenu) {
                W("No se encontro items para actualizar");
            }

            foreach ($MxSubMenu as $SubMenu) {
                $codSubmenu = (int) $SubMenu->Codigo;
                $codMenu = (int) $SubMenu->Menu;

                $Q_det_submenu = "SELECT Codigo
                        FROM menu_empresa_perfil
                        WHERE Menu = {$codMenu}
                        AND MenuDetalle = {$codSubmenu} 
                        AND Perfil = {$perfil} 
                        AND Entidad = 'Sys'";

                $cod_det_submenu = (int) fetchOne($Q_det_submenu, $vConex)->Codigo;

                if ($cod_det_submenu) {
                    $s .= "El submenu {$codSubmenu} ya esta registrado<br>";
                } else {
                    //Si el submenu no existe en el detalle de submenu del perfil
                    $data_INSERT = array(
                        "Menu" => $codMenu,
                        "MenuDetalle" => $codSubmenu,
                        "Estado" => "Activo",
                        "Perfil" => $perfil,
                        "Entidad" => "Sys"
                    );

                    insert("menu_empresa_perfil", $data_INSERT, $vConex);

                    $s .= "Se inserto {$codSubmenu} para el perfil {$perfil} en la entidad Sys<br>";
                }
            }
            W($s);
            MenuPerfil("detallePerfilView");
            break;
        case "PActualizaItemPerfilEmpresa":
            //Este proceso barre todo los submenus de la entidad Sys con su respectivo perfil del detalle de la tabla menu_empresa_perfil 
            $perfil = get("perfil_cod");
            $CodMEP = get("CodMEP");

            //Definiendo si el array de codigo de menus existe para aplicar un filtro
            $MxcodMenu = post("ky");

            if (!$MxcodMenu) {
                W("Debes seleccionar un Módulo de menu para poder actualizar los items...<br>");
                MenuPerfil("detallePerfilViewEmprPerfil");
            }

            $FILTER = implode(",", $MxcodMenu);

            $Q_det_submenu = "SELECT Codigo, Menu, MenuDetalle, Estado
                        FROM menu_empresa_perfil
                        WHERE Perfil = {$perfil} 
                        AND Entidad = 'Sys'
                        AND Menu IN({$FILTER})";

            $MxDet_submenu = fetchAll($Q_det_submenu, $vConex);

            if (!$MxDet_submenu) {
                W("No se encontro items para actualizar");
            }

            foreach ($MxDet_submenu as $Det_submenu) {
                $codSubmenu = (int) $Det_submenu->MenuDetalle;
                $codMenu = (int) $Det_submenu->Menu;
                $Estado = (string) $Det_submenu->Estado;

                $Q_det_submenu_aux = "SELECT Codigo
                        FROM menu_empresa_perfil
                        WHERE Menu = {$codMenu}
                        AND MenuDetalle = {$codSubmenu} 
                        AND Perfil = {$perfil} 
                        AND Entidad = '{$CodMEP}'";

                $cod_det_submenu = (int) fetchOne($Q_det_submenu_aux, $vConex)->Codigo;
                if ($cod_det_submenu) {
      Update("menu_empresa_perfil", ["Estado" => $Estado], ["Codigo" => $cod_det_submenu], $vConex);
                    $s .= "Se actualizo el Estado del submenu {$codSubmenu} para este perfil<br>";
                } else {
                    //Si el submenu no existe en el detalle de submenu del perfil
                    $data_INSERT = array(
                        "Menu" => $codMenu,
                        "MenuDetalle" => $codSubmenu,
                        "Estado" => "Activo",
                        "Perfil" => $perfil,
                        "Entidad" => $CodMEP
                    );

                    insert("menu_empresa_perfil", $data_INSERT, $vConex);

                    $s .= "Se inserto {$codSubmenu} para el perfil {$perfil} de la entidad {$CodMEP}<br>";
                }
            }
            W($s);
            MenuPerfil("detallePerfilViewEmprPerfil");
            break;
        case "PUpdateSubItem":
            //Este proceso barre todo los submenus de la entidad Sys con su respectivo perfil del detalle de la tabla menu_empresa_perfil 
            $perfil = get("perfil_cod");
            $CodMenu = get("CodMenu");

            //Definiendo si el array de codigo de menus existe para aplicar un filtro
            $MxcodSubMenu = post("ky");

            if (!$MxcodSubMenu) {
                W("Debes seleccionar un Módulo de Submenu...<br>");
                MenuPerfil("detalle_submenu");
            }

            foreach ($MxcodSubMenu as $codSubmenu) {
                $Q_det_submenu_aux = "SELECT Codigo
                        FROM menu_empresa_perfil
                        WHERE Menu = {$CodMenu}
                        AND MenuDetalle = {$codSubmenu} 
                        AND Perfil = {$perfil} 
                        AND Entidad = 'Sys'";

                $cod_det_submenu = (int) fetchOne($Q_det_submenu_aux, $vConex)->Codigo;
                if ($cod_det_submenu) {
                    ;
                    $s .= "El submenu {$codSubmenu} ya esta registrado<br>";
                } else {
                    //Si el submenu no existe en el detalle de submenu del perfil
                    $data_INSERT = array(
                        "Menu" => $CodMenu,
                        "MenuDetalle" => $codSubmenu,
                        "Estado" => "Activo",
                        "Perfil" => $perfil,
                        "Entidad" => "Sys"
                    );

                    insert("menu_empresa_perfil", $data_INSERT, $vConex);

                    $s .= "Se inserto {$codSubmenu} para el perfil {$perfil} en la entidad Sys<br>";
                }
            }
            W($s);
            MenuPerfil("detalle_submenu");
            #MenuPerfil("PUpdateSubItem_empresa");
            
            break;
        case "PUpdateSubItem_empresa":
            //Este proceso barre todo los submenus de la entidad Sys con su respectivo perfil del detalle de la tabla menu_empresa_perfil 
            $perfil = get("perfil_cod");
            $CodMEP = get("CodMEP");
            $CodMenu = get("CodMenu");

            //Definiendo si el array de codigo de menus existe para aplicar un filtro
            $MxcodSubMenu = post("ky");

            if (!$MxcodSubMenu) {
                W("Debes seleccionar un Módulo de Submenu...<br>");
                MenuPerfil("detalle_submenu_empresa");
            }

            $FILTER = implode(",", $MxcodSubMenu);

            $Q_det_submenu = "SELECT Codigo, Menu, MenuDetalle, Estado
                        FROM menu_empresa_perfil
                        WHERE Perfil = {$perfil} 
                        AND Entidad = 'Sys'
                        AND Menu = {$CodMenu}
                        AND MenuDetalle IN({$FILTER})";

            $MxDet_submenu = fetchAll($Q_det_submenu, $vConex);

            if (!$MxDet_submenu) {
                W("No se encontro submenus para actualizar");
            }

            foreach ($MxDet_submenu as $Det_submenu) {
                $codSubmenu = (int) $Det_submenu->MenuDetalle;
                $codMenu = (int) $Det_submenu->Menu;
                $Estado = (string) $Det_submenu->Estado;

                $Q_det_submenu_aux = "SELECT Codigo
                        FROM menu_empresa_perfil
                        WHERE Menu = {$codMenu}
                        AND MenuDetalle = {$codSubmenu} 
                        AND Perfil = {$perfil} 
                        AND Entidad = '{$CodMEP}'";

                $cod_det_submenu = (int) fetchOne($Q_det_submenu_aux, $vConex)->Codigo;
                if ($cod_det_submenu) {
      Update("menu_empresa_perfil", ["Estado" => $Estado], ["Codigo" => $cod_det_submenu], $vConex);
                    $s .= "Se actualizo el Estado del submenu {$codSubmenu} para este perfil<br>";
                } else {
                    //Si el submenu no existe en el detalle de submenu del perfil
                    $data_INSERT = array(
                        "Menu" => $codMenu,
                        "MenuDetalle" => $codSubmenu,
                        "Estado" => "Activo",
                        "Perfil" => $perfil,
                        "Entidad" => $CodMEP
                    );

                    insert("menu_empresa_perfil", $data_INSERT, $vConex);

                    $s .= "Se inserto {$codSubmenu} para el perfil {$perfil} de la entidad {$CodMEP}<br>";
                }
            }
            W($s);
            MenuPerfil("detalle_submenu_empresa");
            break;
        case "detallePerfil":
            $perfil = get("perfil_cod");

            $sql = "SELECT Codigo, Menu FROM menu_empresa_det ";
            $consulta = mysql_query($sql, $vConex);
            while ($r = mysql_fetch_array($consulta)) {

                $sql = "SELECT Codigo
                                                            FROM menu_empresa_perfil
                                                            WHERE Menu ='" . $r["Menu"] . "' AND MenuDetalle = '" . $r["Codigo"] . "' AND Perfil ='" . $perfil . "' AND Entidad = 'Sys' ";
                $rg = fetch($sql);
                $codigo = $rg["Codigo"];

                if ($codigo) {

                    $s .= 'ya ingreso el Menu  ' . $r["Codigo"] . ' <br>';
                } else {

                    $sql = 'INSERT INTO menu_empresa_perfil (Menu,MenuDetalle,Estado,Perfil,Entidad)
                                                    VALUES ("' . $r["Menu"] . '","' . ($r["Codigo"]) . '","Activo","' . $perfil . '","Sys")';
                    xSQL($sql, $vConex);
                    $s .= 'Se inserto el Menu  ' . $r["Codigo"] . ' <br>';
                }
            }
            break;
        case "EditarMenuPerfil":
            $Perfil_cod = get("perfil_cod");
            $Codigo = get("Codigo");
            $uRLForm = "Actualizar]" . $enlace . "?metodo=menu_empresa_perfil_edit&transaccion=UPDATE&Codigo=" . $Codigo . "&perfil_cod=" . $Perfil_cod . "]panelB-R]F]}";
            $titulo = "AÃ±adir Detalle";
            $tSelectD = '';
            $form = c_form($titulo, $vConex, "menu_empresa_perfil_edit", "CuadroA", $path, $uRLForm, $Codigo, $tSelectD);
            $s = "<div style='width:280px;padding:0px 0px 0px 30px;'>" . $form . "</div>";
            break;
        case "EditarMenuPerfilEmpresa":
            ## PARAMETRO POR DEFECTO DE REPORTE (ListR3)
            $pagina_start = get('pagina-start');

            $CodMEP = get("CodMEP");
            $Perfil_cod = get("perfil_cod");
            $Codigo = get("Codigo");

            $uRLForm = "Actualizar]{$enlace}?metodo=menu_empresa_perfil_edit_empresa&transaccion=UPDATE&Codigo={$Codigo}&perfil_cod={$Perfil_cod}&CodMEP={$CodMEP}&pagina-start={$pagina_start}]panelB-R]F]}";
            $titulo = "Añadir Detalle";
            $tSelectD = '';
            $form = c_form($titulo, $vConex, "menu_empresa_perfil_edit", "CuadroA", $path, $uRLForm, $Codigo, $tSelectD);
            $s = "<div style='width:280px;padding:0px 0px 0px 30px;'>" . $form . "</div>";
            break;
    }

    WE($s);
}

function Perfil($parm) {
    global $vConex, $enlace;
    //	echo $parm; exit;
    if ($parm == "Listado") {
        $btn = "Crear ]" . $enlace . "?Perfil=Form]panelB-R2}";
        $btn .= "Detalle ]" . $enlace . "?Perfil=Detalle]panelB-R}";
        $btn .= "<div class='actualizar'></div>]" . $enlace . "?Perfil=Listado]panelB-R}";
        $btn = Botones($btn, 'botones1');
        $subMenu = tituloBtnPn("Listado <p>MENÃš PERFIL</p>", $btn, "200px", "TituloA");

        $sql = 'SELECT Codigo, Menu, MenuDetalle,Estado, Perfil, Entidad, Codigo AS CodigoAjax FROM menu_empresa_perfil ORDER BY Codigo ASC ';

        $clase = 'reporteA';
        $enlaceCod = 'codigo_perfil';
        $url = $enlace . "?Perfil=editaReg";
        $panel = 'panelB-R2';
        $reporte = ListR2($titulo, $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_tipo_ouput', '');

        $divFloat = panelFloat($form, "panel-FloatB", $style);
        $s = layoutLSB($divFloat . $subMenu, $reporte, "panelB-R2");
    }

    WE($s);
}

function vistaCT($parm) {
    global $vConex, $enlace;
####################################################  funcion tablas 1111111111111
    $pestanas = getPestanasHtml($enlace, 'tablas');

    $btn = "Crear Tabla]Abrir]panel-Float}";
    // $btn .= "Subit Tablas]".$enlace."?actualizaTabla=tablas]cuerpo}";
    $btn .= "Subit Tablas]" . $enlace . "?Tablas=SubirTablas]cuerpo}";
    $btn .= "<div class='botIconS'><i class='icon-signin'></i></div>]" . $enlace . "?Tablas=Importar-Seleccion]cuerpo}";
    $btn = Botones($btn, 'botones1', '');
    $btn = tituloBtnPn("<span>Tablas</span><p>DEL SISTEMA</p><div class='bicel'></div>", $btn, "300px", "TituloA");
    if ($parm == 'tablas') {

        $sql = "SELECT
			Codigo
			,Descripcion
			,DATE_FORMAT(FechaRegistro, '%d %M %Y') AS FechaCreacion
			, Codigo AS CodigoAjax
		 FROM sys_tabla
		 ORDER BY  FechaRegistro Desc
		";
        $clase = 'reporteA';
        $enlaceCod = 'codigoSysTabla';
        $url = $enlace . "?accionCT=FormDet";
        $panel = 'panelB-R';
        $reporte = ListR2("", $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_tabla', '', '');

        // $path = array('Imagen' => '../_files/','ImagenMarca' => '../_files/');
        $uRLForm = "Guardar]" . $enlace . "?metodo=sys_tabla1&transaccion=INSERT]cuerpo]F]panel-Float}";
        $titulo = "Crear Tabla";
        $form = c_form($titulo, $vConex, "sys_tabla1", "CuadroA", $path, $uRLForm, '', '');
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $style = "left:170px;top:0px;";
        $divFloat = panelFloat($form, "panel-Float", $style);

        $panelA = $divFloat . $btn . pAnimado1($reporte);
        $s = layoutL($pestanas, $panelA);
        WE($s);
    }

    if ($parm == 'FormDet') {
         ////aaaaaaaaaaa
        $codigoSysTabla = get("codigoSysTabla");
        $btn = "+ Campo]Abrir]panel-FloatB}";
        $btn .= "Eliminar Tabla]Abrir]panel-FloatC}";
        $btn .= "Elimina Reg]" . $enlace . "?ProcesosTab=EliminaTabLog&Tabla={$codigoSysTabla}&transaccion=INSERT]panelB-R]}";
     //   $btn .= "Exportar SQL]" . $enlace . "?ProcesosTab=EliminaTabLog&Tabla={$codigoSysTabla}&transaccion=INSERT]panelB-R]}";
		
        $btn = Botones($btn, 'botones1', '');
        $btn = tituloBtnPn("<span>Detalle de la tabla</span><p>" . $codigoSysTabla . "</p><div class='bicel'></div>", $btn, "500px", "TituloA");

        $sql = 'SELECT Codigo,Descripcion,TipoCampo ,Codigo AS CodigoAjax FROM sys_tabla_det ';
        $sql .= ' WHERE sys_tabla = "' . $codigoSysTabla . '"';
        $clase = 'reporteA';
        $enlaceCod = 'codigo_sys_tabla_det';
        $url = $enlace . "?accionCT=Editar";
        $panel = 'panelB-R';
        $reporte = ListR2("", $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_form_det', '', '');

        $titulo = "Crear Campo e";
        $uRLForm = "Guardar]" . $enlace . "?metodo=sysTabletDet&transaccion=INSERT&codigoSysTabla=" . $codigoSysTabla . "]panelB-R]F]panel-FloatB}";
        $tSelectD = array('TipoCampo' => 'SELECT Codigo,Descripcion FROM sys_tipo_input');

        $form = c_form($titulo, $vConex, "sysTabletDet", "CuadroA", $path, $uRLForm, "", $tSelectD);
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $style = "left:10px;top:0px;";

        $btnEF = "Eliminar Tabla]" . $enlace . "?accionCT=EliminarTabla&codigoSysTabla=" . $codigoSysTabla . "]layoutV]panel-FloatC}";
        $btnEF = Botones($btnEF, 'botones1', '');
        $btnEliminarTbF = tituloBtnPn("<span>Eliminar Tabla</span><p>CONFIRMA OTRA VES</p><div class='bicel'></div>", $btnEF, "200px", "TituloA");

        $divFloat = panelFloat($form, "panel-FloatB", $style);
        $divFloatC = panelFloat("<div style='float:left;padding:40px 0px 0px 20px;width:400px;'>" . $btnEliminarTbF . "</div>", "panel-FloatC", $style);
        $s = layoutV($divFloat . $divFloatC . $btn, $reporte);
        WE($s);
    }

    if ($parm == 'Editar') {

        $codigo_sys_tabla_det = get("codigo_sys_tabla_det");
        $sql = 'SELECT sys_tabla,Descripcion,TipoCampo ,Codigo AS CodigoAjax FROM sys_tabla_det ';
        $sql .= ' WHERE Codigo = "' . $codigo_sys_tabla_det . '"';
        $rg = fetch($sql);
        $sys_tabla = $rg["sys_tabla"];

        $btn = "Crear Campo]Abrir]panel-FloatB}";
        $btn = Botones($btn, 'botones1', '');

        $btn = tituloBtnPn("<span>Detalle de la tabla </span><p>" . $codigo_sys_tabla_det . " -  " . $sys_tabla . "</p><div class='bicel'></div>", $btn, "260px", "TituloA");
        //mmmmmmmmm
        $uRLForm = "Actualizar]" . $enlace . "?metodo=sysTabletDet&transaccion=UPDATE&cod=" . $codigo_sys_tabla_det . "&codigoSysTabla=" . $sys_tabla . "]panelB-R]F]}";
        $uRLForm .="Eliminar]" . $enlace . "?metodo=sysTabletDet&transaccion=DELETE&cod=" . $codigo_sys_tabla_det . "&codigoSysTabla=" . $sys_tabla . "]panelB-R]F]}";
        $tSelectD = array('TipoCampo' => 'SELECT Codigo,Descripcion FROM sys_tipo_input');
        $form = c_form($titulo, $vConex, "sysTabletDet", "CuadroA", $path, $uRLForm, $codigo_sys_tabla_det, $tSelectD);
        $form = "<div style='width:500px;'>" . $form . "</div>";
        $s = layoutV($btn, $form);
        WE($s);
    }

    if ($parm == 'EliminarTabla') {

        $codigoSysTabla = get("codigoSysTabla");
        $sql = 'DELETE FROM sys_tabla WHERE  Codigo = "' . $codigoSysTabla . '" ';
        $s = xSQL($sql, $vConex);
        $sql = 'DELETE FROM sys_tabla_det WHERE  sys_tabla = "' . $codigoSysTabla . '" ';
        $s = xSQL($sql, $vConex);

        $sql = 'DROP TABLE IF EXISTS ' . $codigoSysTabla . ';';

        $s = xSQL($sql, $vConex);
        WE("Se elimino Correctamente  " . $codigoSysTabla);
    }
}

function detalleForm($parm) {
    global $vConex, $enlace;

    $cod = get('codigoForm');

    if ($parm == 'detalle') {

        $btn .= "<div class='botIconS'><i class='icon-arrow-left'></i></div>]" . $enlace . "]cuerpo}";
        $btn .= "<div class='botIconS'><i class='icon-edit'></i></div>]Abrir]panel-FloatC}";
        $btn .= "<div class='botIconS'><i class='icon-trash'></i></div>]" . $enlace . "?accionForm=Eliminar&codigoForm=" . $cod . "]layoutV]CHECK}";
        // $btn .= "<div class='botIconS'><i class='icon-align-justify'></i></div>]".$enlace."?generarScrip=Generar&codigoForm=".$cod."]layoutV}";
        $btn .= "<div class='botIconS'><i class='icon-copy'></i></div>]" . $enlace . "?muestra=Copia-Formulario&codigoForm=" . $cod . "]PanelInferior}";
        $btn = Botones($btn, 'botones1', 'sys_form_det');

        $titulo = "<span>Detalle </span><p>FORMULARIO " . $cod . "</p><div class='bicel'></div>";
        $btn = tituloBtnPn($titulo, $btn, "300px", "TituloA");

        $path = array('Imagen' => '../_files/', 'ImagenMarca' => '../_files/');
        $uRLForm = "Guardar]" . $enlace . "?metodo=sysformdet2&transaccion=INSERT&codigoForm=" . $cod . "]layoutV]F]panel-Float}";
        $titulo = "AÃ‘ADIR CAMPO";

        $sql = 'SELECT Tabla FROM sys_form WHERE Codigo = "' . $cod . '" ';
        $rg = fetch($sql);
        $tabla = $rg["Tabla"];

        $tSelectD = array('NombreCampo' => 'SELECT Descripcion as Cod,Descripcion FROM sys_tabla_det WHERE sys_tabla = "' . $tabla . '" ');
        $form = c_form("AÃ±adir Campo ", $vConex, "sysformdet2", "CuadroA", $path, $uRLForm, "", $tSelectD);
        $style = "left:10px;top:-50px;width:500px;";
        $divFloat = panelFloat($form, "panel-FloatC", $style);

        $sql = 'SELECT
		NombreCampo
		,Alias
		,TipoOuput
		,TipoInput
		,Visible
		,Correlativo AS Corr
		,AutoIncrementador AS AutIn
		, Posicion AS P
		, Codigo AS CodigoAjax FROM sys_form_det ';
        $sql .= ' WHERE Form = "' . $cod . '" ORDER BY Posicion';
        $clase = 'reporteA';
        $enlaceCod = 'codigoFormDet';
        $url = $enlace . "?muestra=form&codigoForm=" . $cod . "";
        $panel = 'layoutV';
        $rpt = ListR2("", $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_form_det', 'checks', '');
        $rpt = "<div id = 'PanelInferior' style='float:left;width:100%;' >" . $rpt . "</div>";

        $s = layoutV($btn . $divFloat, $rpt);
    }

    if ($parm == 'form') {

        $btn .= "<div class='botIconS'><i class='icon-arrow-left'></i></div>]" . $enlace . "?muestra=detalle&codigoForm=" . $cod . "]layoutV}";
        $btn = Botones($btn, 'botones1', 'sys_form_det');

        $titulo = "<span>Formatear </span><p>FORMULARIO " . $cod . "</p><div class='bicel'></div>";
        $btn = tituloBtnPn($titulo, $btn, "80px", "TituloA");

        $CodDet = get('codigoFormDet');
        $sql = 'SELECT Form FROM sys_form_det WHERE  Codigo = "' . $CodDet . '" ';
        $rg = fetch($sql);
        $form = $rg["Form"];

        $sql = 'SELECT Tabla FROM sys_form WHERE  Codigo = "' . $form . '" ';
        $rg = fetch($sql);
        $tabla = $rg["Tabla"];

        $uRLForm = "Actualizar]" . $enlace . "?metodo=sysformdet2&transaccion=UPDATE&codformdet=" . $CodDet . "&codigoForm=" . $form . "]layoutV]F]}";
        $tSelectD = array('NombreCampo' => 'SELECT Descripcion as Cod,Descripcion FROM sys_tabla_det WHERE sys_tabla = "' . $tabla . '" ');
        $s = c_form("", $vConex, "sysformdet2", "CuadroA", "", $uRLForm, $CodDet, $tSelectD);
        $s = "<div id = 'PanelInferior' style='float:left;width:100%;' >" . $s . "</div>";
        $s = layoutV($btn, $s);
    }

    if ($parm == 'Copia-Formulario') {
        $uRLForm = "COPIAR]" . $enlace . "?muestra=Copia-Process&codigoForm=" . $cod . "]PanelInferior]F]}";
        $titulo = "AÃ‘ADIR CAMPO";
        $tSelectD = array('NombreCampo' => 'SELECT Descripcion as Cod,Descripcion FROM sys_tabla_det WHERE sys_tabla = "' . $tabla . '" ');
        $s = c_form("REDEFINIR NOMBRE DEL NUEVO FORMULARIO", $vConex, "CopiaFormulario", "CuadroA", $path, $uRLForm, '', $tSelectD);
        WE($s);
    }

    if ($parm == 'Copia-Process') {

        $codigoForm = get("codigoForm");

        $sql = 'SELECT * FROM sys_form WHERE Codigo = "' . $codigoForm . '" ';
        $consulta = Matris_Datos($sql, $vConex);

        while ($reg = mysql_fetch_array($consulta)) {

            $sql2 = " INSERT INTO sys_form (Codigo, Descripcion, DescripcionExtendida,Tabla, Estado)
			VALUES ('" . post("Codigo") . "', 'Form_" . post("Descripcion") . "', '" . post("DescripcionExtendida") . "', '" . $reg["Tabla"] . "', 'Activo') ";
            xSQL($sql2, $vConex);

            $sql = 'SELECT * FROM sys_form_det WHERE  Form = "' . $reg["Codigo"] . '" ';
            $consultaB = Matris_Datos($sql, $vConex);

            while ($regB = mysql_fetch_array($consultaB)) {

                $Codigo_Correlativo = numeradorB("sys_form_det", 10, '', $vConex);
                $condiciones[0] = " Codigo='" . $regB["Codigo"] . "' ";

                $CampoModificado = array('Form' => post("Codigo"));
                $vSQLC = GeneraScriptGen($vConex, "sys_form_det", $condiciones, $Codigo_Correlativo, $CampoModificado);

                echo '<pre>';
                print_r($vSQLC);
                echo '</pre>';
                xSQL($vSQLC, $vConex);
            }
        }

        WE("");
    }

    WE(pAnimado1($s));
}

function detalleFormB() {
    global $vConex, $enlace;
    $codReg = get('codigoArticulo');

    $path = array('Imagen' => '../_files/', 'ImagenMarca' => '../_files/');
    $uRLForm = "" . $enlace . "?metodo=procesaForm&FArticulos1=INSERT";
    $s = c_form($vConex, "FArticulos1", "CuadroA", $path, $uRLForm);
    WE(pAnimado1($s));
}

function Formularios($Arg) {

    global $vConex, $enlace;

    if ($Arg == "EliminarFormularios") {

        $campos = post("ky");
        for ($j = 0; $j < count($campos); $j++) {

            DReg("sys_form_det", "Form", "'" . $campos[$j] . "'", $vConex);
            DReg("sys_form", "Codigo", "'" . $campos[$j] . "'", $vConex);
            W("Se EliminÃ³ el formulario " . $campos[$j] . "  <br>");
        }

        WE($btn_titulo . $s);
    }


    if ($Arg == "Importar-Seleccion") {

        $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]" . $enlace . "]cuerpo}";
        $btn .= "Importar Excel]" . $enlace . "?ImportarExportar=Importar]cuerpo}";
        $btn .= "Exportar Excel]" . $enlace . "?ImportarExportar=Exportar]layoutV}";
        $btn = Botones($btn, 'botones1', '');
        $titulo = "<span>Importar Formulario </span><p>CONECTATE A UNA BASE DE DATOS</p><div class='bicel'></div>";
        $btn_titulo = tituloBtnPn($titulo, $btn, "330px", "TituloA");

        $uRLForm = "Siguiente]" . $enlace . "?Formularios=Importar-Seleccion-Form&codformdet=" . $CodDet . "&codigoForm=" . $form . "]layoutV]F]}";
        $tSelectD = array('Nombre' => 'SELECT Nombre, Nombre  FROM sys_base_datos  ');
        $form = c_form("", $vConex, "select_bdatos", "CuadroA", "", $uRLForm, $CodDet, $tSelectD);

        $s = "<div id = 'PanelInferior' style='float:left;width:100%;' >" . $form . "</div>";

        WE($btn_titulo . $s);
    }
######### RRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRR
    if ($Arg == "Importar-Archivo-Excel") {

        $Base_Datos = post("Nombre");
        $ConexionBExt = conexSis_Emp($Base_Datos);
        $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]" . $enlace . "]cuerpo}";
        $btn = Botones($btn, 'botones1', '');
        $titulo = "<span>Importar Formulario </span><p>DESDE UN ARCHIVO</p><div class='bicel'></div>";
        $btn_titulo = tituloBtnPn($titulo, $btn, "230px", "TituloA");
        #  $btn_titulo = tituloBtnPn($titulo, $btn, "380px", "TituloA");
        $menu_titulo = tituloBtnPn( "<span>INICIAR CARGA</span><p style='font-weight:600;'>Importar Archivo</p>", $btn, '80px', 'TituloA' );
        $uRLForm = "Cargar Archivo]{$enlace}?metodo=FImportarFormulario&transaccion=INSERT]panelB-R]F]}";
        $tSelectD = "";
        $path = array( 'Archivo' => '../../from/' );
        #Proceso para crear la carpeta de la Empresa donde guardar los archivos
        if(!file_exists($path['Archivo'])){
            W("La carpeta 'Formatos' no existe en esta empresa...!!!<br>");
            W("Creando la carpeta...!!<br>");
            if(!mkdir($path['Archivo'],0777,true)){
                W("ERROR: No se pudo crear la carpeta.!!!<br>");
            }else{
                W("&check; Se creo la carpeta ..!!! <br>");
            }
        }

        $form = c_form_adp( '', $vConex, "FImportarFormulario", "CuadroA1FR", $path, $uRLForm, $Codigo, $tSelectD, 'Codigo' );
        $s= "{$btn_titulo} <div style='width: 300px;' >{$form}</div>";

        WE($s);
    }




    if ($Arg == "Importar-Seleccion-Form") {

        $Base_Datos = post("Nombre");
        $ConexionBExt = conexSis_Emp($Base_Datos);

        $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]" . $enlace . "?Formularios=Importar-Seleccion&Base_Datos=" . $Base_Datos . "]layoutV}";
        $btn .= "Importar]" . $enlace . "?Formularios=Importar-Seleccion-UPForm&Base_Datos=" . $Base_Datos . "]layoutV]CHECK}";
        $btn = Botones($btn, 'botones1', 'sys_form');
        $titulo = "<span>Importar Formulario </span><p>BD " . $Base_Datos . "</p><div class='bicel'></div>";
        $btn_titulo = tituloBtnPn($titulo, $btn, "170px", "TituloA");

        $sql = 'SELECT Codigo AS Formulario
		, Tabla
		,  DATE_FORMAT(FechaHoraCreacion, "%d %M %Y") AS FechaCreacion
		, Codigo AS CodigoAjax
		FROM sys_form
		ORDER BY  FechaHoraCreacion DESC
		';
        $clase = 'reporteA';
        $enlaceCod = 'codigoForm';
        $url = $enlace . "?Formularios=Importar-Seleccion-UPForm&Base_Datos=" . $Base_Datos . "";
        $panel = 'layoutV';

        $reporte = ListR2("", $sql, $ConexionBExt, $clase, '', $url, $enlaceCod, $panel, 'sys_form', 'checks', '');
        $reporte = "<div id = 'PanelInferior' style='float:left;width:100%;' >" . $reporte . "</div>";
        WE($btn_titulo . $reporte);
    }

    if ($Arg == "Importar-Seleccion-UPForm") {

        $Base_Datos = get("Base_Datos");
        $campos = post("ky");
        for ($j = 0; $j < count($campos); $j++) {


            $sql = 'SELECT Tabla FROM sys_form WHERE  Codigo = "' . $campos[$j] . '" ';
            $rg = fetch($sql);
            $tabla = $rg["Tabla"];

            if (empty($tabla)) {

                $ConexionBExt = conexSis_Emp($Base_Datos);
                $condiciones[0] = " Codigo = '" . $campos[$j] . "' ";

                $vSQLA = GeneraScriptGen($ConexionBExt, "sys_form", $condiciones, "", "");
                $vConexR = conexSis_Emp("owlgroup_owl");
                xSQL($vSQLA, $vConexR);

                $ConexionBExt = conexSis_Emp($Base_Datos);
                $sql = 'SELECT * FROM sys_form_det WHERE  Form = "' . $campos[$j] . '" ';
                $consulta = Matris_Datos($sql, $ConexionBExt);

                $cont = 0;
                while ($reg = mysql_fetch_array($consulta)) {

                    $vConexB = conexSis_Emp("owlgroup_owl");
                    $Codigo_Correlativo = numeradorB("sys_form_det", 10, '', $vConexB);

                    $ConexionBExt = conexSis_Emp($Base_Datos);
                    $condiciones[0] = " Codigo='" . $reg["Codigo"] . "' ";
                    $vSQLC = GeneraScriptGen($ConexionBExt, "sys_form_det", $condiciones, $Codigo_Correlativo, "");
                    echo '<pre>';
                    print_r($vSQLC);
                    echo '</pre>';
                    $vConexR = conexSis_Emp("owlgroup_owl");
                    xSQL($vSQLC, $vConexR);
                }
            }
        }

        WE("");
    }
}

function ImportarExportar($Arg){
    global $vConex, $enlace;
    switch($Arg){
        case "Exportar":

            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]" . $enlace . "]cuerpo}";
            $btn = Botones($btn, 'botones1', '');
            $titulo = "<span>EXPORTAR FORMULARIO </span><p>CONECTATE A UNA BASE DE DATOS</p><div class='bicel'></div>";
            $btn_titulo = tituloBtnPn($titulo, $btn, "330px", "TituloA");

            $uRLForm = "Siguiente]" . $enlace . "?ImportarExportar=Exportar-Seleccion-Form&codformdet=" . $CodDet . "&codigoForm=" . $form . "]layoutV]F]}";
            $tSelectD = array('Nombre' => 'SELECT Nombre, Nombre  FROM sys_base_datos  ');
            $form = c_form("", $vConex, "select_bdatos", "CuadroA", "", $uRLForm, $CodDet, $tSelectD);
            $s = "<div id = 'PanelInferior' style='float:left;width:100%;' >" . $form . "</div>";

            WE($btn_titulo . $s);
            /*
                    $Base_Datos = post("Nombre");
                    $ConexionBExt = conexSis_Emp($Base_Datos);
                    $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]" . $enlace . "]cuerpo}";
                    $btn = Botones($btn, 'botones1', '');
                    $titulo = "<span>Importar Formulario </span><p>DESDE UN ARCHIVO</p><div class='bicel'></div>";
                    $btn_titulo = tituloBtnPn($titulo, $btn, "230px", "TituloA");
                    #  $btn_titulo = tituloBtnPn($titulo, $btn, "380px", "TituloA");
                    $menu_titulo = tituloBtnPn( "<span>INICIAR CARGA</span><p style='font-weight:600;'>Importar Archivo</p>", $btn, '80px', 'TituloA' );
                    $uRLForm = "Cargar Archivo]{$enlace}?metodo=FImportarFormulario&transaccion=INSERT]panelB-R]F]}";
                    $tSelectD = "";
                    $path = array( 'Archivo' => '../../from/' );
                    #Proceso para crear la carpeta de la Empresa donde guardar los archivos
                    if(!file_exists($path['Archivo'])){
                        W("La carpeta 'Formatos' no existe en esta empresa...!!!<br>");
                        W("Creando la carpeta...!!<br>");
                        if(!mkdir($path['Archivo'],0777,true)){
                            W("ERROR: No se pudo crear la carpeta.!!!<br>");
                        }else{
                            W("&check; Se creo la carpeta ..!!! <br>");
                        }
                    }
                    $form = c_form_adp( '', $vConex, "FImportarFormulario", "CuadroA1FR", $path, $uRLForm, $Codigo, $tSelectD, 'Codigo' );
                    $s= "{$btn_titulo} <div style='width: 300px;' >{$form}</div>";
            */
            WE($s);
            break;
        case "Exportar-Seleccion-Form":
                $Base_Datos = post("Nombre");
                $ConexionBExt = conexSis_Emp($Base_Datos);

                $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]" . $enlace . "?Formularios=Importar-Seleccion&Base_Datos=" . $Base_Datos . "]layoutV}";
                $btn .= "Exportar]" . $enlace . "?ImportarExportar=ExportarFrom&Base_Datos=" . $Base_Datos . "]layoutV]CHECK}";
                $btn = Botones($btn, 'botones1', 'sys_form');
                $titulo = "<span>Exportar Formulario </span><p>BD " . $Base_Datos . "</p><div class='bicel'></div>";
                $btn_titulo = tituloBtnPn($titulo, $btn, "170px", "TituloA");

                $sql = 'SELECT Codigo AS Formulario
                        , Tabla
                        ,  DATE_FORMAT(FechaHoraCreacion, "%d %M %Y") AS FechaCreacion
                        , Codigo AS CodigoAjax
                        FROM sys_form
                        ORDER BY  FechaHoraCreacion DESC
                        ';
                $clase = 'reporteA';
                $enlaceCod = 'codigoForm';
                $url = $enlace . "?Formularios=Importar-Seleccion-UPForm&Base_Datos=" . $Base_Datos . "";
                $panel = 'layoutV';

                $reporte = ListR2("", $sql, $ConexionBExt, $clase, '', $url, $enlaceCod, $panel, 'sys_form', 'checks', '');
                $reporte = "<div id = 'PanelInferior' style='float:left;width:100%;' >" . $reporte . "</div>";
                WE($btn_titulo . $reporte);

            break;
        case "ExportarFrom":

                $ky = post('ky');
                print_r($ky);

           # $NroForm = count($ky);
            foreach ($ky as $obj) {



               }


            WE('');

            $Sql="SELECT SF.Codigo,SF.Descripcion,SF.DescripcionExtendida,SF.Tabla,SF.Estado,SF.FechaHoraCreacion,
                  SFD.NombreCampo,SFD.Alias,SFD.TipoInput,SFD.TipoOuput,SFD.TamanoCampo,SFD.Form,SFD.Visible,SFD.TablaReferencia,SFD.OpcionesValue,
                  SFD.MaximoPeso,SFD.AliasB,SFD.CtdaCartCorrelativo,SFD.CadenaCorrelativo,SFD.Validacion,SFD.InsertP,
                  SFD.UpdateP,SFD.Correlativo,SFD.Posicion,SFD.AutoIncrementador,SFD.read_only,SFD.TipoValor,SFD.PlaceHolder,
                  SFD.Edicion,SFD.event_hidden_field,SFD.destiny_upload
                  FROM sys_form SF
                  INNER JOIN sys_form_det AS SFD ON SF.Codigo = SFD.Form
                  WHERE SF.Codigo = 'FUsuarioEntidad'";

            ExportExcel($Sql,$vConex);
            WE("");

            break;
    }
}

function Tablas($Arg) {

    global $vConex, $enlace;

    if ($Arg == "SubirTablas") {

        $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]" . $enlace . "?accionCT=tablas]cuerpo}";
        $btn = Botones($btn, 'botones1', '');
        $titulo = "<span>Subir Tablas </span><p>CONECTATE A UNA BASE DE DATOS</p><div class='bicel'></div>";
        $btn_titulo = tituloBtnPn($titulo, $btn, "80px", "TituloA");
        $uRLForm = "Siguiente]" . $enlace . "?Tablas=SubirTablas-DBase]PanelInferior]F]}";
        $tSelectD = array('Nombre' => 'SELECT Nombre, Nombre  FROM sys_base_datos  ');
        $form = c_form("", $vConex, "select_bdatos", "CuadroA", "", $uRLForm, $CodDet, $tSelectD);
        $s = "<div id = 'PanelInferior' style='float:left;width:50%;' >" . $form . "</div>";
        WE($btn_titulo . $s);
    }

    if ($Arg == "SubirTablas-DBase") {

        $Base_Datos = post("Nombre");
        $ConexionBExt = conexSis_Emp($Base_Datos);

        $result = mysql_list_tables($Base_Datos);
        $s = "<form method='post' name='Form_sys_form1' id='Form_sys_form1' class='CuadroA' action='javascript:void(null);' enctype='multipart/form-data'>";
        while ($row = mysql_fetch_row($result)) {
            $s .= "<div style='width:100%;float:left;'>";
            $s .= "<span>" . $row[0] . "</span>";
            $s .= "<input type='checkbox' name='ky[]' value='" . $row[0] . "' >";
            $s .= "</div>";
        }

        $s .= "<div class='Botonera'>";
        $viewdata = array();
        $viewdata['sUrl'] = $enlace . "?Tablas=Process-Tabla&Nombre=" . $Base_Datos;
        $viewdata['formid'] = "Form_sys_form1";
        $viewdata['sDivCon'] = "cuerpo";
        $viewdata['sIdCierra'] = "";
        $s .= "<button onclick=enviaFormS('" . json_encode($viewdata) . "'); class='" . $atributoBoton[5] . "'  >";
        $s .= "Actualizar";
        $s .= "</button>";
        $s .= "</div>";
        $s .= "</form>";
        WE($s);
    }

    if ($Arg == "Process-Tabla") {

        $Base_Datos = get("Nombre");
        $Tablas = post("ky");
        $ConexionBExt = conexSis_Emp($Base_Datos);

        for ($j = 0; $j < count($Tablas); $j++) {

            $_sql = 'SELECT * FROM ' . $Tablas[$j];
            $consulta = mysql_query($_sql, $ConexionBExt);
            $resultado = $consulta or die(mysql_error());
            $datos = array();

            for ($i = 0; $i < mysql_num_fields($consulta); ++$i) {

                $campo = mysql_field_name($consulta, $i);
                $type = mysql_field_type($consulta, $i);
                $size = mysql_field_len($consulta, $i);
                if ($type == 'string') {
                    $type = 'varchar';
                }
                $datos[$i] = array('Campo' => $campo, 'Tipo' => $type, 'Tamano' => $size);
                $conta++;
            }

            $sql = 'SELECT Codigo,Descripcion FROM sys_tabla WHERE  Codigo = "' . $Tablas[$j] . '" ';
            $rg = fetch($sql);
            $codigo = $rg["Codigo"];

            if ($codigo == "") {

                $sql = 'INSERT  INTO sys_tabla(Codigo,Descripcion,Estado) VALUES ("' . $Tablas[$j] . '","' . $Tablas[$j] . '","Activo")';
                W(xSQL($sql, $vConex) . "<br>");
                for ($k = 0; $k < $conta; ++$k) {
                    $cod_sys_tabla_det = numerador("sys_tabla_det", 1, "");
                    $_sql2 = 'INSERT  INTO sys_tabla_det (Codigo,Descripcion,TipoCampo,sys_tabla,Size) VALUES (' . $cod_sys_tabla_det . ',"' . $datos[$k]['Campo'] . '","' . $datos[$k]['Tipo'] . '","' . $Tablas[$j] . '","' . $datos[$k]['Tamano'] . '")';
                    xSQL($_sql2, $vConex);
                }
                W("La tabla " . $codigo . " Fue Insertada <br>");
            } else {
                W("La tabla " . $codigo . " ya existe <br>");
            }
        }
    }

    if ($Arg == "SubirTablas-DBase") {

        $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]" . $enlace . "?accionCT=tablas]cuerpo}";
        $btn = Botones($btn, 'botones1', '');
        $titulo = "<span>Importar Tablas </span><p>CONECTATE A UNA BASE DE DATOS</p><div class='bicel'></div>";
        $btn_titulo = tituloBtnPn($titulo, $btn, "80px", "TituloA");
        $uRLForm = "Siguiente]" . $enlace . "?Tablas=Importar-Seleccion-Tab]cuerpo]F]}";
        $tSelectD = array('Nombre' => 'SELECT Nombre, Nombre  FROM sys_base_datos  ');
        $form = c_form("", $vConex, "select_bdatos", "CuadroA", "", $uRLForm, $CodDet, $tSelectD);

        $s = "<div id = 'PanelInferior' style='float:left;width:50%;' >" . $form . "</div>";

        WE($btn_titulo . $s);
    }

    if ($Arg == "Importar-Seleccion") {

        $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]" . $enlace . "?accionCT=tablas]cuerpo}";
        $btn = Botones($btn, 'botones1', '');
        $titulo = "<span>Importar Tablas </span><p>CONECTATE A UNA BASE DE DATOS</p><div class='bicel'></div>";
        $btn_titulo = tituloBtnPn($titulo, $btn, "80px", "TituloA");
        $uRLForm = "Siguiente]" . $enlace . "?Tablas=Importar-Seleccion-Tab]cuerpo]F]}";
        $uRLForm .= "Importar Archivo]" . $enlace . "?Tablas=Importar-Archivo-Tab]cuerpo]F]}";
        $tSelectD = array('Nombre' => 'SELECT Nombre, Nombre  FROM sys_base_datos  ');
        $form = c_form("", $vConex, "select_bdatos", "CuadroA", "", $uRLForm, $CodDet, $tSelectD);

        $s = "<div id = 'PanelInferior' style='float:left;width:50%;' >" . $form . "</div>";

        WE($btn_titulo . $s);
    }

    if ($Arg == "Importar-Seleccion-Tab") {

        $Base_Datos = post("Nombre");
        $ConexionBExt = conexSis_Emp($Base_Datos);

        $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]" . $enlace . "?Tablas=Importar-Seleccion&Base_Datos=" . $Base_Datos . "]cuerpo}";
        $btn .= "Importar]" . $enlace . "?Tablas=Importar-Seleccion-UPTab&Base_Datos=" . $Base_Datos . "]cuerpo]CHECK}";
        $btn .= "Importar con datos]" . $enlace . "?Tablas=Importar-Cdata&Base_Datos=" . $Base_Datos . "]cuerpo]CHECK}";
        $btn = Botones($btn, 'botones1', 'sys_tabla');
        $titulo = "<span>Importar Tablas </span><p>BD " . $Base_Datos . "</p><div class='bicel'></div>";
        $btn_titulo = tituloBtnPn($titulo, $btn, "380px", "TituloA");

        $sql = 'SELECT
		Codigo AS Tabla
		,Codigo AS CodigoAjax
		,DATE_FORMAT(FechaRegistro,"%d %M %Y") AS FechaCreacion
		FROM sys_tabla  ORDER BY FechaRegistro DESC  ';
        $clase = 'reporteA';
        $enlaceCod = 'codigoForm';
        $url = $enlace . "?Tablas=Importar-Seleccion-UPTab&Base_Datos=" . $Base_Datos . "";
        $panel = 'cuerpo';

        $reporte = ListR2("", $sql, $ConexionBExt, $clase, '', $url, $enlaceCod, $panel, 'sys_tabla', 'checks', '');
        $reporte = "<div id = 'cuerpo' style='float:left;width:50%;' >" . $reporte . "</div>";
        WE($btn_titulo . $reporte);
    }


    if ($Arg == "Importar-Seleccion-UPTab") {

        $Base_Datos = get("Base_Datos");
        $campos = post("ky");

        for ($j = 0; $j < count($campos); $j++) {

            $sql = 'SELECT Codigo FROM sys_tabla WHERE  Codigo = "' . $campos[$j] . '" ';
            $rg = fetch($sql);
            $tabla = $rg["Codigo"];

            if (empty($tabla)) {

                $ConexionBExt = conexSis_Emp($Base_Datos);
                $sql = 'CREATE TABLE owlgroup_owl.' . $campos[$j] . ' LIKE ' . $Base_Datos . '.' . $campos[$j] . ' ';
                $rg = fetch($sql);

                $ConexionBExt = conexSis_Emp($Base_Datos);
                $condiciones[0] = " Codigo = '" . $campos[$j] . "' ";

                $vSQLA = GeneraScriptGen($ConexionBExt, "sys_tabla", $condiciones, "", "");
                $vConexR = conexSis_Emp("owlgroup_owl");
                xSQL($vSQLA, $vConexR);

                $ConexionBExt = conexSis_Emp($Base_Datos);
                $sql = 'SELECT * FROM sys_tabla_det WHERE  sys_tabla = "' . $campos[$j] . '" ';
                $consulta = Matris_Datos($sql, $ConexionBExt);
                $cont = 0;


                while ($reg = mysql_fetch_array($consulta)) {

                    $vConexB = conexSis_Emp("owlgroup_owl");
                    $Codigo_Correlativo = numeradorB("sys_tabla_det", 10, '', $vConexB);

                    $ConexionBExt = conexSis_Emp($Base_Datos);
                    $condiciones[0] = " Codigo='" . $reg["Codigo"] . "' ";
                    $vSQLC = GeneraScriptGen($ConexionBExt, "sys_tabla_det", $condiciones, $Codigo_Correlativo, "");
                    echo '<pre>';
                    print_r($vSQLC);
                    echo '</pre>';
                    $vConexR = conexSis_Emp("owlgroup_owl");
                    xSQL($vSQLC, $vConexR);
                }
            } else {
                ModTabla($Base_Datos, $tabla);
            }
        }
        WE("");
    }

    if ($Arg == "Importar-Cdata") {

        $Base_Datos = get("Base_Datos");
        $campos = post("ky");

        for ($j = 0; $j < count($campos); $j++) {

            $sql = 'SELECT Codigo FROM sys_tabla WHERE  Codigo = "' . $campos[$j] . '" ';
            $rg = fetch($sql);
            $tabla = $rg["Codigo"];

            if (empty($tabla)) {
                WE("La Tabla no estÃ¡ creada");
            } else {

                $sql = 'DELETE FROM ' . $campos[$j] . '';
                xSQL($sql, $vConex);

                $ConexionBExt = conexSis_Emp($Base_Datos);
                $sql = 'INSERT INTO owlgroup_owl.' . $campos[$j] . ' SELECT * FROM ' . $Base_Datos . '.' . $campos[$j] . ' ';
                xSQL($sql, $ConexionBExt);
                WE("Datos Migrados");
            }
        }
        WE("");
    }
}

function ModTabla($Base_Datos, $tabla) {
    global $vConex, $enlace;

    $ConexionBExt = conexSis_Emp($Base_Datos);
    $resultado = mysql_query('SELECT * FROM ' . $tabla . ' limit 1', $ConexionBExt);

    $cnt = mysql_num_fields($resultado);
    $nuev = array();
    $tnuev = array();

    for ($i = 0; $i < $cnt; $i++) {
        $campo = mysql_field_name($resultado, $i);
        $nuev[] = $campo;
        $type = mysql_field_type($resultado, $i);
        $size = mysql_field_len($resultado, $i);
        if ($type == 'string') {
            $type = 'varchar';
        }
        if ($type == 'real') {
            $type = 'decimal';
        }
        $tnuev[$campo] = array('Tipo' => $type, 'Tamano' => $size);
    }


    $resultado = mysql_query('SELECT * FROM ' . $tabla . ' limit 1', $vConex);
    $cnt = mysql_num_fields($resultado);
    $ant = array();
    $tant = array();


    for ($i = 0; $i < $cnt; $i++) {
        $campo = mysql_field_name($resultado, $i);
        $ant[] = $campo;
        $type = mysql_field_type($resultado, $i);
        $size = mysql_field_len($resultado, $i);
        if ($type == 'string') {
            $type = 'varchar';
        }
        if ($type == 'real') {
            $type = 'decimal';
        }
        $tant[$campo] = array('Tipo' => $type, 'Tamano' => $size);
    }


    $res = array_diff($nuev, $ant);
    $resde = array_diff($ant, $nuev);



    foreach ($resde as $fieldname) {
        $field = $tnuev[$fieldname];
        $sql = "ALTER TABLE $tabla DROP $fieldname ";
        xSQL($sql, $vConex);
    }

    foreach ($res as $fieldname) {

        $field = $tnuev[$fieldname];
        if ($field['Tipo'] == 'datetime' || $field['Tipo'] == 'date' || $field['Tipo'] == 'time') {
            $sql = "ALTER TABLE $tabla ADD $fieldname {$field['Tipo']} NOT NULL ";
            xSQL($sql, $vConex);
            //print_r($sql);
            //W($field['Tipo']." DATE</br>");
        //
        } else {

            $sql = "ALTER TABLE $tabla ADD $fieldname {$field['Tipo']} ({$field['Tamano']}) NOT NULL ";
            xSQL($sql, $vConex);
        }
    }

    W("ACTUALIZADO 1");

    Act_det($Base_Datos, $tabla);
}

function Act_det($Base_Datos, $tabla) {

    global $vConex, $enlace;
    $sql = "DELETE FROM sys_tabla_det WHERE sys_tabla = '" . $tabla . "'  ";
    xSQL($sql, $vConex);



    $ConexionBExt = conexSis_Emp($Base_Datos);
    $resultado = mysql_query('SELECT * FROM sys_tabla_det WHERE sys_tabla = "' . $tabla . '"', $ConexionBExt);
    $cnt = mysql_num_fields($resultado);
    $ant = array();

    for ($i = 0; $i < $cnt; $i++) {
        $dat = mysql_fetch_array($resultado);
        $ant[] = $dat;
    }


    foreach ($ant as $fieldname) {


        $nuev = insertCorrelativo(array('name' => 'sys_tabla_det', 'alias' => 'sys_tabla_det'), array(
            'Descripcion' => $fieldname["Descripcion"],
            'TipoCampo' => $fieldname["TipoCampo"],
            'sys_tabla' => $fieldname["sys_tabla"],
            'Size' => $fieldname["Size"],
                ), array(
            'name' => 'Codigo',
            'prefijo' => '',
                ), $vConex);
    }

    WE("ACTUALIZADO F");
}

function getPestanasHtml($basepath, $active = 'formulario') {

    $formularios = $tablas = $datos = $datosmaestros = $selecciondb = '';
    switch ($active) {
        case 'seleccion-db':
            $selecciondb = ']Marca';
            break;
        case 'tablas':
            $tablas = ']Marca';
            break;
        case 'datosalternos':
            $datos = ']Marca';
            break;
        case 'datosmaestros':
            $datosmaestros = ']Marca';
            break;
        case 'ProcesoSistema':
            $p_sistema = ']Marca';
            break;
        case 'Reportes':
            $p_reportes = ']Marca';
            break;
        default:
            $formularios = ']Marca';
            break;
    }
    $menu = 'Formularios]' . $basepath . ']cuerpo' . $formularios . '}';
    $menu .= 'Tablas]' . $basepath . '?accionCT=tablas]cuerpo' . $tablas . '}';
    $menu .= 'Importar Datos]' . $basepath . '?action=seleccion-db]cuerpo' . $selecciondb . '}';
    $menu .= 'Datos Alternos]' . $basepath . '?accionDA=DAlternos]cuerpo' . $datos . '}';
    $menu .= 'Datos Maestros]' . $basepath . '?accionDA=DMaestros]cuerpo' . $datosmaestros . '}';
    $menu .= 'Procesos De Sistema]' . $basepath . '?ProcesosSistema=Menu]cuerpo' . $p_sistema . '}';
    $menu .= 'Reportes ]' . $basepath . '?Reportes=Menu]cuerpo' . $p_reportes . '}';
    $pestanas = menuHorizontal($menu, 'menuV1');

    return $pestanas;
}

function BuscarFormulario() {

    global $vConex, $enlace;

    W("yyyyyyy");
    $btn = "<div class='botIconS'><i class='icon-trash'></i></div>]" . $enlace . "?Formularios=EliminarFormularios]PanelInferior]CHECK}";
    $btn .= "<div class='botIconS'><i class='icon-refresh'></i></div>]" . $enlace . "]cuerpo}";
    $btn .= "<div class='botIconS'><i class='icon-edit'></i></div> ]Abrir]panel-Float}";
    $btn .= "<div class='botIconS'><i class='icon-search'></i></div>]Abrir]panel-FloatB}";
    $btn .= "<div class='botIconS'><i class='icon-signin'></i></div>]" . $enlace . "?Formularios=Importar-Seleccion]layoutV}";
    $btn = Botones($btn, 'botones1', 'sys_form');

    $titulo = "<span>Lista</span><p>FORMULARIOS DEL SISTEMA</p><div class='bicel'></div>";
    $btn_titulo = tituloBtnPn($titulo, $btn, "300px", "TituloA");

    $path = "";
    $uRLForm = "Guardar]" . $enlace . "?metodo=SysFomr1&transaccion=INSERT]panelB-R]F]panel-Float}";
    $titulo = "CREAR FORMULARIO";
    $form = c_form($titulo, $vConex, "SysFomr1", "CuadroA", $path, $uRLForm, '', '');
    $form = "<div style='width:500px;'>" . $form . "</div>";
    $style = "left:170px;top:0px;";
    $divFloat = panelFloat($form, "panel-Float", $style);
    $divFloat = panelFloat($form, "panel-Float", $style);

    $path = "";
    $uRLForm = "Buscar]" . $enlace . "?BuscarFormulario=Yess]layoutV]F]panel-Float}";
    $titulo = "BUSCAR FORMULARIO";
    $form = c_form($titulo, $vConex, "SysFomr1", "CuadroA", $path, $uRLForm, '', '');
    $form = "<div style='width:500px;'>" . $form . "</div>";
    $style = "left:170px;top:0px;";
    $divFloatB = panelFloat($form, "panel-FloatB", $style);

    $panelA = $divFloat . $btn_titulo . pAnimado1($reporte);

    $sql = 'SELECT ';
    $sql .= ' Codigo AS Formulario ';
    $sql .= ' , Tabla ';
    $sql .= ' ,  DATE_FORMAT(FechaHoraCreacion, "%d %M %Y")  AS FechaCreacion';
    $sql .= ' , Codigo AS CodigoAjax ';
    $sql .= ' FROM sys_form ';
    $sql .= ' WHERE Codigo like "%' . post("Codigo") . '%"  ';
    $sql .= ' ORDER BY  FechaHoraCreacion DESC ';
    // WE($sql);
    $clase = 'reporteA';
    $enlaceCod = 'codigoForm';
    $url = $enlace . "?muestra=detalle";
    $panel = 'layoutV';
    $reporte = ListR2('', $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_form', 'checks', '');
    $reporte = "<div id = 'PanelInferior' style='float:left;width:100%;' >" . $reporte . "</div>";

    $panelA = layoutV2($divFloat . $divFloatB . $pestanas, $btn_titulo . $reporte);

    WE($panelA);
}

function site() {
    global $vConex, $enlace;
    
    $pestanas = getPestanasHtml($enlace);

    $btn = "<div class='botIconS'><i class='icon-trash'></i></div>]" . $enlace . "?Formularios=EliminarFormularios]PanelInferior]CHECK}";
    $btn .= "<div class='botIconS'><i class='icon-refresh'></i></div>]" . $enlace . "]cuerpo}";
    $btn .= "<div class='botIconS'><i class='icon-edit'></i></div> ]Abrir]panel-Float}";
    $btn .= "<div class='botIconS'><i class='icon-search'></i></div>]Abrir]panel-FloatB}";
    $btn .= "<div class='botIconS'><i class='icon-signin'></i></div>]" . $enlace . "?Formularios=Importar-Seleccion]layoutV}";
    $btn = Botones($btn, 'botones1', 'sys_form');

    $titulo = "<span>Lista</span><p>FORMULARIOS DEL SISTEMA</p><div class='bicel'></div>";
    $btn_titulo = tituloBtnPn($titulo, $btn, "300px", "TituloA");
//_______---
    $path = "";
    #$uRLForm = "Guardar]" . $enlace . "?metodo=SysFomr1&transaccion=INSERT]panelB-R]F]panel-Float]}";
    $uRLForm = "Guardar]" . $enlace . "?metodo=SysFomr1&transaccion=INSERT]cuerpo]F]panel-Float]}";
    $titulo = "CREAR FORMULARIO eee";
    $form = c_form($titulo, $vConex, "SysFomr1", "CuadroA", $path, $uRLForm, '', '');
    $form = "<div style='width:500px;'>" . $form . "</div>";
    $style = "left:170px;top:0px;";
    $divFloat = panelFloat($form, "panel-Float", $style);

    $path = "";
    $uRLForm = "Buscar]" . $enlace . "?BuscarFormulario=Yess]layoutV]F]panel-Float}";
    $titulo = "BUSCAR FORMULARIO";
    $form = c_form($titulo, $vConex, "Form_Busqueda_form", "CuadroA", $path, $uRLForm, '', '');
    $form = "<div style='width:500px;'>" . $form . "</div>";
    $style = "left:170px;top:0px;";
    $divFloatB = panelFloat($form, "panel-FloatB", $style);

    $panelA = $divFloat . $btn_titulo . pAnimado1($reporte);

    $sql = 'SELECT ';
    $sql .= ' Codigo AS Formulario ';
    $sql .= ' , Tabla ';
    $sql .= ' ,  DATE_FORMAT(FechaHoraCreacion, "%d %M %Y")  AS FechaCreacion';
    $sql .= ' , Codigo AS CodigoAjax ';
    $sql .= ' FROM sys_form ';
    $sql .= ' ORDER BY  FechaHoraCreacion DESC ';
    $clase = 'reporteA';
    $enlaceCod = 'codigoForm';
    $url = $enlace . "?muestra=detalle";
    $panel = 'layoutV';
    $reporte = ListR2('', $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_form', 'checks', '');
    $reporte = "<div id = 'PanelInferior' style='float:left;width:100%;' >" . $reporte . "</div>";

    $panelA = layoutV2($divFloat . $divFloatB . $pestanas, $btn_titulo . $reporte);

    $panel = array(array('PanelA1', '90%', $panelA),
        array('PanelB1', '10%', '')
    );
    $s = LayoutPage($panel);

    return $s;
}

function pAnimado1($cont) {
    $s = "<div class='PanelAnimado-001' >";
    $s = $s . "<div class='PanelAnimado-001-animate' style='width:100%;'>";
    $s = $s . $cont;
    $s = $s . "</div>";
    $s = $s . "</div>";
    return $s;
}

function selectionDb() {
    global $vConex, $enlace;
    $pestanas = getPestanasHtml($enlace, 'seleccion-db');
    $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]" . $enlace . "?action=tablas]cuerpo}";
    $btn = Botones($btn, 'botones1', '');
    $titulo = "<span>Importar Tablas </span><p>CONECTATE A UNA BASE DE DATOS</p><div class='bicel'></div>";
    $btn_titulo = tituloBtnPn($titulo, '', "80px", "TituloA");
    $uRLForm = "Siguiente]" . $enlace . "?action=listado-tablas]cuerpo]F]}";
    $tSelectD = array('Nombre' => 'SELECT Nombre, Nombre  FROM sys_base_datos  ');
    $form = c_form("", $vConex, "select_bdatos", "CuadroA", "", $uRLForm, $CodDet, $tSelectD);

    $s = "<div id = 'PanelInferior' style='float:left;width:50%;' >" . $form . "</div>";

    WE($pestanas . $btn_titulo . $s);
}

function listadoTablas($dbname = '', $msg = '') {

    global $vConex, $enlace;

    $pestanas = getPestanasHtml($enlace, 'seleccion-db');

    if (empty($dbname)) {
        $Base_Datos = post('Nombre');
    } else {
        $Base_Datos = $dbname;
    }

    $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]" . $enlace . "?action=seleccion-db&Base_Datos=" . $Base_Datos . "]cuerpo}";
    $btn .= "Actualizar Tabla]" . $enlace . "?action=actualizar-tabla&dbname=" . $Base_Datos . "]cuerpo]CHECK}";

    $btn = Botones($btn, 'botones1', 'sys_tabla_modelo');
    $titulo = "<span>Importar Tablas </span><p>" . $Base_Datos . "</p><div class='bicel'></div>";
    $btn_titulo = tituloBtnPn($titulo, $btn, "380px", "TituloA");

    $sql = "(
	SELECT
		tab1.Codigo AS Tabla,
		IF( ISNULL(tab1.Codigo), '', CONCAT(
			'<input type=\"radio\" value=\"',tab1.Codigo,'\" name=\"ky1\">'
		) ) AS 'Check Modelo',
		tab2.Codigo AS Tabla2,
		IF( ISNULL(tab2.Codigo), '', CONCAT(
			'<input type=\"radio\" value=\"',tab1.Codigo,'\" name=\"ky2\">'
		) ) AS 'Check Destino'
	FROM
		owlgroup_owl.sys_tabla tab1
	LEFT JOIN $Base_Datos.sys_tabla tab2 ON tab1.Codigo = tab2.Codigo
        )
        UNION ALL
                (
		SELECT
			tab1.Codigo AS Tabla,
			IF( ISNULL(tab1.Codigo), '', CONCAT(
			'<input type=\"radio\" value=\"',tab1.Codigo,'\" name=\"ky1\">'
			) ) AS 'Check Modelo',
			tab2.Codigo AS Tabla2,
			IF( ISNULL(tab2.Codigo), '', CONCAT(
			'<input type=\"radio\" value=\"',tab1.Codigo,'\" name=\"ky2\">'
		) ) AS 'Check Destino'
		FROM
			owlgroup_owl.sys_tabla tab1
		RIGHT JOIN $Base_Datos.sys_tabla tab2 ON tab1.Codigo = tab2.Codigo
	)";

    $clase = 'reporteA';
    $enlaceCod = 'codigoForm';
    $url = $enlace . "?Tablas=Importar-Seleccion-UPTab&Base_Datos=" . $Base_Datos . "";
    $panel = 'cuerpo';

    $reporte = ListR2('', $sql, $vConex, $clase, '', $url, $enlaceCod, $panel, 'sys_tabla_modelo', 'form', '');
    $mensaje = !empty($msg) ? Msg($msg, 'C') : '';
    $html = '<div id = "cuerpo" style="float:left;width:50%;" >' . $mensaje . $reporte . '</div>';
    WE($pestanas . $btn_titulo . $html);
}

function showColums($tabla, $dbname = '') {
    $tabla = (string) $tabla;
    $from = empty($dbname) ? $tabla : $dbname . '.' . $tabla;
    $sql = 'SHOW COLUMNS FROM ' . $from;
    return fetchAll($sql);
}

function filterFiels($fields) {
    $return = array();
    foreach ($fields as $field) {
        $return[$field->Field] = $field;
    }
    return $return;
}

function actualizarTabla() {

    global $vConex, $enlace;
    $dbname = get('dbname');
    $tablaModelo = post('ky1');
    $tablaOrigen = post('ky2');

    $fieldsData1 = showColums($tablaModelo);
    $fieldsData2 = showColums($tablaModelo, $dbname);
    $fields1 = filterFiels($fieldsData1);
    $fields2 = filterFiels($fieldsData2);

    $fieldsAdd = array_diff_key($fields1, $fields2);
    $fieldsDel = array_diff_key($fields2, $fields1);

    $agregados = $eliminados = '';

    foreach ($fieldsAdd as $field) {
        $sql = "ALTER TABLE `$dbname`.`$tablaOrigen` ADD `$field->Field` $field->Type NOT NULL";
        $agregados .= query($sql) ? '<p>' . $field->Field . ' => TRUE</p>' : '<p>' . $field->Field . ' => FALSE</p>';
    }

    foreach ($fieldsDel as $field) {
        $sql = "ALTER TABLE `$dbname`.`$tablaOrigen` DROP `$field->Field` ";
        $eliminados .= query($sql) ? '<p>' . $field->Field . ' => TRUE</p>' : '<p>' . $field->Field . ' => FALSE</p>';
    }

    $sqlTruncate = "TRUNCATE TABLE `$tablaModelo`";
    query($sqlTruncate);
    $sqlInto = "INSERT INTO `$tablaModelo` SELECT * FROM `$dbname`.`$tablaOrigen`";
    query($sqlInto);

    listadoTablas($dbname, "Se importaron los datos de `$dbname`.`$tablaOrigen` correctamente");
}

function getSubTemas() {

    $sql = 'SELECT rs.* FROM (
                            SELECT
                                    st.SubTemaCod, st.Descripcion, st.TituloArticulo,
                                    st.NombreArchivo, st.Formato, st.TipoSubtema,
                                    st.TipoTema, st.ContenidoArticulo, st.Entidad,
                      Ue.Codigo, ue.Usuario, ue.EntidadCreadora,
                      Us.Nombres AS EmpresaNombre, u.Carpeta, u.Nombres AS username, st.Tema, t.Curso
                            FROM    subtema st
                            INNER JOIN tema t ON t.CodTema = st.Tema
                            INNER JOIN usuarios u ON st.Entidad = u.IdUsuario
                            INNER JOIN usuario_entidad ue ON u.Usuario = ue.Usuario
                            INNER JOIN usuarios us ON ue.EntidadCreadora = us.Usuario

                            WHERE
                                    st.Componente IN (0, \'\')
                            AND st.TipoTema IN ( \'Documento\', \'Video\', \'Embebido\' )
            ) AS rs
            LEFT JOIN archivocontenido ac ON rs.NombreArchivo = ac.Archivo
            WHERE
             ac.Archivo IS NULL';

    return fetchAll($sql);
}

function getTipoProducto($tipoTema, $tipoSubtema) {

    $tipos = array(
        'Documento' => 'ArchivoTexto',
        'Embebido' => array('Documento' => 'DocEmb', 'Video' => 'VideoEmb'),
        'Video' => 'Video',
    );

    $return = '';
    if (array_key_exists($tipoTema, $tipos)) {
        if (is_array($tipos[$tipoTema])) {
            $return = array_key_exists($tipoSubtema, $tipos[$tipoTema]) ? $tipos[$tipoTema][$tipoSubtema] : '';
        } else {
            $return = $tipos[$tipoTema];
        }
    }
    return $return;
}

function validFormato($formato) {
    $formato = (string) $formato;
    $tiposFormatos = array('txt', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf', 'zip', 'rar', 'mp4', 'avi', 'wmv');
    return in_array($formato, $tiposFormatos) ? $subtema->Formato : '';
}

function insertArchivoContenido(stdClass $subtema) {

    $return = array('success' => false, 'lastInsertId' => 0);

    if (!empty($subtema)) {
        $TipoProducto = getTipoProducto($subtema->TipoTema, $subtema->TipoSubtema);
        $dataInsert = array(
            'Nombre' => $subtema->TituloArticulo,
            'TipoProducto' => $TipoProducto,
            'Categoria' => 1,
            'Formato' => validFormato($subtema->Formato),
            'Archivo' => $subtema->NombreArchivo,
            'Embebido' => $subtema->ContenidoArticulo,
            'Entidad' => $subtema->EntidadCreadora,
            'Usuario' => $subtema->Codigo,
            'FechReg' => date('Y-m-d H:i:s')
        );

        if ($subtema->TipoTema == 'Documento' || $subtema->TipoTema == 'Video') {
            $file = copyFile($subtema);
            if ($file->success) {
                $return = insert('archivocontenido', $dataInsert);
                $dataInsert['EmpresaNombre'] = $subtema->EmpresaNombre;
                $dataInsert['UsuarioNombre'] = $subtema->username;
                $dataInsert['userEmail'] = $subtema->Usuario;
                $dataInsert['id'] = $return['lastInsertId'];
                $return['data'] = (object) $dataInsert;
            }
        } elseif ($subtema->TipoTema == 'Embebido') {
            $return = insert('archivocontenido', $dataInsert);
            $dataInsert['EmpresaNombre'] = $subtema->EmpresaNombre;
            $dataInsert['UsuarioNombre'] = $subtema->username;
            $dataInsert['userEmail'] = $subtema->Usuario;
            $dataInsert['id'] = $return['lastInsertId'];
            $return['data'] = (object) $dataInsert;
        }
    }
    return $return;
}

function insertArticulo(stdClass $archivoContenido) {

    $return = array('success' => false, 'lastInsertId' => 0);

    if (!empty($archivoContenido)) {

        $dataInsert = array('Titulo' => $archivoContenido->Nombre,
            'Descripcion' => $archivoContenido->Nombre,
            'Fabricante' => $archivoContenido->UsuarioNombre,
            'Entidad' => $archivoContenido->userEmail . 'Profesor',
            'TipoProducto' => $archivoContenido->TipoProducto,
            'Producto' => 'ARC-' . $archivoContenido->id,
            'ProductoFab' => $archivoContenido->id,
            'TipoOrigen' => 1, 'Precio' => 0, 'Moneda' => '',
            'ConsolidadoBusqueda' => $archivoContenido->Nombre,
            'FechaPublicacion' => $archivoContenido->FechReg,
            'FechReg' => $archivoContenido->FechReg,
            'Empresa' => $archivoContenido->Entidad,
            'Usuario' => $archivoContenido->Usuario,
            'Estado' => 'Publicado');

        $return = insert('articulos', $dataInsert);
    }

    return $return;
}

function insertAlmacen(stdClass $archivoContenido) {

    $return = array('success' => false, 'lastInsertId' => 0);

    if (!empty($archivoContenido)) {

        $dataInsert = array('Origen' => $archivoContenido->userEmail . 'Profesor',
            'Entidad' => $archivoContenido->userEmail . 'Profesor',
            'Producto' => 'ARC-' . $archivoContenido->id,
            'TipoProducto' => $archivoContenido->TipoProducto,
            'Estado' => 'Abierto',
            'FechReg' => $archivoContenido->FechReg,
            'stock' => 1,
            'cantidad' => 1,
            'Descripcion' => '',
            'Coordinador' => 0,
            'SoporteTecnico' => 0,
            'NivelCoordinacion' => '',
            'NivelSoporte' => '',
            'Comunidad' => 0);

        $return = insert('almacen', $dataInsert);
    }

    return $return;
}

function copyFile(stdClass $subTema) {
    $return = new stdClass();
    $return->success = false;
    if (!empty($subTema) && ( $subTema->TipoTema == 'Documento' || $subTema->TipoTema == 'Video' )) {

        $source = BASE_PATH . 'ArchivosProfesor' . DS . $subTema->Carpeta . DS . 'CU-' . $subTema->Curso . DS . $subTema->NombreArchivo;
        $destino = BASE_PATH . 'articulos' . DS . $subTema->NombreArchivo;
        $return->origen = $source;
        $return->destino = $destino;
        if (file_exists($source)) {
            $return->success = copy($source, $destino);
        }
    }
    return $return;
}

function insertDataSubTema(stdClass $subtema) {
    $return = new stdClass();
    $return->success = false;
    $archivoContenido = insertArchivoContenido($subtema);

    if ($archivoContenido['success']) {
        $articulo = insertArticulo($archivoContenido['data']);
        $almacen = insertAlmacen($archivoContenido['data']);
        $return->articuloId = $articulo['lastInsertId'];
        $return->almacenId = $almacen['lastInsertId'];
        update('subtema', array('Componente' => $return->almacenId), array('SubTemaCod' => $subtema->SubTemaCod));
        $return->success = true;
    }
    return $return;
}

function ordenarDatos() {

    global $vConex, $enlace;
    $subtemas = getSubTemas();
    echo count($subtemas);
    $i = 0;
    foreach ($subtemas as $subtema) {
        $i++;
        if ($i > 0 && $i <= 200) {

            $insertDataSubTema = insertDataSubTema($subtema);
            if ($insertDataSubTema->success) {

                $output = "$i.- Articulo: $insertDataSubTema->articuloId - Almacen: $insertDataSubTema->almacenId <br>";
                echo $output;
            } else {
                $output = "$i.- Articulo: (-) - Almacen: (-) <br>";
                echo $output;
            }
        }
    }
}

function Tutorial($arg) {
    global $enlace, $vConex;

    switch ($arg) {
        case "view":
            $btn = "Crear Tutorial]{$enlace}?Tutorial=form]panelB-R}";
            $btn = Botones($btn, 'botones1');
            $subMenu = tituloBtnPn("Listado <p> Administracion de Tutoriales</p>", $btn, "auto", "TituloA");

            $_Q = "
            SELECT Codigo as CodigoAjax,
            description,
            path,
            extension,
            date_create,
            _order,
            status
            FROM tutorial
            ORDER BY _order ASC";

            $clase = "reporteA";
            $enlaceCod = "tutorial_id";
            $url = "{$enlace}?Tutorial=edit";
            $panel = "panelB-R";
            $reporte = ListR2("", $_Q, $vConex, $clase, '', $url, $enlaceCod, $panel, "", "");

            $s = LayoutMHrz($subMenu, $reporte);

            WE($s);
            break;
         case "form":
              $SedeSQL =  "SELECT Codigo,Descripcion FROM  usuario_perfil	";
                $tSelectD = array(
				'Perfil' => $SedeSQL 
                );
			
			//AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA	
            $btn = "<i class='icon-arrow-left'></i> Atrás]{$enlace}?Tutorial=view]panelB-R}";
            $btn = Botones($btn, 'botones1', '');
            $titulo = "<span>Crear</span><p>TUTORIAL</p>";
			//W("1111111111111111");
            $btn_titulo = tituloBtnPn($titulo, $btn, "auto", "TituloA");
            //form
            $uRLForm = "Guardar]{$enlace}?metodo=register_tutorial&transaccion=INSERT]panelB-R]F}";

            $path = array('path' => '../_files/');

            $form = FormR1("", $vConex, "register_tutorial", "CuadroA", $path, $uRLForm,"", $tSelectD, "","");

            WE("<div style='width: 500px;'>{$btn_titulo}{$form}</div>");
            break;
        case "edit":
            $tutorial_id = get("tutorial_id");

            $btn = "<i class='icon-arrow-left'></i> Atrás]{$enlace}?Tutorial=view]panelB-R}";
            $btn = Botones($btn, 'botones1', '');
            $titulo = "<span>Editar</span><p>TUTORIAL</p>";
            $btn_titulo = tituloBtnPn($titulo, $btn, "auto", "TituloA");
            //form
            $uRLForm = "Guardar]{$enlace}?metodo=update_tutorial&transaccion=UPDATE&tutorial_id={$tutorial_id}]panelB-R]F}";
            $uRLForm .= "Eliminar]{$enlace}?Tutorial=delete&tutorial_id={$tutorial_id}]panelB-R]F}";

            $SDinamico = [
                "_order" => "
                SELECT _order AS Codigo,
                _order AS Descripcion
                FROM tutorial
                ORDER BY _order ASC"
            ];

            $form = FormR1("", $vConex, "update_tutorial", "CuadroA", $path, $uRLForm, $tutorial_id, $SDinamico, "Codigo");

            WE("<div style='width: 500px;'>{$btn_titulo}{$form}</div>");
            break;
        case "delete":
            $tutorial_id = get("tutorial_id");

            delete("tutorial", [ "Codigo" => $tutorial_id]
            );

            Tutorial("view");
            break;
    }
}

function Soporte($arg) {
    global $enlace, $vConex;

    switch ($arg) {
        case "view":
            $btn = "Crear Soporte]{$enlace}?Soporte=form]panelB-R}";
            $btn = Botones($btn, 'botones1');
            $subMenu = tituloBtnPn("Listado <p>SOPORTE MASTER</p>", $btn, "auto", "TituloA");

            $_Q = " SELECT Codigo as CodigoAjax,
                    Nombre,
                    Email_1,
                    Email_2,
                    Telefono,
                    Anexo
                    FROM empresa_corporacion";

            $clase = "reporteA";
            $enlaceCod = "Codigo";
            $url = "{$enlace}?Soporte=edit";
            $panel = "panelB-R";
            $reporte = ListR2("", $_Q, $vConex, $clase, '', $url, $enlaceCod, $panel, "", "");
            $s = LayoutMHrz($subMenu, $reporte);

            WE($s);
            break;
        case "form":
            $btn = "<i class='icon-arrow-left'></i> Atrás]{$enlace}?Soporte=view]panelB-R}";
            $btn = Botones($btn, 'botones1', '');
            $titulo = "<span>Crear</span><p>Soporte</p>";
            $btn_titulo = tituloBtnPn($titulo, $btn, "auto", "TituloA");
            $uRLForm = "Guardar]{$enlace}?metodo=empresa_corporacion&transaccion=INSERT]panelB-R]F}";
            $path = array('path' => '../_files/');
            $form = FormR1("", $vConex, "empresa_corporacion", "CuadroA", $path, $uRLForm, "", "");
            WE("<div style='width: 500px;'>{$btn_titulo}{$form}</div>");
            break;
        case "edit":
            $Codigo = get("Codigo");

            $btn = "<i class='icon-arrow-left'></i> Atrás]{$enlace}?Soporte=view]panelB-R}";
            $btn = Botones($btn, 'botones1', '');
            $titulo = "<span>Editar</span><p>Soporte</p>";
            $btn_titulo = tituloBtnPn($titulo, $btn, "auto", "TituloA");
            $uRLForm = "Guardar]{$enlace}?metodo=empresa_corporacion&transaccion=UPDATE&Codigo={$Codigo}]panelB-R]F}";
            $uRLForm .= "Eliminar]{$enlace}?Soporte=delete&Codigo={$Codigo}]panelB-R]F}";
            $form = FormR1("", $vConex, "empresa_corporacion", "CuadroA", $path, $uRLForm, $Codigo, $SDinamico, "Codigo");
            WE("<div style='width: 500px;'>{$btn_titulo}{$form}</div>");
            break;
        case "delete":
            $Codigo = get("Codigo");
            delete("empresa_corporacion", [ "Codigo" => $Codigo] );
            Soporte("view");
            break;
    }
}
////////////////////////////////////////////////////////////////////--------------------------------------------tp incio
function TipoProyectos($arg) {
    global $enlace, $vConex;

    switch ($arg) {
        case "Tipos":
            $btn = "Crear Tipo Proyecto]{$enlace}?TipoProyectos=formProyecto]panelB-R}";
            $btn = Botones($btn, 'botones1');
            $subMenu = tituloBtnPn("Listado <p>Tipos de Proyectos</p>", $btn, "auto", "TituloA");

            $_Q = "SELECT  Codigo   as CodigoAjax,  
									Tipo,	titulo,	subtitulo,	subtituloLogin,	subtituloDetalle,	InternoSubtitulo1,	InternoSubtitulo2,	
									AulaTitulo1,	AulaTitulo2,	tituloSesion,   MenuLateral_Sesion, formCrearTitulo1, formCrearTitulo2, TituloPrincipal,
									TituloEncabezado1,  TituloEncabezado2,DetalleBoton, TituloPrincipal2,TituloMenuPrincipal, GeneralTitulo,
									ConfigTitulo, menuPrincipalInterno, DetalleBoton2, TituloPrincipal3, GeneralTitulo2, ConfigTitulo2,
									DetalleBotonInterno1, DetalleBotonInterno2, DetalleBotonInterno3, TituloFormInterno1, TituloFormInterno2,
									TituloPresentacion, TituloListadoTemas, TituloBuscarContenido, AulaTituloEditar, AulaTituloEditar2,
									TituloEliminar, MensajeMenuLatera1,MensajeMenuLatera2,MensajeMenuLatera3 
				
						FROM 
						            tipo_proyecto  ";

			
            $clase = "reporteA";
            $enlaceCod = "Codigo";
            $url = "{$enlace}?TipoProyectos=editProyectos";
            $panel = "panelB-R";
            $reporte = ListR2("", $_Q, $vConex, $clase, '', $url, $enlaceCod, $panel, "", "");
            $s = LayoutMHrz($subMenu, $reporte);

            WE($s);
            break;
        case "formProyecto":
            $btn = "<i class='icon-arrow-left'></i> Atrás]{$enlace}?TipoProyectos=Tipos]panelB-R}";
            $btn = Botones($btn, 'botones1', '');
            $titulo = "<span>Crear</span><p>Tipo de Proyecto</p>";
            $btn_titulo = tituloBtnPn($titulo, $btn, "auto", "TituloA");
           // $uRLForm = "Guardar]{$enlace}?metodo=empresa_corporacion&transaccion=INSERT]panelB-R]F}";
            $uRLForm = "Guardar]{$enlace}?metodo=empresa_TipoProyecto&transaccion=INSERT]panelB-R]F}";
            $path = array('path' => '../_files/');
            $form = FormR1("", $vConex, "empresa_TipoProyecto", "CuadroA", $path, $uRLForm, "", "");
            WE("<div style='width: 800px;'>{$btn_titulo}{$form}</div>");
            break;
        case "editProyectos":
            $Codigo = get("Codigo");
			
	        $btn = "<i class='icon-arrow-left'></i> Atrás]{$enlace}?TipoProyectos=Tipos]panelB-R}";
            $btn = Botones($btn, 'botones1', '');
            $titulo = "<span>Editar</span><p>Tipo de Proyecto</p>";
            $btn_titulo = tituloBtnPn($titulo, $btn, "auto", "TituloA");
            $uRLForm = "Guardar]{$enlace}?metodo=empresa_TipoProyecto&transaccion=UPDATE&Codigo={$Codigo}]panelB-R]F}";
            $uRLForm .= "Eliminar]{$enlace}?TipoProyectos=delete&Codigo={$Codigo}]panelB-R]F}";
            $form = FormR1("", $vConex, "empresa_TipoProyecto", "CuadroA", $path, $uRLForm, $Codigo, $SDinamico, "Codigo");
            WE("<div style='width: 800px;'>{$btn_titulo}{$form}</div>");
            break;
        case "delete":
            $Codigo = get('Codigo');
			// vd($_GET);
			// vd($_POST);
			
			$SQL="DELETE  FROM  tipo_proyecto  WHERE  Codigo={$Codigo} ";
			xSQL($SQL, $vConex);
            W(" <p style='color : red'>  Se ha eliminado un tipo de Poryecto </h1>");
           // delete("empresa_TipoProyecto", $Codigo);
	         TipoProyectos("Tipos");
            break;
    }
}

/////////////////////////////////////////////////////////////////////////////////////////////////--------------------------tp Fin


function ProcesosTab($Arg){
 global $enlace, $vConex;

    switch ($Arg) {
        case "EliminaTabLog":
		     $tabla = get("Tabla");
			 /////tttttttttttttttt
            DReg("sys_tabla", "Codigo", "'" . $tabla . "'", $vConex);
            DReg("sys_tabla_det", "sys_tabla", "'" . $tabla. "'", $vConex);
            WE("Se EliminÃ³ el formulario " . $campos[$j] . "  <br>");			
			
            break;
       
    }
}

WE(site());
