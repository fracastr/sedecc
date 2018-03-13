<?php

class JsonController extends Controller
{
	public function actionIndex()
	{
		header('Content-type:application/json;charset=utf-8');
		// Descargar evaluaciones
		//echo (DateTime::createFromFormat('Y-m-d', '12-12-2000'))->format('d-m-Y');

		echo DateTime::createFromFormat('d-m-Y', '12-12-2000')->format('Y-m-d');

		echo json_encode('Test');
	}

	public function actionCargo(){
		header('Content-type:application/json;charset=utf-8');
		$model=new Evaluacion('search');
		$global['check_json'] = "json_valido_sedecc";
		$global['campos'] = $model->attributeLabels();
		$global['rules'] = $model->rules();
		//creamos json para mostrar los checklist de cada trabajador por empresa
		$global['categorias'] = Yii::app()->db->createCommand("SELECT DISTINCT car_id FROM min_pregunta WHERE tipo_checklist='trabajadores'")->query()->readAll();

		foreach ($global['categorias'] as $key => $value){
			$global['items'][$value['car_id']] = Yii::app()->db->createCommand("SELECT * FROM min_pregunta WHERE car_id = '".$value['car_id']."' ORDER BY tem_id")->query()->readAll();
		}
		//creamos json para mostrar los checklist de cada equipos por empresa
		$global['equipos'] = Yii::app()->db->createCommand("SELECT DISTINCT car_id FROM min_pregunta WHERE tipo_checklist='equipos'")->query()->readAll();

		foreach ($global['equipos'] as $key => $value){
			$global['items_equipos'][$value['car_id']] = Yii::app()->db->createCommand("SELECT * FROM min_pregunta WHERE car_id = '".$value['car_id']."' ORDER BY pre_id")->query()->readAll();
		}

		//creamos json para mostrar los checklist de cada instalacion por empresa
		$global['instalaciones'] = Yii::app()->db->createCommand("SELECT DISTINCT car_id FROM min_pregunta WHERE tipo_checklist='instalaciones'")->query()->readAll();

		foreach ($global['instalaciones'] as $key => $value){
			$global['items_instalaciones'][$value['car_id']] = Yii::app()->db->createCommand("SELECT * FROM min_pregunta WHERE car_id = '".$value['car_id']."' ORDER BY pre_id")->query()->readAll();
		}
		//obtenemos el rut de la empresa para realizar verificacion de que checklist tiene activo
		$tmp = Yii::app()->db->createCommand("SELECT DISTINCT act_eess FROM min_check_activo WHERE tipo_checklist='trabajadores'")->query()->readAll();
		for($i=0;$i<count($tmp);$i++){
			//obtenemos los distintos checklist por cada EESS
			$activos[$tmp[$i]['act_eess']] = Yii::app()->db->createCommand("SELECT DISTINCT act_car FROM min_check_activo WHERE act_eess = '".$tmp[$i]['act_eess']."'  AND tipo_checklist='trabajadores'")->query()->readAll();
		}
		//obtenemos los nombres de distintos checklist que tenemos en la BD
		$activos[''] = Yii::app()->db->createCommand("SELECT DISTINCT act_car FROM min_check_activo WHERE tipo_checklist='trabajadores'")->query()->readAll();
		$global['activos'] = $activos;

		///////////////////activos equipos////////////
		//obtenemos el rut de la empresa para realizar verificacion de que checklist tiene activo
		$tmp_equipos = Yii::app()->db->createCommand("SELECT DISTINCT act_eess FROM min_check_activo WHERE tipo_checklist='equipos'")->query()->readAll();
		for($j=0;$j<count($tmp_equipos);$j++){
			//obtenemos los distintos checklist por cada EESS
			$activos_equipos[$tmp_equipos[$j]['act_eess']] = Yii::app()->db->createCommand("SELECT DISTINCT act_car FROM min_check_activo WHERE act_eess = '".$tmp_equipos[$j]['act_eess']."'  AND tipo_checklist='equipos'")->query()->readAll();
		}
		//obtenemos los nombres de distintos checklist que tenemos en la BD
		$activos_equipos[''] = Yii::app()->db->createCommand("SELECT DISTINCT act_car FROM min_check_activo WHERE tipo_checklist='equipos'")->query()->readAll();
		$global['activos_equipos'] = $activos_equipos;

		/////////////////////ACTIVOS INSTALACIONES////////////////////
		//obtenemos el rut de la empresa para realizar verificacion de que checklist tiene activo por instalacion
		$tmp_instalaciones = Yii::app()->db->createCommand("SELECT DISTINCT act_eess FROM min_check_activo WHERE tipo_checklist='instalaciones'")->query()->readAll();
		for($k=0;$k<count($tmp_instalaciones);$k++){
			//obtenemos los distintos checklist por cada EESS
			$activos_instalaciones[$tmp_instalaciones[$k]['act_eess']] = Yii::app()->db->createCommand("SELECT DISTINCT act_car FROM min_check_activo WHERE act_eess = '".$tmp_instalaciones[$k]['act_eess']."' AND tipo_checklist='instalaciones'")->query()->readAll();
		}
		//obtenemos los nombres de distintos checklist que tenemos en la BD
		$activos_instalaciones[''] = Yii::app()->db->createCommand("SELECT DISTINCT act_car FROM min_check_activo WHERE tipo_checklist='instalaciones'")->query()->readAll();
		$global['activos_instalaciones'] = $activos_instalaciones;

		//obtenemos los usuarios que se les permite el ingreso a la app
		$global['trabajador'] = Yii::app()->db->createCommand("SELECT eess_rut, tra_rut, tra_evaluador FROM min_trabajador")->query()->readAll();
		$global['formularios'] = Yii::app()->db->createCommand("SELECT correlativo_chk_eess, checklist, eess_rut,n_campos, campo,nombre_campos,campos_values,campos_requeridos FROM min_formularios
ORDER BY correlativo_chk_eess ASC")->query()->readAll();
$global['bitacora_forms'] = Yii::app()->db->createCommand("SELECT eess_rut, bit_nombre, bit_n_campos, bit_campos, bit_nombre_campos, bit_campos_values, bit_campos_requeridos FROM min_formulario_bitacora")->query()->readAll();
		echo json_encode($global);
	}

	public function actionHistorico(){
		// Cargar página desde cache
		header('Content-type:application/json;charset=utf-8');
		if(file_exists('sedecc_historico'))
		//if(time()-filemtime('sedecc_historico') < 1){ // Actualizar siempre
		/*if(time()-filemtime('sedecc_historico') < (1*86400)){ // Actualizar una vez al día
			include('sedecc_historico');
			return;
		}
		*/
		header('Content-type:application/json;charset=utf-8');


		$rows = Yii::app()->db->createCommand("SELECT
			eva_id,
			eva_creado,
			eva_tipo,
			eess_rut,
			tra_rut,
			eva_apellidos,
			eva_nombres,
			eva_fecha_evaluacion,
			eva_comuna,
			eva_fundo,
			eva_faena,
			eva_cache_porcentaje
		FROM min_evaluacion")->query()->readAll();
		for($i=0;$i<count($rows);$i++){
			$rows[$i]['resultado'] = $rows[$i]['eva_cache_porcentaje'];
		}
		$global['rows'] = $rows;
		/**/

		$rows_equipos = Yii::app()->db->createCommand("SELECT eva_id, eva_creado, eva_tipo, eess_rut, eva_fecha_evaluacion, eva_campos_modificados, eva_cache_porcentaje FROM min_evaluacion_equipos")->query()->readAll();
		for($o=0;$o<count($rows_equipos);$o++){
			$rows_equipos[$o]['resultado_equipos'] = $rows_equipos[$o]['eva_cache_porcentaje'];
		}
		$global['rows_equipos'] = $rows_equipos;

		$global['check_json'] = "json_valido_sedecc";
		echo json_encode($global);

		$fp = fopen('sedecc_historico', 'w');
		fwrite($fp, json_encode($global));
		fclose($fp);
	}

	public function actionAutocompletar(){
		// Cargar página desde cache
		header('Content-type:application/json;charset=utf-8');
		if(file_exists('sedecc_autocompletar'))
		if(time()-filemtime('sedecc_autocompletar') < 1){ // Actualizar siempre
		//if(time()-filemtime('sedecc_autocompletar') < (1*86400)){ // Actualizar una vez al día
		//prueba
			include('sedecc_autocompletar');
			return;
		}
		$global['check_json'] = "json_valido_sedecc";
		$global['cargo'] = Yii::app()->db->createCommand("SELECT * FROM min_cargo")->query()->readAll();
		$global['trabajador'] = Yii::app()->db->createCommand("SELECT * FROM min_trabajador")->query()->readAll();
		$global['comuna'] = Yii::app()->db->createCommand("SELECT com_nombre FROM min_comuna")->query()->readAll();
		$global['fundo'] = Yii::app()->db->createCommand("SELECT fun_eess as eess_rut, fun_nombre as eva_fundo FROM min_fundo ORDER BY fun_nombre")->query()->readAll();
		$global['faena'] = Yii::app()->db->createCommand("SELECT eess_rut, fae_nombre as eva_faena FROM min_faena WHERE fae_activo = 1 ORDER BY fae_nombre")->query()->readAll();
		$global['tipo_cosecha'] = Yii::app()->db->createCommand("SELECT eva_tipo_cosecha FROM min_evaluacion GROUP BY eva_tipo_cosecha")->query()->readAll();
		$global['patente'] = Yii::app()->db->createCommand("SELECT DISTINCT(eva_patente) as eva_patente FROM min_evaluacion")->query()->readAll();
		$global['maquinaria'] = Yii::app()->db->createCommand("SELECT eq_maquina,eq_codigo FROM min_equipos")->query()->readAll();
		$global['codigos_faenas'] = Yii::app()->db->createCommand("SELECT cod_faenas as 'eva_cod_faena' FROM min_codigos_faenas")->query()->readAll();
		echo json_encode($global);

		$fp = fopen('sedecc_autocompletar', 'w');
		fwrite($fp, json_encode($global));
		fclose($fp);
	}

	public function actionPendientes(){
		header('Content-type:application/json;charset=utf-8');
		$global["sedecc"] = Yii::app()->db->createCommand("
		SELECT e.tra_rut, p.pre_enunciado, r.res_observacion, r.res_plazo FROM min_respuesta r
			LEFT JOIN min_pregunta p ON (p.pre_id = r.pre_id)
			LEFT JOIN min_evaluacion e ON (e.eva_id = r.eva_id)
		WHERE res_seguimiento = 0")->queryAll();
		$global['check_json'] = "json_valido_sedecc";
		echo json_encode($global);
	}

	public function actionRecep(){
		header('Content-type:application/json;charset=utf-8');

		//$sqlversion = "SELECT version_antigua,version_actual,version_nueva FROM min_control_version WHERE id = 0";
		//$version = Yii::app()->db->createCommand($sqlversion)->query()->readAll();
		$sqlantigua = "SELECT version_antigua FROM min_control_version WHERE id = 0";
		$version_antigua = Yii::app()->db->createCommand($sqlantigua)->queryScalar();
		$sqlactual = "SELECT version_actual FROM min_control_version WHERE id = 0";
		$version_actual = Yii::app()->db->createCommand($sqlactual)->queryScalar();
		$sqlnueva = "SELECT version_nueva FROM min_control_version WHERE id = 0";
		$version_nueva = Yii::app()->db->createCommand($sqlnueva)->queryScalar();

		/*for($v=0;$v<count($version);$v++){
			$version_antigua=$version[$v]['version_antigua'];
			$version_actual=$version[$v]['version_actual'];
			$version_nueva=$version[$v]['version_nueva'];
		}
		*/
		//$nueva = "26";
		/*
		if(isset($_POST['version_control'])){
			$traeversion = $_POST['version_control'];
		}*/
		//$version = $_POST['version_android'];
		if(!isset($_POST['version_control']))
		{
			//version actual
			if(isset($_POST['android']))
			{
				//Parche para variables no definidas, util para actualizaciones
				if(!isset($_POST['android'])) $_POST['android']='';
				if(!isset($_POST['timestamp'])) $_POST['timestamp']='';
				if(!isset($_POST['lat'])) $_POST['lat']='';
				if(!isset($_POST['lon'])) $_POST['lon']='';
				if(!isset($_POST['tim'])) $_POST['tim']='';
				if(!isset($_POST['categoria'])) $_POST['categoria']='';
				if(!isset($_POST['items'])) $_POST['items']='';
				if(!isset($_POST['respuestas'])) $_POST['respuestas']='';
				if(!isset($_POST['observaciones'])) $_POST['observaciones']='';
				if(!isset($_POST['fotos'])) $_POST['fotos']='';
				if(!isset($_POST['fechas'])) $_POST['fechas']='';

				if(!isset($_POST['eess_rut'])) $_POST['eess_rut']='';
				if(!isset($_POST['tra_rut'])) $_POST['tra_rut']='';
				if(!isset($_POST['eva_apellidos'])) $_POST['eva_apellidos']='';
				if(!isset($_POST['eva_nombres'])) $_POST['eva_nombres']='';
				if(!isset($_POST['eva_comuna'])) $_POST['eva_comuna']='';
				if(!isset($_POST['eva_fundo'])) $_POST['eva_fundo']='';
				if(!isset($_POST['eva_faena'])) $_POST['eva_faena']='';
				if(!isset($_POST['eva_jefe_faena'])) $_POST['eva_jefe_faena']='';
				if(!isset($_POST['eva_supervisor'])) $_POST['eva_supervisor']='';
				if(!isset($_POST['eva_apr'])) $_POST['eva_apr']='';
				if(!isset($_POST['eva_linea'])) $_POST['eva_linea']='';
				if(!isset($_POST['eva_vencimiento_corma'])) $_POST['eva_vencimiento_corma']='';

				if(!isset($_POST['eva_licencia_conducir_clase'])) $_POST['eva_licencia_conducir_clase']='';
				if(!isset($_POST['eva_licencia_conducir_vencimiento'])) $_POST['eva_licencia_conducir_vencimiento']='';

				if(!isset($_POST['eva_tipo_cosecha'])) $_POST['eva_tipo_cosecha']='';
				if(!isset($_POST['eva_patente'])) $_POST['eva_patente']='';
				if(!isset($_POST['eva_general_observacion'])) $_POST['eva_general_observacion']='';
				if(!isset($_POST['eva_evaluador'])) $_POST['eva_evaluador']='';
				if(!isset($_POST['eva_cargo'])) $_POST['eva_cargo']='';
				if(!isset($_POST['critico'])) $_POST['critico']='';

				// Crear archivo con información recibida
				$myfile = fopen("recep/".$_POST['android'].$_POST['timestamp'], "w") or die("Unable to open file!");
				$content = json_encode(
					array(
						// Variables fijas
						'android'=>$_POST['android'],
						'timestamp'=>$_POST['timestamp'],
						'lat'=>$_POST['lat'],
						'lon'=>$_POST['lon'],
						'tim'=>$_POST['tim'],
						'categoria'=>utf8_encode($_POST['categoria']),
						'items'=>base64_encode($_POST['items']),
						'respuestas'=>base64_encode($_POST['respuestas']),
						'observaciones'=>base64_encode($_POST['observaciones']),
						'fotos'=>base64_encode($_POST['fotos']),
						'fechas'=>base64_encode($_POST['fechas']),

						// Campos
						"eess_rut"=>utf8_encode($_POST['eess_rut']),
						"tra_rut"=>utf8_encode($_POST['tra_rut']),
						"eva_apellidos"=>utf8_encode($_POST['eva_apellidos']),
						"eva_nombres"=>utf8_encode($_POST['eva_nombres']),
						"eva_comuna"=>utf8_encode($_POST['eva_comuna']),
						"eva_fundo"=>utf8_encode($_POST['eva_fundo']),
						"eva_faena"=>utf8_encode($_POST['eva_faena']),
						"eva_jefe_faena"=>utf8_encode($_POST['eva_jefe_faena']),
						"eva_supervisor"=>utf8_encode($_POST['eva_supervisor']),
						"eva_apr"=>utf8_encode($_POST['eva_apr']),
						"eva_linea"=>utf8_encode($_POST['eva_linea']),
						"eva_vencimiento_corma"=>utf8_encode($_POST['eva_vencimiento_corma']),

						"eva_licencia_conducir_clase"=>utf8_encode($_POST['eva_licencia_conducir_clase']),
						"eva_licencia_conducir_vencimiento"=>utf8_encode($_POST['eva_licencia_conducir_vencimiento']),

						"eva_tipo_cosecha"=>utf8_encode($_POST['eva_tipo_cosecha']),
						"eva_general_observacion"=>utf8_encode($_POST['eva_general_observacion']),

						// Otros
						"eva_general_foto"=>utf8_encode($_POST['general_foto']),
						"eva_general_fecha"=>utf8_encode($_POST['general_fecha']),
						"eva_evaluador"=>utf8_encode($_POST['eva_evaluador']),
						"eva_cargo"=>utf8_encode($_POST['eva_cargo']),

					)
				);
				fwrite($myfile, $content);
				fclose($myfile);

				// Una vez guardado el archivo generar inserción SQL
				$myfile = fopen("recep/".$_POST['android'].$_POST['timestamp'], "r") or die("Unable to open file!");
				$content = fread($myfile,filesize("recep/".$_POST['android'].$_POST['timestamp']));
				fclose($myfile);

				//echo $content.'<hr>';

				$obj = json_decode($content);

				// Procesar info

				$guardaversion = "INSERT INTO min_version_uso(
						id,
						fecha_envio,
						id_eva,
						id_android,
						nombre_evaluador,
						eess_rut,
						modelo_android,
						version_android,
						version_app,
						tipo_ingreso
					) VALUES (
						NULL,
						NULL,
						'".$obj->timestamp."',
						'".$obj->android."',
						'".$obj->eva_evaluador."',
						'".$obj->eess_rut."',
						'sin info',
						'sin version',
						'menosque26',
						'25'
					);";

					$sql = "INSERT INTO min_evaluacion(
						eva_id,
						eva_creado,
						eva_tipo,
						eess_rut,
						tra_rut,
						eva_apellidos,
						eva_nombres,
						eva_fecha_evaluacion,
						eva_fundo,
						eva_comuna,
						eva_jefe_faena,
						eva_faena,
						eva_supervisor,
						eva_apr,
						eva_geo_x,
						eva_geo_y,
						eva_linea,
						eva_vencimiento_corma,
						eva_licencia_conducir_clase,
						eva_licencia_conducir_vencimiento,
						eva_tipo_cosecha,
						eva_general_observacion,
						eva_general_foto,
						eva_general_fecha,
						eva_evaluador,
						eva_cargo
					) VALUES(
						".$obj->timestamp.",
						null,
						'".$obj->categoria."',
						'".$obj->eess_rut."',
						'".$obj->tra_rut."',
						'".$obj->eva_apellidos."',
						'".$obj->eva_nombres."',
						'".date("Y-m-d H:i:s",($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."',
						'".$obj->eva_fundo."',
						'".$obj->eva_comuna."',
						'".$obj->eva_jefe_faena."',
						'".$obj->eva_faena."',
						'".$obj->eva_supervisor."',
						'".$obj->eva_apr."',
						".$obj->lat.",
						".$obj->lon.",
						'".$obj->eva_linea."',
						'".$obj->eva_vencimiento_corma."',
						'".$obj->eva_licencia_conducir_clase."',
						'".$obj->eva_licencia_conducir_vencimiento."',
						'".$obj->eva_tipo_cosecha."',
						'".$obj->eva_general_observacion."',
						'".$obj->eva_general_foto."',
						'".$obj->eva_general_fecha."',
						'".$obj->eva_evaluador."',
						'".$obj->eva_cargo."'
					);";

					$myfile = fopen("recep/sql".$_POST['android'].$_POST['timestamp'], "w") or die("Unable to open file!");
					fwrite($myfile, $sql);
					fclose($myfile);
					Yii::app()->db->createCommand($guardaversion)->execute();
					Yii::app()->db->createCommand($sql)->execute();

					$items = json_decode(utf8_encode(base64_decode($obj->items)));
					$respuestas = json_decode(base64_decode($obj->respuestas));
					$observaciones = json_decode(base64_decode($obj->observaciones));
					$fotos = json_decode(base64_decode($obj->fotos));
					$fechas = json_decode(base64_decode($obj->fechas));

					for($i=0;$i<count($items);$i++)
					{
						$seguimiento = 1;
						if($respuestas[$i] == 'no') $seguimiento = 0;
						//insertar respuesta
						$sql = "INSERT INTO min_respuesta(
							res_enunciado,
							res_respuesta,
							res_critico,
							res_ponderacion,
							pre_id,
							car_id,
							tem_id,
							res_observacion,
							res_foto,
							eva_id,
							res_seguimiento,
							res_plazo
						) VALUES (
							'".$items[$i]->pre_enunciado."',
							'".$respuestas[$i]."',
							'".$items[$i]->critico."',
							'".$items[$i]->pre_ponderacion."',
							'".$items[$i]->pre_id."',
							'".$obj->categoria."',
							'".$items[$i]->tem_id."',
							'".urldecode($observaciones[$i])."',
							'".$fotos[$i]."',
							".$obj->timestamp.",
							'".$seguimiento."',
							'".$fechas[$i]."'
						);";
						Yii::app()->db->createCommand($sql)->execute();
					}

					// Actualizar porcentaje cache
					$limit1 = Yii::app()->params['riesgoalto'];
					$limit2 = Yii::app()->params['riesgomedio'];
					$id = $obj->timestamp;
					$sql = "SELECT eva_id FROM min_respuesta WHERE eva_id = ".$id." GROUP BY eva_id";
					$categorias = Yii::app()->db->createCommand($sql)->query()->readAll();
					$nota = 0;
					$todosna = 0;

					$si = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) as n FROM min_respuesta WHERE eva_id = ".$id."  AND res_respuesta = 'si' GROUP BY res_respuesta")->queryScalar();
					$no = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) as n FROM min_respuesta WHERE eva_id = ".$id."  AND res_respuesta = 'no' GROUP BY res_respuesta")->queryScalar();
					$na = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) as n FROM min_respuesta WHERE eva_id = ".$id."  AND res_respuesta = 'n/a' GROUP BY res_respuesta")->queryScalar();
					if($si == '') $si = 0;
					if($no == '') $no = 0;
					if($na == '') $na = 0;
					if(($si + $no) > 0) $r = 100*($si / ($si + $no)); else $r = 0;
					$nota=floor($r);
					$ref = floor($nota);


					// Actualizar porcentaje cache y correlativo evaluador
					$corr = Yii::app()->db->createCommand("SELECT MAX(eva_evaluador_correlativo)+1 FROM min_evaluacion WHERE eva_evaluador = '".$obj->eva_evaluador."'")->queryScalar();
					// Obtener cargo desde aplicación, si viene vacío, sacar de mantenedor de trabajadores.
					$evaCargo = $obj->eva_cargo;
					if($evaCargo=='') $evaCargo = Yii::app()->db->createCommand("SELECT car_descripcion FROM min_evaluacion as E JOIN min_trabajador as T ON(E.tra_rut = T.tra_rut) JOIN min_cargo as C ON(T.car_id = C.car_id) where eva_id = '".$obj->timestamp."'")->queryScalar();
					$sql="UPDATE min_evaluacion SET eva_cache_porcentaje = '".$ref."', eva_evaluador_correlativo = '".$corr."' WHERE eva_id = ".$obj->timestamp;
					Yii::app()->db->createCommand($sql)->execute();

					// Enviar correos con la evaluación
					 $limit1 = Yii::app()->params['riesgoalto'];
					$limit2 = Yii::app()->params['riesgomedio'];
					//$nv = 'Bajo';
					//if($ref < Yii::app()->params['riesgomedio']) $nv = 'Medio';
					//if($ref < Yii::app()->params['riesgoalto']) $nv = 'Alto';
					if($ref>=0 && $ref<=$limit1) $nv = 'Alto';
					if($ref>$limit1 && $ref<=$limit2) $nv = 'Medio';
					if($ref>$limit2 && $ref<=100) $nv = 'Bajo';

					$ver= Yii::app()->db->createCommand("SELECT res_critico,res_respuesta FROM min_respuesta WHERE eva_id = ".$id)->query()->readAll();
					for($s=0;$s<count($ver);$s++)
					{
						if($ver[$s]['res_critico'] == 'si'&&$ver[$s]['res_respuesta']=='no'){
							$alerta1 = '¡Precaución!: Trabajador ha incumplido una pregunta considerada crítica.';//';
							$alerta2 = ' Se recomienda Identificar las desviaciones, implementar las medidas correctivas y un realizar un seguimiento sistemático.';//';
							$tienecriticosi='si';

						}else if($ver[$s]['res_critico'] == 'si'&&$ver[$s]['res_respuesta']=='si'){
							$alerta1 = '';
							$alerta2 = '';
							$tienecriticono = 'no';
						}else{
							$alerta1 = '';
							$alerta2 = '';
							$tienecritico = 'no';
							$tienecriticosi = 'no';
							$tienecriticono = 'no';
						}
					}
					//verificamos si cumple con el porcentaje minimo de respuesta
					$verpromedio = Yii::app()->db->createCommand("SELECT eva_cache_porcentaje From min_evaluacion WHERE eva_id=".$id)->query()->readAll();
					for($t=0;$t<count($verpromedio);$t++)
					{
						$notapromedio = number_format(floor($verpromedio[$t]['eva_cache_porcentaje']));
						if($notapromedio>85 and $tienecriticosi=='si'){
							$alerta1 = '¡Precaución!: Trabajador ha incumplido una pregunta considerada crítica.';//';
							$alerta2 = 'Se recomienda Identificar las desviaciones, implementar las medidas correctivas y un realizar un seguimiento sistemático.';//';

						}elseif($notapromedio<=85 or $tienecriticosi=='si'){
							$alerta1 = ' ¡ALERTA! Trabajador en Nivel de Riesgo ALTO';//';
							$alerta2 = ' Se recomienda detener los trabajos y reinstruir al trabajador.';//';
						}elseif($notapromedio>85 and $tienecriticono=='no'){
							$alerta1 = '';
							$alerta2 = '';
						}elseif($tienecriticosi=='si'){
							$alerta1 = ' ¡Precaución!: Trabajador ha incumplido una pregunta considerada crítica.';//';
							$alerta2 = ' Se recomienda Identificar las desviaciones, implementar las medidas correctivas y un realizar un seguimiento sistemático.';//';
						}elseif($tienecritico=='no'){
							$alerta1 = '';//';
							$alerta2 = '';//';
						}
					}
					//cambios4
					// Enviar email a medio mundo

					$eess = Yii::app()->db->createCommand("SELECT eess_nombre_corto FROM min_eess WHERE eess_rut = '".$obj->eess_rut."'")->queryScalar();
					$eessR = Yii::app()->db->createCommand("SELECT eess_rut FROM min_eess WHERE eess_rut = '".$obj->eess_rut."'")->queryScalar();
					if ($eessR == '76885630') {
						$asunto = "".json_decode(json_encode($obj->categoria))."";
						// a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>
						$email = "
						<p>Se&ntilde;ores<br><br></p>
						<p>Informamos a usted que con fecha <b>".date("d-m-Y", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>, a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b> se llev&oacute; a cabo un Control Operacional a uno de sus trabajadores que se desempe&ntilde;a como ".$evaCargo.".</p>
						<p>El resultado de esta Evaluaci&oacute;n fue de un <b>".floor($nota)." %</b> de cumplimiento, lo que representa un Nivel de Riesgo <b>".$nv."</b></p>
						<p align='center' style='color:red;'><b>".$alerta1." </b></p><p align='center'><b>".$alerta2."</b></p>
						<p>El respectivo Informe se encuentra disponible en el archivo adjunto a este mensaje. <!--o ingresando a la <a href='http://innoapsion.cl/terreno/login-eess/'>siguiente plataforma</a>, teniendo como credenciales de acceso, tanto para el usuario y contrase&ntilde;a, el Rut de su Empresa, sin puntos, guion ni d&iacute;gito verificador (Rut: 1234567)--></p>
						<p>Atte.<br>SAFCO LTDA.<br></p>
						";
						$headers = "From: SAFCO LTDA <no-reply@safco.cl> \r\n";
					}else{
					$asunto = "Evaluación de Desempeño";
					// a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>
					if($nota < 100) $email = "
					<p>Se&ntilde;ores<br><b>".json_decode(json_encode($eess))."</b><br></p>
					<p>Informamos a usted que con fecha <b>".date("d-m-Y", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>, a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b> se llev&oacute; a cabo una Evaluaci&oacute;n SEDECC a uno de sus trabajadores que se desempe&ntilde;a como ".$evaCargo.".</p>
					<p>El resultado de esta Evaluaci&oacute;n fue de un <b>".floor($nota)." %</b> de cumplimiento, lo que representa un Nivel de Riesgo <b>".$nv."</b></p>
					<p align='center' style='color:red;'><b>".$alerta1." </b></p><p align='center'><b>".$alerta2."</b></p>
					<p>El respectivo Informe se encuentra disponible en el archivo adjunto a este mensaje. <!--o ingresando a la <a href='http://innoapsion.cl/terreno/login-eess/'>siguiente plataforma</a>, teniendo como credenciales de acceso, tanto para el usuario y contrase&ntilde;a, el Rut de su Empresa, sin puntos, guion ni d&iacute;gito verificador (Rut: 1234567)--></p>
					<p>Las consultas referidas a la Evaluaci&oacute;n favor dirigirlas al Ejecutor de la misma y que se encuentra identificado en el Informe.</b>
					<p>Atte.<br>Innoapsion.<br></p>
					";
					//a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>
					else $email = "
					<p>Se&ntilde;ores<br><b>".json_decode(json_encode($eess))."</b><br></p>
					<p>Informamos a usted que con fecha <b>".date("d-m-Y", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>, a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b> se llev&oacute; a cabo una Evaluaci&oacute;n SEDECC a uno de sus trabajadores que se desempe&ntilde;a como ".$evaCargo.".</p>
					<p>El resultado de esta Evaluaci&oacute;n fue de un <b>".floor($nota)." %</b> de cumplimiento, lo que representa un Nivel de Riesgo <b>".$nv."</b></p>
					<p align='center' style='color:red;'><b>".$alerta1." </b></p><p align='center'><b>".$alerta2."</b></p>
					<p>El respectivo Informe se encuentra disponible en el archivo adjunto a este mensaje. <!--o ingresando a la <a href='http://innoapsion.cl/terreno/login-eess/'>siguiente plataforma</a>, teniendo como credenciales de acceso, tanto para el usuario y contrase&ntilde;a, el Rut de su Empresa, sin puntos, guion ni d&iacute;gito verificador (Rut: 1234567)--></p>
					<p>Las consultas referidas a la Evaluaci&oacute;n favor dirigirlas al Ejecutor de la misma y que se encuentra identificado en el Informe.</b>
					<p>Atte.<br>Innoapsion.<br></p>
					";
					$headers = "From: Sedecc <sedecc@innoapsion.cl> \r\n";
					}

				if ($eessR == '76885630') {
					$archivo = chunk_split(base64_encode(file_get_contents("http://innoapsion.cl/sedecc/index.php?r=evaluacion/pdfsafco&id=".$obj->timestamp)));
				}else{
					$idpdf = $obj->timestamp;
					$enlacepdf = "http://innoapsion.cl/sedecc/index.php?r=evaluacion/pdf&id=";
					$archivo = chunk_split(base64_encode(file_get_contents("http://innoapsion.cl/sedecc/index.php?r=evaluacion/pdf&id=".$obj->timestamp)));
				}

				$otrosemails = Yii::app()->db->createCommand("SELECT GROUP_CONCAT(ema_email) FROM min_email WHERE eess_rut = '".$obj->eess_rut."'")->queryScalar();

				$direccionesem = Yii::app()->db->createCommand("
				SELECT GROUP_CONCAT(tra_email) FROM `min_trabajador` WHERE
				CONCAT(tra_nombres,' ',tra_apellidos) = '".$obj->eva_nombres." ".$obj->eva_apellidos."' OR
				CONCAT(tra_nombres,' ',tra_apellidos) = '".$obj->eva_supervisor."' OR
				CONCAT(tra_nombres,' ',tra_apellidos) = '".$obj->eva_jefe_faena."' OR
				CONCAT(tra_nombres,' ',tra_apellidos) = '".$obj->eva_apr."' OR
				tra_rut = '".$obj->eva_evaluador."'
				")->queryScalar();

				$direccioneses = Yii::app()->db->createCommand("SELECT eess_email FROM `min_eess` WHERE eess_rut = '".$obj->eess_rut."'")->queryScalar();
				//$direccioneses="";
				$headers .= "Cc: ".$direccionesem.", ".$otrosemails." \r\n"; //
				//prueba de correo $headers .= "Cc:  \r\n"; //
				$headers .= "Bcc: ronnymunoz22@gmail.com, eduardoacevedo@innoapsion.cl, sebastian.carcamo398@gmail.com " . "\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
			    $headers .= "Content-Type: multipart/mixed; boundary=\"=A=G=R=O=\"\r\n\r\n";
			    $email_message = "--=A=G=R=O=\r\n";
			    $email_message .= "Content-type: text/html; charset=iso-8859-1\r\n";
			    $email_message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
				$email_message .= $email . "\r\n\r\n";
			    $email_message .= "--=A=G=R=O=\r\n";
			    $email_message .= "Content-Type: application/octet-stream; name=\"Evaluaciones.pdf\"\r\n";
			    $email_message .= "Content-Transfer-Encoding: base64\r\n";
			    $email_message .= "Content-Disposition: attachment; filename=\"Evaluaciones.pdf\"\r\n\r\n";
			    $email_message .= $archivo . "\r\n\r\n";
			    $email_message .= "--=A=G=R=O=\r\n";
			   mail($direccioneses, $asunto, $email_message, $headers); // Quitar email de prueba

				// Check de éxito
				echo json_encode("json_valido_sedecc");



				// LO QUE OCURRE DESDE ACÁ NO ES CRÍTICO, POR ESO SE HACE POST EL MENSAJE DE ÉXITO

				// Ingresar trabajador si no existe
				$c = Yii::app()->db->createCommand("SELECT COUNT(*) FROM min_trabajador
				WHERE eess_rut = '".$obj->eess_rut."' AND tra_rut = '".$obj->tra_rut."'")->queryScalar();
				if($c == 0){
					Yii::app()->db->createCommand("INSERT INTO min_trabajador(
						eess_rut,
						tra_rut,
						tra_nombres,
						tra_apellidos,
						tra_licencia_conducir
					)VALUES(
						'".$obj->eess_rut."',
						'".$obj->tra_rut."',
						'".$obj->eva_nombres."',
						'".$obj->eva_apellidos."',
						'".$obj->eva_licencia_conducir_clase."'
					)")->execute();
				}

				// Si existe actualizar corma y licencia de conducir
				if($obj->eva_vencimiento_corma != ''){
					Yii::app()->db->createCommand("UPDATE min_trabajador SET
						tra_vencimiento_corma = '".DateTime::createFromFormat('d-m-Y', $obj->eva_vencimiento_corma)->format('Y-m-d')."'
					WHERE tra_rut = '".$obj->tra_rut."'
					")->execute();
				}
				if($obj->eva_licencia_conducir_vencimiento != ''){
					Yii::app()->db->createCommand("UPDATE min_trabajador SET
						tra_vencimiento_licencia_conducir = '".DateTime::createFromFormat('d-m-Y', $obj->eva_licencia_conducir_vencimiento)->format('Y-m-d')."'
					WHERE tra_rut = '".$obj->tra_rut."'
					")->execute();
				}

				// Ingresar faena si no existe
				$c = Yii::app()->db->createCommand("SELECT COUNT(*) FROM min_faena
				WHERE fae_nombre = '".$obj->eva_faena."' AND eess_rut = '".$obj->eess_rut."'")->queryScalar();
				if($c == 0){
					Yii::app()->db->createCommand("INSERT INTO min_faena(
						fae_nombre,
						eess_rut,
						tipo
					) VALUES(
						'".$obj->eva_faena."',
						'".$obj->eess_rut."',
						'".$obj->eva_tipo_cosecha."'
					)")->execute();
				}

				// Cada vez que llegue una evaluación, se actualizarán los caché por ítem pendientes.
				$rows = Yii::app()->db->createCommand("SELECT eva_id, eva_tipo, tra_rut, eva_nombres, eva_apellidos, eva_cache_porcentaje FROM min_evaluacion WHERE eva_item_nombre_0 is NULL")->queryAll();
				for($i=0;$i<count($rows);$i++){
					// Obtener categorías de cada evaluación
					$categorias = Yii::app()->db->createCommand("SELECT DISTINCT tem_id FROM min_respuesta WHERE car_id = '".$rows[$i]['eva_tipo']."' ORDER BY tem_id")->queryAll();
					for($j=0;$j<count($categorias);$j++){
						// Asignar nombre de ítem
						Yii::app()->db->createCommand("UPDATE min_evaluacion SET eva_item_nombre_".$j." = '".$categorias[$j]['tem_id']."' WHERE eva_id = '".$rows[$i]['eva_id']."'")->execute();
						// Asignar nota
						$si = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) FROM min_evaluacion e LEFT JOIN min_respuesta r ON (e.eva_id = r.eva_id) WHERE r.eva_id = '".$rows[$i]['eva_id']."' AND r.tem_id = '".$categorias[$j]['tem_id']."' AND res_respuesta = 'si'")->queryScalar();
						$no = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) FROM min_evaluacion e LEFT JOIN min_respuesta r ON (e.eva_id = r.eva_id) WHERE r.eva_id = '".$rows[$i]['eva_id']."' AND r.tem_id = '".$categorias[$j]['tem_id']."' AND res_respuesta = 'no'")->queryScalar();
						if($si+$no>0){
							Yii::app()->db->createCommand("UPDATE min_evaluacion SET eva_item_nota_".$j." = '".floor(100*($si/($si+$no)))."' WHERE eva_id = '".$rows[$i]['eva_id']."'")->execute();
						}
					}
				}
			}
			else{
				echo json_encode("ERROR");
			}
		}//cierre control version
		if(isset($_POST['version_control'])){
			$traeversion = $_POST['version_control'];
			if($traeversion==$version_actual)
			{
				if(isset($_POST['android']))
				{
					//Parche para variables no definidas, util para actualizaciones
					if(!isset($_POST['android'])) $_POST['android']='';
					if(!isset($_POST['timestamp'])) $_POST['timestamp']='';
					if(!isset($_POST['lat'])) $_POST['lat']='';
					if(!isset($_POST['lon'])) $_POST['lon']='';
					if(!isset($_POST['tim'])) $_POST['tim']='';
					if(!isset($_POST['categoria'])) $_POST['categoria']='';
					if(!isset($_POST['items'])) $_POST['items']='';
					if(!isset($_POST['respuestas'])) $_POST['respuestas']='';
					if(!isset($_POST['observaciones'])) $_POST['observaciones']='';
					if(!isset($_POST['fotos'])) $_POST['fotos']='';
					if(!isset($_POST['fechas'])) $_POST['fechas']='';

					if(!isset($_POST['eess_rut'])) $_POST['eess_rut']='';
					if(!isset($_POST['tra_rut'])) $_POST['tra_rut']='';
					if(!isset($_POST['eva_apellidos'])) $_POST['eva_apellidos']='';
					if(!isset($_POST['eva_nombres'])) $_POST['eva_nombres']='';
					if(!isset($_POST['eva_comuna'])) $_POST['eva_comuna']='';
					if(!isset($_POST['eva_fundo'])) $_POST['eva_fundo']='';
					if(!isset($_POST['eva_faena'])) $_POST['eva_faena']='';
					if(!isset($_POST['eva_jefe_faena'])) $_POST['eva_jefe_faena']='';
					if(!isset($_POST['eva_supervisor'])) $_POST['eva_supervisor']='';
					if(!isset($_POST['eva_apr'])) $_POST['eva_apr']='';
					if(!isset($_POST['eva_linea'])) $_POST['eva_linea']='';
					if(!isset($_POST['eva_vencimiento_corma'])) $_POST['eva_vencimiento_corma']='';

					if(!isset($_POST['eva_licencia_conducir_clase'])) $_POST['eva_licencia_conducir_clase']='';
					if(!isset($_POST['eva_licencia_conducir_vencimiento'])) $_POST['eva_licencia_conducir_vencimiento']='';

					if(!isset($_POST['eva_tipo_cosecha'])) $_POST['eva_tipo_cosecha']='';
					if(!isset($_POST['eva_patente'])) $_POST['eva_patente']='';
					if(!isset($_POST['eva_fecha'])) $_POST['eva_fecha']='';
					if(!isset($_POST['eva_hora'])) $_POST['eva_hora']='';
					if(!isset($_POST['eva_lugar'])) $_POST['eva_lugar']='';
					if(!isset($_POST['eva_general_observacion'])) $_POST['eva_general_observacion']='';
					if(!isset($_POST['eva_evaluador'])) $_POST['eva_evaluador']='';
					if(!isset($_POST['eva_cargo'])) $_POST['eva_cargo']='';
					//vesionamiento
					if(!isset($_POST['version_control'])) $_POST['version_control']='';
					if(!isset($_POST['modelo_dispo'])) $_POST['modelo_dispo']='';
					if(!isset($_POST['version_android'])) $_POST['version_android']='';
					if(!isset($_POST['critico'])) $_POST['critico']='';

					// Crear archivo con información recibida
					$myfile = fopen("recep/".$_POST['android'].$_POST['timestamp'], "w") or die("Unable to open file!");
					$content = json_encode(
						array(
							// Variables fijas
							'android'=>$_POST['android'],
							'timestamp'=>$_POST['timestamp'],
							'lat'=>$_POST['lat'],
							'lon'=>$_POST['lon'],
							'tim'=>$_POST['tim'],
							'categoria'=>utf8_encode($_POST['categoria']),
							'items'=>base64_encode($_POST['items']),
							'respuestas'=>base64_encode($_POST['respuestas']),
							'observaciones'=>base64_encode($_POST['observaciones']),
							'fotos'=>base64_encode($_POST['fotos']),
							'fechas'=>base64_encode($_POST['fechas']),

							// Campos
							"eess_rut"=>utf8_encode($_POST['eess_rut']),
							"tra_rut"=>utf8_encode($_POST['tra_rut']),
							"eva_apellidos"=>utf8_encode($_POST['eva_apellidos']),
							"eva_nombres"=>utf8_encode($_POST['eva_nombres']),
							"eva_comuna"=>utf8_encode($_POST['eva_comuna']),
							"eva_fundo"=>utf8_encode($_POST['eva_fundo']),
							"eva_faena"=>utf8_encode($_POST['eva_faena']),
							"eva_jefe_faena"=>utf8_encode($_POST['eva_jefe_faena']),
							"eva_supervisor"=>utf8_encode($_POST['eva_supervisor']),
							"eva_apr"=>utf8_encode($_POST['eva_apr']),
							"eva_linea"=>utf8_encode($_POST['eva_linea']),
							"eva_vencimiento_corma"=>utf8_encode($_POST['eva_vencimiento_corma']),

							"eva_licencia_conducir_clase"=>utf8_encode($_POST['eva_licencia_conducir_clase']),
							"eva_licencia_conducir_vencimiento"=>utf8_encode($_POST['eva_licencia_conducir_vencimiento']),

							"eva_tipo_cosecha"=>utf8_encode($_POST['eva_tipo_cosecha']),
							"eva_patente"=>utf8_encode($_POST['eva_patente']),
							"eva_fecha"=>utf8_encode($_POST['eva_fecha']),
							"eva_hora"=>utf8_encode($_POST['eva_hora']),
							"eva_lugar"=>utf8_encode($_POST['eva_lugar']),
							"eva_general_observacion"=>utf8_encode($_POST['eva_general_observacion']),

							// Otros
							"eva_general_foto"=>utf8_encode($_POST['general_foto']),
							"eva_general_fecha"=>utf8_encode($_POST['general_fecha']),
							"eva_evaluador"=>utf8_encode($_POST['eva_evaluador']),
							"eva_cargo"=>utf8_encode($_POST['eva_cargo']),

							//versiones
							"version_control"=>utf8_encode($_POST['version_control']),
							"modelo_dispo"=>utf8_encode($_POST['modelo_dispo']),
							"version_android"=>utf8_encode($_POST['version_android']),

						)
					);
					fwrite($myfile, $content);
					fclose($myfile);

					// Una vez guardado el archivo generar inserción SQL
					$myfile = fopen("recep/".$_POST['android'].$_POST['timestamp'], "r") or die("Unable to open file!");
					$content = fread($myfile,filesize("recep/".$_POST['android'].$_POST['timestamp']));
					fclose($myfile);

					//echo $content.'<hr>';

					$obj = json_decode($content);

					// Procesar info
					$guardaversion = "INSERT INTO min_version_uso(
						id,
						fecha_envio,
						id_eva,
						id_android,
						nombre_evaluador,
						eess_rut,
						modelo_android,
						version_android,
						version_app,
						tipo_ingreso
					) VALUES (
						NULL,
						NULL,
						'".$obj->timestamp."',
						'".$obj->android."',
						'".$obj->eva_evaluador."',
						'".$obj->eess_rut."',
						'".$obj->modelo_dispo."',
						'".$obj->version_android."',
						'".$obj->version_control."',
						'".$version_actual."'
					);";

					$sql = "INSERT INTO min_evaluacion(
						eva_id,
						eva_creado,
						eva_tipo,
						eess_rut,
						tra_rut,
						eva_apellidos,
						eva_nombres,
						eva_fecha_evaluacion,
						eva_fundo,
						eva_comuna,
						eva_jefe_faena,
						eva_faena,
						eva_supervisor,
						eva_apr,
						eva_geo_x,
						eva_geo_y,
						eva_linea,
						eva_vencimiento_corma,
						eva_licencia_conducir_clase,
						eva_licencia_conducir_vencimiento,
						eva_tipo_cosecha,
						eva_patente,
						eva_general_observacion,
						eva_general_foto,
						eva_general_fecha,
						eva_evaluador,
						eva_cargo
					) VALUES(
						".$obj->timestamp.",
						null,
						'".$obj->categoria."',
						'".$obj->eess_rut."',
						'".$obj->tra_rut."',
						'".$obj->eva_apellidos."',
						'".$obj->eva_nombres."',
						'".date("Y-m-d H:i:s",($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."',
						'".$obj->eva_fundo."',
						'".$obj->eva_comuna."',
						'".$obj->eva_jefe_faena."',
						'".$obj->eva_faena."',
						'".$obj->eva_supervisor."',
						'".$obj->eva_apr."',
						".$obj->lat.",
						".$obj->lon.",
						'".$obj->eva_linea."',
						'".$obj->eva_vencimiento_corma."',
						'".$obj->eva_licencia_conducir_clase."',
						'".$obj->eva_licencia_conducir_vencimiento."',
						'".$obj->eva_tipo_cosecha."',
						'".$obj->eva_patente."',
						'".$obj->eva_general_observacion."',
						'".$obj->eva_general_foto."',
						'".$obj->eva_general_fecha."',
						'".$obj->eva_evaluador."',
						'".$obj->eva_cargo."'
					);";

					$myfile = fopen("recep/sql".$_POST['android'].$_POST['timestamp'], "w") or die("Unable to open file!");
					fwrite($myfile, $sql);
					fclose($myfile);
					Yii::app()->db->createCommand($guardaversion)->execute();
					Yii::app()->db->createCommand($sql)->execute();

					$items = json_decode(utf8_encode(base64_decode($obj->items)));
					$respuestas = json_decode(base64_decode($obj->respuestas));
					$observaciones = json_decode(base64_decode($obj->observaciones));
					$fotos = json_decode(base64_decode($obj->fotos));
					$fechas = json_decode(base64_decode($obj->fechas));

					for($i=0;$i<count($items);$i++)
					{
						$seguimiento = 1;
						if($respuestas[$i] == 'no') $seguimiento = 0;
						//insertar respuesta
						$sql = "INSERT INTO min_respuesta(
							res_enunciado,
							res_respuesta,
							res_critico,
							res_ponderacion,
							pre_id,
							car_id,
							tem_id,
							res_observacion,
							res_foto,
							eva_id,
							res_seguimiento,
							res_plazo
						) VALUES (
							'".$items[$i]->pre_enunciado."',
							'".$respuestas[$i]."',
							'".$items[$i]->critico."',
							'".$items[$i]->pre_ponderacion."',
							'".$items[$i]->pre_id."',
							'".$obj->categoria."',
							'".$items[$i]->tem_id."',
							'".urldecode($observaciones[$i])."',
							'".$fotos[$i]."',
							".$obj->timestamp.",
							'".$seguimiento."',
							'".$fechas[$i]."'
						);";
						Yii::app()->db->createCommand($sql)->execute();
					}

					// Actualizar porcentaje cache
					$limit1 = Yii::app()->params['riesgoalto'];
					$limit2 = Yii::app()->params['riesgomedio'];
					$id = $obj->timestamp;
					$sql = "SELECT eva_id FROM min_respuesta WHERE eva_id = ".$id." GROUP BY eva_id";
					$categorias = Yii::app()->db->createCommand($sql)->query()->readAll();
					$nota = 0;
					$todosna = 0;

					$si = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) as n FROM min_respuesta WHERE eva_id = ".$id."  AND res_respuesta = 'si' GROUP BY res_respuesta")->queryScalar();
					$no = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) as n FROM min_respuesta WHERE eva_id = ".$id."  AND res_respuesta = 'no' GROUP BY res_respuesta")->queryScalar();
					$na = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) as n FROM min_respuesta WHERE eva_id = ".$id."  AND res_respuesta = 'n/a' GROUP BY res_respuesta")->queryScalar();
					if($si == '') $si = 0;
					if($no == '') $no = 0;
					if($na == '') $na = 0;
					if(($si + $no) > 0) $r = 100*($si / ($si + $no)); else $r = 0;
					$nota=floor($r);
					$ref = floor($nota);


					// Actualizar porcentaje cache y correlativo evaluador
					$corr = Yii::app()->db->createCommand("SELECT MAX(eva_evaluador_correlativo)+1 FROM min_evaluacion WHERE eva_evaluador = '".$obj->eva_evaluador."'")->queryScalar();
					// Obtener cargo desde aplicación, si viene vacío, sacar de mantenedor de trabajadores.
					$evaCargo = $obj->eva_cargo;
					if($evaCargo=='') $evaCargo = Yii::app()->db->createCommand("SELECT car_descripcion FROM min_evaluacion as E JOIN min_trabajador as T ON(E.tra_rut = T.tra_rut) JOIN min_cargo as C ON(T.car_id = C.car_id) where eva_id = '".$obj->timestamp."'")->queryScalar();
					$sql="UPDATE min_evaluacion SET eva_cache_porcentaje = '".$ref."', eva_evaluador_correlativo = '".$corr."' WHERE eva_id = ".$obj->timestamp;
					Yii::app()->db->createCommand($sql)->execute();

					 // Enviar correos con la evaluación
					 $limit1 = Yii::app()->params['riesgoalto'];
					$limit2 = Yii::app()->params['riesgomedio'];
					//$nv = 'Bajo';
					//if($ref < Yii::app()->params['riesgomedio']) $nv = 'Medio';
					//if($ref < Yii::app()->params['riesgoalto']) $nv = 'Alto';
					if($ref>=0 && $ref<=$limit1) $nv = 'Alto';
					if($ref>$limit1 && $ref<=$limit2) $nv = 'Medio';
					if($ref>$limit2 && $ref<=100) $nv = 'Bajo';

					$ver= Yii::app()->db->createCommand("SELECT res_critico,res_respuesta FROM min_respuesta WHERE eva_id = ".$id)->query()->readAll();
					for($s=0;$s<count($ver);$s++)
					{
						if($ver[$s]['res_critico'] == 'si'&&$ver[$s]['res_respuesta']=='no'){
							$alerta1 = '¡Precaución!: Trabajador ha incumplido una pregunta considerada crítica.';//';
							$alerta2 = ' Se recomienda Identificar las desviaciones, implementar las medidas correctivas y un realizar un seguimiento sistemático.';//';
							$tienecriticosi='si';

						}else if($ver[$s]['res_critico'] == 'si'&&$ver[$s]['res_respuesta']=='si'){
							$alerta1 = '';
							$alerta2 = '';
							$tienecriticono = 'no';
						}else{
							$alerta1 = '';
							$alerta2 = '';
							$tienecritico = 'no';
							$tienecriticosi = 'no';
							$tienecriticono = 'no';
						}
					}
					//verificamos si cumple con el porcentaje minimo de respuesta
					$verpromedio = Yii::app()->db->createCommand("SELECT eva_cache_porcentaje From min_evaluacion WHERE eva_id=".$id)->query()->readAll();
					for($t=0;$t<count($verpromedio);$t++)
					{
						$notapromedio = number_format(floor($verpromedio[$t]['eva_cache_porcentaje']));
						if($notapromedio>85 and $tienecriticosi=='si'){
							$alerta1 = '¡Precaución!: Trabajador ha incumplido una pregunta considerada crítica.';//';
							$alerta2 = 'Se recomienda Identificar las desviaciones, implementar las medidas correctivas y un realizar un seguimiento sistemático.';//';

						}elseif($notapromedio<=85 or $tienecriticosi=='si'){
							$alerta1 = ' ¡ALERTA! Trabajador en Nivel de Riesgo ALTO';//';
							$alerta2 = ' Se recomienda detener los trabajos y reinstruir al trabajador.';//';
						}elseif($notapromedio>85 and $tienecriticono=='no'){
							$alerta1 = '';
							$alerta2 = '';
						}elseif($tienecriticosi=='si'){
							$alerta1 = ' ¡Precaución!: Trabajador ha incumplido una pregunta considerada crítica.';//';
							$alerta2 = ' Se recomienda Identificar las desviaciones, implementar las medidas correctivas y un realizar un seguimiento sistemático.';//';
						}elseif($tienecritico=='no'){
							$alerta1 = '';//';
							$alerta2 = '';//';
						}
					}// Enviar correos con la evaluación
					 $limit1 = Yii::app()->params['riesgoalto'];
					$limit2 = Yii::app()->params['riesgomedio'];
					//$nv = 'Bajo';
					//if($ref < Yii::app()->params['riesgomedio']) $nv = 'Medio';
					//if($ref < Yii::app()->params['riesgoalto']) $nv = 'Alto';
					if($ref>=0 && $ref<=$limit1) $nv = 'Alto';
					if($ref>$limit1 && $ref<=$limit2) $nv = 'Medio';
					if($ref>$limit2 && $ref<=100) $nv = 'Bajo';

					$ver= Yii::app()->db->createCommand("SELECT res_critico,res_respuesta FROM min_respuesta WHERE eva_id = ".$id)->query()->readAll();
					for($s=0;$s<count($ver);$s++)
					{
						if($ver[$s]['res_critico'] == 'si'&&$ver[$s]['res_respuesta']=='no'){
							$alerta1 = '¡Precaución!: Trabajador ha incumplido una pregunta considerada crítica.';//';
							$alerta2 = ' Se recomienda Identificar las desviaciones, implementar las medidas correctivas y un realizar un seguimiento sistemático.';//';
							$tienecriticosi='si';

						}else if($ver[$s]['res_critico'] == 'si'&&$ver[$s]['res_respuesta']=='si'){
							$alerta1 = '';
							$alerta2 = '';
							$tienecriticono = 'no';
						}else{
							$alerta1 = '';
							$alerta2 = '';
							$tienecritico = 'no';
							$tienecriticosi = 'no';
							$tienecriticono = 'no';
						}
					}
					//verificamos si cumple con el porcentaje minimo de respuesta
					$verpromedio = Yii::app()->db->createCommand("SELECT eva_cache_porcentaje From min_evaluacion WHERE eva_id=".$id)->query()->readAll();
					for($t=0;$t<count($verpromedio);$t++)
					{
						$notapromedio = number_format(floor($verpromedio[$t]['eva_cache_porcentaje']));
						if($notapromedio>85 and $tienecriticosi=='si'){
							$alerta1 = '¡Precaución!: Trabajador ha incumplido una pregunta considerada crítica.';//';
							$alerta2 = 'Se recomienda Identificar las desviaciones, implementar las medidas correctivas y un realizar un seguimiento sistemático.';//';

						}elseif($notapromedio<=85 or $tienecriticosi=='si'){
							$alerta1 = ' ¡ALERTA! Trabajador en Nivel de Riesgo ALTO';//';
							$alerta2 = ' Se recomienda detener los trabajos y reinstruir al trabajador.';//';
						}elseif($notapromedio>85 and $tienecriticono=='no'){
							$alerta1 = '';
							$alerta2 = '';
						}elseif($tienecriticosi=='si'){
							$alerta1 = ' ¡Precaución!: Trabajador ha incumplido una pregunta considerada crítica.';//';
							$alerta2 = ' Se recomienda Identificar las desviaciones, implementar las medidas correctivas y un realizar un seguimiento sistemático.';//';
						}elseif($tienecritico=='no'){
							$alerta1 = '';//';
							$alerta2 = '';//';
						}
					}
					//cambios4
					// Enviar email a medio mundo

					$eess = Yii::app()->db->createCommand("SELECT eess_nombre_corto FROM min_eess WHERE eess_rut = '".$obj->eess_rut."'")->queryScalar();
					$eessR = Yii::app()->db->createCommand("SELECT eess_rut FROM min_eess WHERE eess_rut = '".$obj->eess_rut."'")->queryScalar();
					if ($eessR == '76885630') {
						$asunto = "".json_decode(json_encode($obj->categoria))."";
						// a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>
						$email = "
						<p>Se&ntilde;ores<br><br></p>
						<p>Informamos a usted que con fecha <b>".date("d-m-Y", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>, a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b> se llev&oacute; a cabo un Control Operacional a uno de sus trabajadores que se desempe&ntilde;a como ".$evaCargo.".</p>
						<p>El resultado de esta Evaluaci&oacute;n fue de un <b>".floor($nota)." %</b> de cumplimiento, lo que representa un Nivel de Riesgo <b>".$nv."</b></p>
						<p align='center' style='color:red;'><b>".$alerta1." </b></p><p align='center'><b>".$alerta2."</b></p>
						<p>El respectivo Informe se encuentra disponible en el archivo adjunto a este mensaje. <!--o ingresando a la <a href='http://innoapsion.cl/terreno/login-eess/'>siguiente plataforma</a>, teniendo como credenciales de acceso, tanto para el usuario y contrase&ntilde;a, el Rut de su Empresa, sin puntos, guion ni d&iacute;gito verificador (Rut: 1234567)--></p>
						<p>Atte.<br>SAFCO LTDA.<br></p>
						";
						$headers = "From: SAFCO LTDA <no-reply@safco.cl> \r\n";
					}else{
					$asunto = "Evaluación de Desempeño";
					// a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>
					if($nota < 100) $email = "
					<p>Se&ntilde;ores<br><b>".json_decode(json_encode($eess))."</b><br></p>
					<p>Informamos a usted que con fecha <b>".date("d-m-Y", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>, a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b> se llev&oacute; a cabo una Evaluaci&oacute;n SEDECC a uno de sus trabajadores que se desempe&ntilde;a como ".$evaCargo.".</p>
					<p>El resultado de esta Evaluaci&oacute;n fue de un <b>".floor($nota)." %</b> de cumplimiento, lo que representa un Nivel de Riesgo <b>".$nv."</b></p>
					<p align='center' style='color:red;'><b>".$alerta1." </b></p><p align='center'><b>".$alerta2."</b></p>
					<p>El respectivo Informe se encuentra disponible en el archivo adjunto a este mensaje. <!--o ingresando a la <a href='http://innoapsion.cl/terreno/login-eess/'>siguiente plataforma</a>, teniendo como credenciales de acceso, tanto para el usuario y contrase&ntilde;a, el Rut de su Empresa, sin puntos, guion ni d&iacute;gito verificador (Rut: 1234567)--></p>
					<p>Las consultas referidas a la Evaluaci&oacute;n favor dirigirlas al Ejecutor de la misma y que se encuentra identificado en el Informe.</b>
					<p>Atte.<br>Innoapsion.<br></p>
					";
					//a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>
					else $email = "
					<p>Se&ntilde;ores<br><b>".json_decode(json_encode($eess))."</b><br></p>
					<p>Informamos a usted que con fecha <b>".date("d-m-Y", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>, a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b> se llev&oacute; a cabo una Evaluaci&oacute;n SEDECC a uno de sus trabajadores que se desempe&ntilde;a como ".$evaCargo.".</p>
					<p>El resultado de esta Evaluaci&oacute;n fue de un <b>".floor($nota)." %</b> de cumplimiento, lo que representa un Nivel de Riesgo <b>".$nv."</b></p>
					<p align='center' style='color:red;'><b>".$alerta1." </b></p><p align='center'><b>".$alerta2."</b></p>
					<p>El respectivo Informe se encuentra disponible en el archivo adjunto a este mensaje. <!--o ingresando a la <a href='http://innoapsion.cl/terreno/login-eess/'>siguiente plataforma</a>, teniendo como credenciales de acceso, tanto para el usuario y contrase&ntilde;a, el Rut de su Empresa, sin puntos, guion ni d&iacute;gito verificador (Rut: 1234567)--></p>
					<p>Las consultas referidas a la Evaluaci&oacute;n favor dirigirlas al Ejecutor de la misma y que se encuentra identificado en el Informe.</b>
					<p>Atte.<br>Innoapsion.<br></p>
					";
					$headers = "From: Sedecc <sedecc@innoapsion.cl> \r\n";
					}

					if ($eessR == '76885630')
					{
						$archivo = chunk_split(base64_encode(file_get_contents("http://innoapsion.cl/sedecc/index.php?r=evaluacion/pdfsafco&id=".$obj->timestamp)));
					}else{
						$idpdf = $obj->timestamp;
						$enlacepdf = "http://innoapsion.cl/sedecc/index.php?r=evaluacion/pdf&id=";
						$archivo = chunk_split(base64_encode(file_get_contents("http://innoapsion.cl/sedecc/index.php?r=evaluacion/pdf&id=".$obj->timestamp)));
					}

					$otrosemails = Yii::app()->db->createCommand("SELECT GROUP_CONCAT(ema_email) FROM min_email WHERE eess_rut = '".$obj->eess_rut."'")->queryScalar();

					$direccionesem = Yii::app()->db->createCommand("
					SELECT GROUP_CONCAT(tra_email) FROM `min_trabajador` WHERE
					CONCAT(tra_nombres,' ',tra_apellidos) = '".$obj->eva_nombres." ".$obj->eva_apellidos."' OR
					CONCAT(tra_nombres,' ',tra_apellidos) = '".$obj->eva_supervisor."' OR
					CONCAT(tra_nombres,' ',tra_apellidos) = '".$obj->eva_jefe_faena."' OR
					CONCAT(tra_nombres,' ',tra_apellidos) = '".$obj->eva_apr."' OR
					tra_rut = '".$obj->eva_evaluador."'
					")->queryScalar();

					$direccioneses = Yii::app()->db->createCommand("SELECT eess_email FROM `min_eess` WHERE eess_rut = '".$obj->eess_rut."'")->queryScalar();
					//$direccioneses="";
					$headers .= "Cc: ".$direccionesem.", ".$otrosemails." \r\n"; //
					//prueba de correo $headers .= "Cc:  \r\n"; //
					$headers .= "Bcc: ronnymunoz22@gmail.com, eduardoacevedo@innoapsion.cl, sebastian.carcamo398@gmail.com " . "\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
				    $headers .= "Content-Type: multipart/mixed; boundary=\"=A=G=R=O=\"\r\n\r\n";
				    $email_message = "--=A=G=R=O=\r\n";
				    $email_message .= "Content-type: text/html; charset=iso-8859-1\r\n";
				    $email_message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
					$email_message .= $email . "\r\n\r\n";
				    $email_message .= "--=A=G=R=O=\r\n";
				    $email_message .= "Content-Type: application/octet-stream; name=\"Evaluaciones.pdf\"\r\n";
				    $email_message .= "Content-Transfer-Encoding: base64\r\n";
				    $email_message .= "Content-Disposition: attachment; filename=\"Evaluaciones.pdf\"\r\n\r\n";
				    $email_message .= $archivo . "\r\n\r\n";
				    $email_message .= "--=A=G=R=O=\r\n";
				   mail($direccioneses, $asunto, $email_message, $headers); // Quitar email de prueba

					// Check de éxito
					echo json_encode("json_valido_sedecc");



					// LO QUE OCURRE DESDE ACÁ NO ES CRÍTICO, POR ESO SE HACE POST EL MENSAJE DE ÉXITO

					// Ingresar trabajador si no existe
					$c = Yii::app()->db->createCommand("SELECT COUNT(*) FROM min_trabajador
					WHERE eess_rut = '".$obj->eess_rut."' AND tra_rut = '".$obj->tra_rut."'")->queryScalar();
					if($c == 0){
						Yii::app()->db->createCommand("INSERT INTO min_trabajador(
							eess_rut,
							tra_rut,
							tra_nombres,
							tra_apellidos,
							tra_licencia_conducir
						)VALUES(
							'".$obj->eess_rut."',
							'".$obj->tra_rut."',
							'".$obj->eva_nombres."',
							'".$obj->eva_apellidos."',
							'".$obj->eva_licencia_conducir_clase."'
						)")->execute();
					}

					// Si existe actualizar corma y licencia de conducir
					if($obj->eva_vencimiento_corma != ''){
						Yii::app()->db->createCommand("UPDATE min_trabajador SET
							tra_vencimiento_corma = '".DateTime::createFromFormat('d-m-Y', $obj->eva_vencimiento_corma)->format('Y-m-d')."'
						WHERE tra_rut = '".$obj->tra_rut."'
						")->execute();
					}
					if($obj->eva_licencia_conducir_vencimiento != ''){
						Yii::app()->db->createCommand("UPDATE min_trabajador SET
							tra_vencimiento_licencia_conducir = '".DateTime::createFromFormat('d-m-Y', $obj->eva_licencia_conducir_vencimiento)->format('Y-m-d')."'
						WHERE tra_rut = '".$obj->tra_rut."'
						")->execute();
					}

					// Ingresar faena si no existe
					$c = Yii::app()->db->createCommand("SELECT COUNT(*) FROM min_faena
					WHERE fae_nombre = '".$obj->eva_faena."' AND eess_rut = '".$obj->eess_rut."'")->queryScalar();
					if($c == 0){
						Yii::app()->db->createCommand("INSERT INTO min_faena(
							fae_nombre,
							eess_rut,
							tipo
						) VALUES(
							'".$obj->eva_faena."',
							'".$obj->eess_rut."',
							'".$obj->eva_tipo_cosecha."'
						)")->execute();
					}

					// Cada vez que llegue una evaluación, se actualizarán los caché por ítem pendientes.
					$rows = Yii::app()->db->createCommand("SELECT eva_id, eva_tipo, tra_rut, eva_nombres, eva_apellidos, eva_cache_porcentaje FROM min_evaluacion WHERE eva_item_nombre_0 is NULL")->queryAll();
					for($i=0;$i<count($rows);$i++){
						// Obtener categorías de cada evaluación
						$categorias = Yii::app()->db->createCommand("SELECT DISTINCT tem_id FROM min_respuesta WHERE car_id = '".$rows[$i]['eva_tipo']."' ORDER BY tem_id")->queryAll();
						for($j=0;$j<count($categorias);$j++){
							// Asignar nombre de ítem
							Yii::app()->db->createCommand("UPDATE min_evaluacion SET eva_item_nombre_".$j." = '".$categorias[$j]['tem_id']."' WHERE eva_id = '".$rows[$i]['eva_id']."'")->execute();
							// Asignar nota
							$si = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) FROM min_evaluacion e LEFT JOIN min_respuesta r ON (e.eva_id = r.eva_id) WHERE r.eva_id = '".$rows[$i]['eva_id']."' AND r.tem_id = '".$categorias[$j]['tem_id']."' AND res_respuesta = 'si'")->queryScalar();
							$no = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) FROM min_evaluacion e LEFT JOIN min_respuesta r ON (e.eva_id = r.eva_id) WHERE r.eva_id = '".$rows[$i]['eva_id']."' AND r.tem_id = '".$categorias[$j]['tem_id']."' AND res_respuesta = 'no'")->queryScalar();
							if($si+$no>0){
								Yii::app()->db->createCommand("UPDATE min_evaluacion SET eva_item_nota_".$j." = '".floor(100*($si/($si+$no)))."' WHERE eva_id = '".$rows[$i]['eva_id']."'")->execute();
							}
						}
					}
				}
				else
				{
					echo json_encode("ERROR");
				}
			}
			else if($traeversion==$version_antigua)
			{
				if(isset($_POST['android'])){
					//Parche para variables no definidas, util para actualizaciones
					if(!isset($_POST['android'])) $_POST['android']='';
					if(!isset($_POST['timestamp'])) $_POST['timestamp']='';
					if(!isset($_POST['lat'])) $_POST['lat']='';
					if(!isset($_POST['lon'])) $_POST['lon']='';
					if(!isset($_POST['tim'])) $_POST['tim']='';
					if(!isset($_POST['categoria'])) $_POST['categoria']='';
					if(!isset($_POST['items'])) $_POST['items']='';
					if(!isset($_POST['respuestas'])) $_POST['respuestas']='';
					if(!isset($_POST['observaciones'])) $_POST['observaciones']='';
					if(!isset($_POST['fotos'])) $_POST['fotos']='';
					if(!isset($_POST['fechas'])) $_POST['fechas']='';

					if(!isset($_POST['eess_rut'])) $_POST['eess_rut']='';
					if(!isset($_POST['tra_rut'])) $_POST['tra_rut']='';
					if(!isset($_POST['eva_apellidos'])) $_POST['eva_apellidos']='';
					if(!isset($_POST['eva_nombres'])) $_POST['eva_nombres']='';
					if(!isset($_POST['eva_comuna'])) $_POST['eva_comuna']='';
					if(!isset($_POST['eva_fundo'])) $_POST['eva_fundo']='';
					if(!isset($_POST['eva_faena'])) $_POST['eva_faena']='';
					if(!isset($_POST['eva_jefe_faena'])) $_POST['eva_jefe_faena']='';
					if(!isset($_POST['eva_supervisor'])) $_POST['eva_supervisor']='';
					if(!isset($_POST['eva_apr'])) $_POST['eva_apr']='';
					if(!isset($_POST['eva_linea'])) $_POST['eva_linea']='';
					if(!isset($_POST['eva_vencimiento_corma'])) $_POST['eva_vencimiento_corma']='';

					if(!isset($_POST['eva_licencia_conducir_clase'])) $_POST['eva_licencia_conducir_clase']='';
					if(!isset($_POST['eva_licencia_conducir_vencimiento'])) $_POST['eva_licencia_conducir_vencimiento']='';

					if(!isset($_POST['eva_tipo_cosecha'])) $_POST['eva_tipo_cosecha']='';
					if(!isset($_POST['eva_patente'])) $_POST['eva_patente']='';
					if(!isset($_POST['eva_fecha'])) $_POST['eva_fecha']='';
					if(!isset($_POST['eva_hora'])) $_POST['eva_hora']='';
					if(!isset($_POST['eva_lugar'])) $_POST['eva_lugar']='';
					if(!isset($_POST['eva_general_observacion'])) $_POST['eva_general_observacion']='';
					if(!isset($_POST['eva_evaluador'])) $_POST['eva_evaluador']='';
					if(!isset($_POST['eva_cargo'])) $_POST['eva_cargo']='';
					//vesionamiento
					if(!isset($_POST['version_control'])) $_POST['version_control']='';
					if(!isset($_POST['modelo_dispo'])) $_POST['modelo_dispo']='';
					if(!isset($_POST['version_android'])) $_POST['version_android']='';
					if(!isset($_POST['critico'])) $_POST['critico']='';

					// Crear archivo con información recibida
					$myfile = fopen("recep/".$_POST['android'].$_POST['timestamp'], "w") or die("Unable to open file!");
					$content = json_encode(
						array(
							// Variables fijas
							'android'=>$_POST['android'],
							'timestamp'=>$_POST['timestamp'],
							'lat'=>$_POST['lat'],
							'lon'=>$_POST['lon'],
							'tim'=>$_POST['tim'],
							'categoria'=>utf8_encode($_POST['categoria']),
							'items'=>base64_encode($_POST['items']),
							'respuestas'=>base64_encode($_POST['respuestas']),
							'observaciones'=>base64_encode($_POST['observaciones']),
							'fotos'=>base64_encode($_POST['fotos']),
							'fechas'=>base64_encode($_POST['fechas']),

							// Campos
							"eess_rut"=>utf8_encode($_POST['eess_rut']),
							"tra_rut"=>utf8_encode($_POST['tra_rut']),
							"eva_apellidos"=>utf8_encode($_POST['eva_apellidos']),
							"eva_nombres"=>utf8_encode($_POST['eva_nombres']),
							"eva_comuna"=>utf8_encode($_POST['eva_comuna']),
							"eva_fundo"=>utf8_encode($_POST['eva_fundo']),
							"eva_faena"=>utf8_encode($_POST['eva_faena']),
							"eva_jefe_faena"=>utf8_encode($_POST['eva_jefe_faena']),
							"eva_supervisor"=>utf8_encode($_POST['eva_supervisor']),
							"eva_apr"=>utf8_encode($_POST['eva_apr']),
							"eva_linea"=>utf8_encode($_POST['eva_linea']),
							"eva_vencimiento_corma"=>utf8_encode($_POST['eva_vencimiento_corma']),

							"eva_licencia_conducir_clase"=>utf8_encode($_POST['eva_licencia_conducir_clase']),
							"eva_licencia_conducir_vencimiento"=>utf8_encode($_POST['eva_licencia_conducir_vencimiento']),

							"eva_tipo_cosecha"=>utf8_encode($_POST['eva_tipo_cosecha']),
							"eva_patente"=>utf8_encode($_POST['eva_patente']),
							"eva_fecha"=>utf8_encode($_POST['eva_fecha']),
							"eva_hora"=>utf8_encode($_POST['eva_hora']),
							"eva_lugar"=>utf8_encode($_POST['eva_lugar']),
							"eva_general_observacion"=>utf8_encode($_POST['eva_general_observacion']),

							// Otros
							"eva_general_foto"=>utf8_encode($_POST['general_foto']),
							"eva_general_fecha"=>utf8_encode($_POST['general_fecha']),
							"eva_evaluador"=>utf8_encode($_POST['eva_evaluador']),
							"eva_cargo"=>utf8_encode($_POST['eva_cargo']),

							//versiones
							"version_control"=>utf8_encode($_POST['version_control']),
							"modelo_dispo"=>utf8_encode($_POST['modelo_dispo']),
							"version_android"=>utf8_encode($_POST['version_android']),

						)
					);
					fwrite($myfile, $content);
					fclose($myfile);

					// Una vez guardado el archivo generar inserción SQL
					$myfile = fopen("recep/".$_POST['android'].$_POST['timestamp'], "r") or die("Unable to open file!");
					$content = fread($myfile,filesize("recep/".$_POST['android'].$_POST['timestamp']));
					fclose($myfile);

					//echo $content.'<hr>';

					$obj = json_decode($content);

					// Procesar info
					$guardaversion = "INSERT INTO min_version_uso(
						id,
						fecha_envio,
						id_eva,
						id_android,
						nombre_evaluador,
						eess_rut,
						modelo_android,
						version_android,
						version_app,
						tipo_ingreso
					) VALUES (
						NULL,
						NULL,
						'".$obj->timestamp."',
						'".$obj->android."',
						'".$obj->eva_evaluador."',
						'".$obj->eess_rut."',
						'".$obj->modelo_dispo."',
						'".$obj->version_android."',
						'".$obj->version_control."',
						'".$version_antigua."'
					);";


					$sql = "INSERT INTO min_evaluacion(
						eva_id,
						eva_creado,
						eva_tipo,
						eess_rut,
						tra_rut,
						eva_apellidos,
						eva_nombres,
						eva_fecha_evaluacion,
						eva_fundo,
						eva_comuna,
						eva_jefe_faena,
						eva_faena,
						eva_supervisor,
						eva_apr,
						eva_geo_x,
						eva_geo_y,
						eva_linea,
						eva_vencimiento_corma,
						eva_licencia_conducir_clase,
						eva_licencia_conducir_vencimiento,
						eva_tipo_cosecha,
						eva_patente,
						eva_general_observacion,
						eva_general_foto,
						eva_general_fecha,
						eva_evaluador,
						eva_cargo
					) VALUES(
						".$obj->timestamp.",
						null,
						'".$obj->categoria."',
						'".$obj->eess_rut."',
						'".$obj->tra_rut."',
						'".$obj->eva_apellidos."',
						'".$obj->eva_nombres."',
						'".date("Y-m-d H:i:s",($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."',
						'".$obj->eva_fundo."',
						'".$obj->eva_comuna."',
						'".$obj->eva_jefe_faena."',
						'".$obj->eva_faena."',
						'".$obj->eva_supervisor."',
						'".$obj->eva_apr."',
						".$obj->lat.",
						".$obj->lon.",
						'".$obj->eva_linea."',
						'".$obj->eva_vencimiento_corma."',
						'".$obj->eva_licencia_conducir_clase."',
						'".$obj->eva_licencia_conducir_vencimiento."',
						'".$obj->eva_tipo_cosecha."',
						'".$obj->eva_patente."',
						'".$obj->eva_general_observacion."',
						'".$obj->eva_general_foto."',
						'".$obj->eva_general_fecha."',
						'".$obj->eva_evaluador."',
						'".$obj->eva_cargo."'
					);";

					$myfile = fopen("recep/sql".$_POST['android'].$_POST['timestamp'], "w") or die("Unable to open file!");
					fwrite($myfile, $sql);
					fclose($myfile);
					Yii::app()->db->createCommand($guardaversion)->execute();
					Yii::app()->db->createCommand($sql)->execute();

					$items = json_decode(utf8_encode(base64_decode($obj->items)));
					$respuestas = json_decode(base64_decode($obj->respuestas));
					$observaciones = json_decode(base64_decode($obj->observaciones));
					$fotos = json_decode(base64_decode($obj->fotos));
					$fechas = json_decode(base64_decode($obj->fechas));

					for($i=0;$i<count($items);$i++){
						$seguimiento = 1;
						if($respuestas[$i] == 'no') $seguimiento = 0;
						$sql = "INSERT INTO min_respuesta(
							res_enunciado,
							res_respuesta,
							res_critico,
							res_ponderacion,
							pre_id,
							car_id,
							tem_id,
							res_observacion,
							res_foto,
							eva_id,
							res_seguimiento,
							res_plazo
						) VALUES (
							'".$items[$i]->pre_enunciado."',
							'".$respuestas[$i]."',
							'".$items[$i]->critico."',
							'".$items[$i]->pre_ponderacion."',
							'".$items[$i]->pre_id."',
							'".$obj->categoria."',
							'".$items[$i]->tem_id."',
							'".urldecode($observaciones[$i])."',
							'".$fotos[$i]."',
							".$obj->timestamp.",
							'".$seguimiento."',
							'".$fechas[$i]."'
						);";
						Yii::app()->db->createCommand($sql)->execute();
					}

					// Actualizar porcentaje cache
				$limit1 = Yii::app()->params['riesgoalto'];
				$limit2 = Yii::app()->params['riesgomedio'];
				$id = $obj->timestamp;
				$sql = "SELECT eva_id FROM min_respuesta WHERE eva_id = ".$id." GROUP BY eva_id";
				$categorias = Yii::app()->db->createCommand($sql)->query()->readAll();
				$nota = 0;
				$todosna = 0;

					$si = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) as n FROM min_respuesta WHERE eva_id = ".$id."  AND res_respuesta = 'si' GROUP BY res_respuesta")->queryScalar();
					$no = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) as n FROM min_respuesta WHERE eva_id = ".$id."  AND res_respuesta = 'no' GROUP BY res_respuesta")->queryScalar();
					$na = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) as n FROM min_respuesta WHERE eva_id = ".$id."  AND res_respuesta = 'n/a' GROUP BY res_respuesta")->queryScalar();
					if($si == '') $si = 0;
					if($no == '') $no = 0;
					if($na == '') $na = 0;
					if(($si + $no) > 0) $r = 100*($si / ($si + $no)); else $r = 0;
					$nota=floor($r);
					$ref = floor($nota);


					// Actualizar porcentaje cache y correlativo evaluador
					$corr = Yii::app()->db->createCommand("SELECT MAX(eva_evaluador_correlativo)+1 FROM min_evaluacion WHERE eva_evaluador = '".$obj->eva_evaluador."'")->queryScalar();
					// Obtener cargo desde aplicación, si viene vacío, sacar de mantenedor de trabajadores.
					$evaCargo = $obj->eva_cargo;
					if($evaCargo=='') $evaCargo = Yii::app()->db->createCommand("SELECT car_descripcion FROM min_evaluacion as E JOIN min_trabajador as T ON(E.tra_rut = T.tra_rut) JOIN min_cargo as C ON(T.car_id = C.car_id) where eva_id = '".$obj->timestamp."'")->queryScalar();
					$sql="UPDATE min_evaluacion SET eva_cache_porcentaje = '".$ref."', eva_evaluador_correlativo = '".$corr."' WHERE eva_id = ".$obj->timestamp;
					Yii::app()->db->createCommand($sql)->execute();

					// Enviar correos con la evaluación
					 $limit1 = Yii::app()->params['riesgoalto'];
					$limit2 = Yii::app()->params['riesgomedio'];
					//$nv = 'Bajo';
					//if($ref < Yii::app()->params['riesgomedio']) $nv = 'Medio';
					//if($ref < Yii::app()->params['riesgoalto']) $nv = 'Alto';
					if($ref>=0 && $ref<=$limit1) $nv = 'Alto';
					if($ref>$limit1 && $ref<=$limit2) $nv = 'Medio';
					if($ref>$limit2 && $ref<=100) $nv = 'Bajo';

					$ver= Yii::app()->db->createCommand("SELECT res_critico,res_respuesta FROM min_respuesta WHERE eva_id = ".$id)->query()->readAll();
					for($s=0;$s<count($ver);$s++)
					{
						if($ver[$s]['res_critico'] == 'si'&&$ver[$s]['res_respuesta']=='no'){
							$alerta1 = '¡Precaución!: Trabajador ha incumplido una pregunta considerada crítica.';//';
							$alerta2 = ' Se recomienda Identificar las desviaciones, implementar las medidas correctivas y un realizar un seguimiento sistemático.';//';
							$tienecriticosi='si';

						}else if($ver[$s]['res_critico'] == 'si'&&$ver[$s]['res_respuesta']=='si'){
							$alerta1 = '';
							$alerta2 = '';
							$tienecriticono = 'no';
						}else{
							$alerta1 = '';
							$alerta2 = '';
							$tienecritico = 'no';
							$tienecriticosi = 'no';
							$tienecriticono = 'no';
						}
					}
					//verificamos si cumple con el porcentaje minimo de respuesta
					$verpromedio = Yii::app()->db->createCommand("SELECT eva_cache_porcentaje From min_evaluacion WHERE eva_id=".$id)->query()->readAll();
					for($t=0;$t<count($verpromedio);$t++)
					{
						$notapromedio = number_format(floor($verpromedio[$t]['eva_cache_porcentaje']));
						if($notapromedio>85 and $tienecriticosi=='si'){
							$alerta1 = '¡Precaución!: Trabajador ha incumplido una pregunta considerada crítica.';//';
							$alerta2 = 'Se recomienda Identificar las desviaciones, implementar las medidas correctivas y un realizar un seguimiento sistemático.';//';

						}elseif($notapromedio<=85 or $tienecriticosi=='si'){
							$alerta1 = ' ¡ALERTA! Trabajador en Nivel de Riesgo ALTO';//';
							$alerta2 = ' Se recomienda detener los trabajos y reinstruir al trabajador.';//';
						}elseif($notapromedio>85 and $tienecriticono=='no'){
							$alerta1 = '';
							$alerta2 = '';
						}elseif($tienecriticosi=='si'){
							$alerta1 = ' ¡Precaución!: Trabajador ha incumplido una pregunta considerada crítica.';//';
							$alerta2 = ' Se recomienda Identificar las desviaciones, implementar las medidas correctivas y un realizar un seguimiento sistemático.';//';
						}elseif($tienecritico=='no'){
							$alerta1 = '';//';
							$alerta2 = '';//';
						}
					}
					//cambios4
					// Enviar email a medio mundo

					$eess = Yii::app()->db->createCommand("SELECT eess_nombre_corto FROM min_eess WHERE eess_rut = '".$obj->eess_rut."'")->queryScalar();
				$eessR = Yii::app()->db->createCommand("SELECT eess_rut FROM min_eess WHERE eess_rut = '".$obj->eess_rut."'")->queryScalar();
				if ($eessR == '76885630') {
					$asunto = "".json_decode(json_encode($obj->categoria))."";
					// a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>
					$email = "
					<p>Se&ntilde;ores<br><br></p>
					<p>Informamos a usted que con fecha <b>".date("d-m-Y", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>, a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b> se llev&oacute; a cabo un Control Operacional a uno de sus trabajadores que se desempe&ntilde;a como ".$evaCargo.".</p>
					<p>El resultado de esta Evaluaci&oacute;n fue de un <b>".floor($nota)." %</b> de cumplimiento, lo que representa un Nivel de Riesgo <b>".$nv."</b></p>
					<p align='center' style='color:red;'><b>".$alerta1." </b></p><p align='center'><b>".$alerta2."</b></p>
					<p>El respectivo Informe se encuentra disponible en el archivo adjunto a este mensaje. <!--o ingresando a la <a href='http://innoapsion.cl/terreno/login-eess/'>siguiente plataforma</a>, teniendo como credenciales de acceso, tanto para el usuario y contrase&ntilde;a, el Rut de su Empresa, sin puntos, guion ni d&iacute;gito verificador (Rut: 1234567)--></p>
					<p>Atte.<br>SAFCO LTDA.<br></p>
					";
					$headers = "From: SAFCO LTDA <no-reply@safco.cl> \r\n";
				}else{
				$asunto = "Evaluación de Desempeño";
				// a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>
				if($nota < 100) $email = "
				<p>Se&ntilde;ores<br><b>".json_decode(json_encode($eess))."</b><br></p>
				<p>Informamos a usted que con fecha <b>".date("d-m-Y", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>, a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b> se llev&oacute; a cabo una Evaluaci&oacute;n SEDECC a uno de sus trabajadores que se desempe&ntilde;a como ".$evaCargo.".</p>
				<p>El resultado de esta Evaluaci&oacute;n fue de un <b>".floor($nota)." %</b> de cumplimiento, lo que representa un Nivel de Riesgo <b>".$nv."</b></p>
				<p align='center' style='color:red;'><b>".$alerta1." </b></p><p align='center'><b>".$alerta2."</b></p>
				<p>El respectivo Informe se encuentra disponible en el archivo adjunto a este mensaje. <!--o ingresando a la <a href='http://innoapsion.cl/terreno/login-eess/'>siguiente plataforma</a>, teniendo como credenciales de acceso, tanto para el usuario y contrase&ntilde;a, el Rut de su Empresa, sin puntos, guion ni d&iacute;gito verificador (Rut: 1234567)--></p>
				<p>Las consultas referidas a la Evaluaci&oacute;n favor dirigirlas al Ejecutor de la misma y que se encuentra identificado en el Informe.</b>
				<p>Atte.<br>Innoapsion.<br></p>
				";
				//a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>
				else $email = "
				<p>Se&ntilde;ores<br><b>".json_decode(json_encode($eess))."</b><br></p>
				<p>Informamos a usted que con fecha <b>".date("d-m-Y", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>, a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b> se llev&oacute; a cabo una Evaluaci&oacute;n SEDECC a uno de sus trabajadores que se desempe&ntilde;a como ".$evaCargo.".</p>
				<p>El resultado de esta Evaluaci&oacute;n fue de un <b>".floor($nota)." %</b> de cumplimiento, lo que representa un Nivel de Riesgo <b>".$nv."</b></p>
				<p align='center' style='color:red;'><b>".$alerta1." </b></p><p align='center'><b>".$alerta2."</b></p>
				<p>El respectivo Informe se encuentra disponible en el archivo adjunto a este mensaje. <!--o ingresando a la <a href='http://innoapsion.cl/terreno/login-eess/'>siguiente plataforma</a>, teniendo como credenciales de acceso, tanto para el usuario y contrase&ntilde;a, el Rut de su Empresa, sin puntos, guion ni d&iacute;gito verificador (Rut: 1234567)--></p>
				<p>Las consultas referidas a la Evaluaci&oacute;n favor dirigirlas al Ejecutor de la misma y que se encuentra identificado en el Informe.</b>
				<p>Atte.<br>Innoapsion.<br></p>
				";
				$headers = "From: Sedecc <sedecc@innoapsion.cl> \r\n";
				}

					if ($eessR == '76885630') {
						$archivo = chunk_split(base64_encode(file_get_contents("http://innoapsion.cl/sedecc/index.php?r=evaluacion/pdfsafco&id=".$obj->timestamp)));
					}else{
						$archivo = chunk_split(base64_encode(file_get_contents("http://innoapsion.cl/sedecc/index.php?r=evaluacion/pdf&id=".$obj->timestamp)));
					}

					//  $otrosemails = Yii::app()->db->createCommand("SELECT GROUP_CONCAT(ema_email) FROM min_email WHERE eess_rut = '".$obj->eess_rut."'")->queryScalar();

					$direccionesem = Yii::app()->db->createCommand("
					SELECT GROUP_CONCAT(tra_email) FROM `min_trabajador` WHERE
					CONCAT(tra_nombres,' ',tra_apellidos) = '".$obj->eva_nombres." ".$obj->eva_apellidos."' OR
					CONCAT(tra_nombres,' ',tra_apellidos) = '".$obj->eva_supervisor."' OR
					CONCAT(tra_nombres,' ',tra_apellidos) = '".$obj->eva_jefe_faena."' OR
					CONCAT(tra_nombres,' ',tra_apellidos) = '".$obj->eva_apr."' OR
					tra_rut = '".$obj->eva_evaluador."'
					")->queryScalar();

					$direccioneses = Yii::app()->db->createCommand("SELECT eess_email FROM `min_eess` WHERE eess_rut = '".$obj->eess_rut."'")->queryScalar();

					$headers .= "Bcc: ronnymunoz22@gmail.com, eduardoacevedo@innoapsion.cl, sebastian.carcamo398@gmail.com " . "\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
				    $headers .= "Content-Type: multipart/mixed; boundary=\"=A=G=R=O=\"\r\n\r\n";
				    $email_message = "--=A=G=R=O=\r\n";
				    $email_message .= "Content-type: text/html; charset=iso-8859-1\r\n";
				    $email_message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
					$email_message .= $email . "\r\n\r\n";
				    $email_message .= "--=A=G=R=O=\r\n";
				    $email_message .= "Content-Type: application/octet-stream; name=\"Evaluaciones.pdf\"\r\n";
				    $email_message .= "Content-Transfer-Encoding: base64\r\n";
				    $email_message .= "Content-Disposition: attachment; filename=\"Evaluaciones.pdf\"\r\n\r\n";
				  //comentado para probar  $email_message .= $archivo . "\r\n\r\n";
				    $email_message .= "--=A=G=R=O=\r\n";
				    mail( $direccioneses, $asunto, $email_message, $headers); // Quitar email de prueba
					//$direccioneses,
					// Check de éxito
					echo json_encode("json_valido_sedecc");



					// LO QUE OCURRE DESDE ACÁ NO ES CRÍTICO, POR ESO SE HACE POST EL MENSAJE DE ÉXITO

					// Ingresar trabajador si no existe
					$c = Yii::app()->db->createCommand("SELECT COUNT(*) FROM min_trabajador
					WHERE eess_rut = '".$obj->eess_rut."' AND tra_rut = '".$obj->tra_rut."'")->queryScalar();
					if($c == 0){
						Yii::app()->db->createCommand("INSERT INTO min_trabajador(
							eess_rut,
							tra_rut,
							tra_nombres,
							tra_apellidos,
							tra_licencia_conducir
						)VALUES(
							'".$obj->eess_rut."',
							'".$obj->tra_rut."',
							'".$obj->eva_nombres."',
							'".$obj->eva_apellidos."',
							'".$obj->eva_licencia_conducir_clase."'
						)")->execute();
					}

					// Si existe actualizar corma y licencia de conducir
					if($obj->eva_vencimiento_corma != ''){
						Yii::app()->db->createCommand("UPDATE min_trabajador SET
							tra_vencimiento_corma = '".DateTime::createFromFormat('d-m-Y', $obj->eva_vencimiento_corma)->format('Y-m-d')."'
						WHERE tra_rut = '".$obj->tra_rut."'
						")->execute();
					}
					if($obj->eva_licencia_conducir_vencimiento != ''){
						Yii::app()->db->createCommand("UPDATE min_trabajador SET
							tra_vencimiento_licencia_conducir = '".DateTime::createFromFormat('d-m-Y', $obj->eva_licencia_conducir_vencimiento)->format('Y-m-d')."'
						WHERE tra_rut = '".$obj->tra_rut."'
						")->execute();
					}

					// Ingresar faena si no existe
					$c = Yii::app()->db->createCommand("SELECT COUNT(*) FROM min_faena
					WHERE fae_nombre = '".$obj->eva_faena."' AND eess_rut = '".$obj->eess_rut."'")->queryScalar();
					if($c == 0){
						Yii::app()->db->createCommand("INSERT INTO min_faena(
							fae_nombre,
							eess_rut,
							tipo
						) VALUES(
							'".$obj->eva_faena."',
							'".$obj->eess_rut."',
							'".$obj->eva_tipo_cosecha."'
						)")->execute();
					}

					// Cada vez que llegue una evaluación, se actualizarán los caché por ítem pendientes.
					$rows = Yii::app()->db->createCommand("SELECT eva_id, eva_tipo, tra_rut, eva_nombres, eva_apellidos, eva_cache_porcentaje FROM min_evaluacion WHERE eva_item_nombre_0 is NULL")->queryAll();
					for($i=0;$i<count($rows);$i++){
						// Obtener categorías de cada evaluación
						$categorias = Yii::app()->db->createCommand("SELECT DISTINCT tem_id FROM min_respuesta WHERE car_id = '".$rows[$i]['eva_tipo']."' ORDER BY tem_id")->queryAll();
						for($j=0;$j<count($categorias);$j++){
							// Asignar nombre de ítem
							Yii::app()->db->createCommand("UPDATE min_evaluacion SET eva_item_nombre_".$j." = '".$categorias[$j]['tem_id']."' WHERE eva_id = '".$rows[$i]['eva_id']."'")->execute();
							// Asignar nota
							$si = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) FROM min_evaluacion e LEFT JOIN min_respuesta r ON (e.eva_id = r.eva_id) WHERE r.eva_id = '".$rows[$i]['eva_id']."' AND r.tem_id = '".$categorias[$j]['tem_id']."' AND res_respuesta = 'si'")->queryScalar();
							$no = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) FROM min_evaluacion e LEFT JOIN min_respuesta r ON (e.eva_id = r.eva_id) WHERE r.eva_id = '".$rows[$i]['eva_id']."' AND r.tem_id = '".$categorias[$j]['tem_id']."' AND res_respuesta = 'no'")->queryScalar();
							if($si+$no>0){
								Yii::app()->db->createCommand("UPDATE min_evaluacion SET eva_item_nota_".$j." = '".floor(100*($si/($si+$no)))."' WHERE eva_id = '".$rows[$i]['eva_id']."'")->execute();
							}
						}
					}
				}
				else{
					echo json_encode("ERROR");
				}
			}
			else if($traeversion==$version_nueva)
			{
				if(isset($_POST['android']))
				{
					//Parche para variables no definidas, util para actualizaciones
					if(!isset($_POST['android'])) $_POST['android']='';
					if(!isset($_POST['timestamp'])) $_POST['timestamp']='';
					if(!isset($_POST['lat'])) $_POST['lat']='';
					if(!isset($_POST['lon'])) $_POST['lon']='';
					if(!isset($_POST['tim'])) $_POST['tim']='';
					if(!isset($_POST['categoria'])) $_POST['categoria']='';
					if(!isset($_POST['items'])) $_POST['items']='';
					if(!isset($_POST['respuestas'])) $_POST['respuestas']='';
					if(!isset($_POST['observaciones'])) $_POST['observaciones']='';
					if(!isset($_POST['fotos'])) $_POST['fotos']='';
					if(!isset($_POST['fechas'])) $_POST['fechas']='';

					if(!isset($_POST['eess_rut'])) $_POST['eess_rut']='';
					if(!isset($_POST['tra_rut'])) $_POST['tra_rut']='';
					if(!isset($_POST['eva_apellidos'])) $_POST['eva_apellidos']='';
					if(!isset($_POST['eva_nombres'])) $_POST['eva_nombres']='';
					if(!isset($_POST['eva_comuna'])) $_POST['eva_comuna']='';
					if(!isset($_POST['eva_fundo'])) $_POST['eva_fundo']='';
					if(!isset($_POST['eva_faena'])) $_POST['eva_faena']='';
					if(!isset($_POST['eva_jefe_faena'])) $_POST['eva_jefe_faena']='';
					if(!isset($_POST['eva_supervisor'])) $_POST['eva_supervisor']='';
					if(!isset($_POST['eva_apr'])) $_POST['eva_apr']='';
					if(!isset($_POST['eva_linea'])) $_POST['eva_linea']='';
					if(!isset($_POST['eva_vencimiento_corma'])) $_POST['eva_vencimiento_corma']='';

					if(!isset($_POST['eva_licencia_conducir_clase'])) $_POST['eva_licencia_conducir_clase']='';
					if(!isset($_POST['eva_licencia_conducir_vencimiento'])) $_POST['eva_licencia_conducir_vencimiento']='';

					if(!isset($_POST['eva_tipo_cosecha'])) $_POST['eva_tipo_cosecha']='';
					if(!isset($_POST['eva_patente'])) $_POST['eva_patente']='';
					if(!isset($_POST['eva_fecha'])) $_POST['eva_fecha']='';
					if(!isset($_POST['eva_hora'])) $_POST['eva_hora']='';
					if(!isset($_POST['eva_lugar'])) $_POST['eva_lugar']='';
					if(!isset($_POST['eva_general_observacion'])) $_POST['eva_general_observacion']='';
					if(!isset($_POST['eva_evaluador'])) $_POST['eva_evaluador']='';
					if(!isset($_POST['eva_cargo'])) $_POST['eva_cargo']='';
					//vesionamiento
					if(!isset($_POST['version_control'])) $_POST['version_control']='';
					if(!isset($_POST['modelo_dispo'])) $_POST['modelo_dispo']='';
					if(!isset($_POST['version_android'])) $_POST['version_android']='';
					if(!isset($_POST['critico'])) $_POST['critico']='';
					//agregamos valores desde la app

					if(!isset($_POST['eq_tipo'])) $_POST['eq_tipo']='';
					if(!isset($_POST['eq_codigo'])) $_POST['eq_codigo']='';
					if(!isset($_POST['eq_maquina'])) $_POST['eq_maquina']='';
					if(!isset($_POST['eq_marca'])) $_POST['eq_marca']='';
					if(!isset($_POST['eq_modelo'])) $_POST['eq_modelo']='';
					if(!isset($_POST['eva_operador'])) $_POST['eva_operador']='';
					if(!isset($_POST['eva_horometro'])) $_POST['eva_horometro']='';
					if(!isset($_POST['eva_ot'])) $_POST['eva_ot']='';
					if(!isset($_POST['juntados'])) $_POST['juntados']='';

					//agregamos los valores a tomar de INSTALACIONES
					if(!isset($_POST['eva_cod_faena'])) $_POST['eva_cod_faena']='';
					if(!isset($_POST['eva_gerente_general'])) $_POST['eva_gerente_general']='';
					if(!isset($_POST['eva_num_trabajadores'])) $_POST['eva_num_trabajadores']='';
					if(!isset($_POST['eva_gerente_operacion'])) $_POST['eva_gerente_operacion']='';
					if(!isset($_POST['eva_administrador'])) $_POST['eva_administrador']='';
					if(!isset($_POST['eva_num_personas'])) $_POST['eva_num_personas']='';
					// Crear archivo con información recibida
					$myfile = fopen("recep/".$_POST['android'].$_POST['timestamp'], "w") or die("Unable to open file!");
					$content = json_encode(
						array(
							// Variables fijas
							'android'=>$_POST['android'],
							'timestamp'=>$_POST['timestamp'],
							'lat'=>$_POST['lat'],
							'lon'=>$_POST['lon'],
							'tim'=>$_POST['tim'],
							'categoria'=>utf8_encode($_POST['categoria']),
							'items'=>base64_encode($_POST['items']),
							'respuestas'=>base64_encode($_POST['respuestas']),
							'observaciones'=>base64_encode($_POST['observaciones']),
							'fotos'=>base64_encode($_POST['fotos']),
							'fechas'=>base64_encode($_POST['fechas']),

							// Campos
							"eess_rut"=>utf8_encode($_POST['eess_rut']),
							"tra_rut"=>utf8_encode($_POST['tra_rut']),
							"eva_apellidos"=>utf8_encode($_POST['eva_apellidos']),
							"eva_nombres"=>utf8_encode($_POST['eva_nombres']),
							"eva_comuna"=>utf8_encode($_POST['eva_comuna']),
							"eva_fundo"=>utf8_encode($_POST['eva_fundo']),
							"eva_faena"=>utf8_encode($_POST['eva_faena']),
							"eva_jefe_faena"=>utf8_encode($_POST['eva_jefe_faena']),
							"eva_supervisor"=>utf8_encode($_POST['eva_supervisor']),
							"eva_apr"=>utf8_encode($_POST['eva_apr']),
							"eva_linea"=>utf8_encode($_POST['eva_linea']),
							"eva_vencimiento_corma"=>utf8_encode($_POST['eva_vencimiento_corma']),

							"eva_licencia_conducir_clase"=>utf8_encode($_POST['eva_licencia_conducir_clase']),
							"eva_licencia_conducir_vencimiento"=>utf8_encode($_POST['eva_licencia_conducir_vencimiento']),

							"eva_tipo_cosecha"=>utf8_encode($_POST['eva_tipo_cosecha']),
							"eva_patente"=>utf8_encode($_POST['eva_patente']),
							"eva_fecha"=>utf8_encode($_POST['eva_fecha']),
							"eva_hora"=>utf8_encode($_POST['eva_hora']),
							"eva_lugar"=>utf8_encode($_POST['eva_lugar']),
							"eva_general_observacion"=>utf8_encode($_POST['eva_general_observacion']),

							// Otros
							"eva_general_foto"=>utf8_encode($_POST['general_foto']),
							"eva_general_fecha"=>utf8_encode($_POST['general_fecha']),
							"eva_evaluador"=>utf8_encode($_POST['eva_evaluador']),
							"eva_cargo"=>utf8_encode($_POST['eva_cargo']),

							//versiones
							"version_control"=>utf8_encode($_POST['version_control']),
							"modelo_dispo"=>utf8_encode($_POST['modelo_dispo']),
							"version_android"=>utf8_encode($_POST['version_android']),

							// Otros variables
							"eq_tipo"=>utf8_encode($_POST['eq_tipo']),
							"eq_codigo"=>utf8_encode($_POST['eq_codigo']),
							"eq_maquina"=>utf8_encode($_POST['eq_maquina']),
							"eq_marca"=>utf8_encode($_POST['eq_marca']),
							"eq_modelo"=>utf8_encode($_POST['eq_modelo']),
							"eva_operador"=>utf8_encode($_POST['eva_operador']),
							"eva_horometro"=>utf8_encode($_POST['eva_horometro']),
							"eva_ot"=>utf8_encode($_POST['eva_ot']),
							"tipo_checklist_app"=>utf8_encode($_POST['juntados']),

							//variables de INSTALACIONES
							//agregamos los valores a tomar de INSTALACIONES
							"eva_cod_faena"=>utf8_encode($_POST['eva_cod_faena']),
							"eva_gerente_general"=>utf8_encode($_POST['eva_gerente_general']),
							"eva_num_trabajadores"=>utf8_encode($_POST['eva_num_trabajadores']),
							"eva_gerente_operacion"=>utf8_encode($_POST['eva_gerente_operacion']),
							"eva_administrador"=>utf8_encode($_POST['eva_administrador']),
							"eva_num_personas"=>utf8_encode($_POST['eva_num_personas']),

						)
					);
					fwrite($myfile, $content);
					fclose($myfile);

					// Una vez guardado el archivo generar inserción SQL
					$myfile = fopen("recep/".$_POST['android'].$_POST['timestamp'], "r") or die("Unable to open file!");
					$content = fread($myfile,filesize("recep/".$_POST['android'].$_POST['timestamp']));
					fclose($myfile);

					//echo $content.'<hr>';

					$obj = json_decode($content);
						$tipo_checklist = $obj->tipo_checklist_app;
					// Procesar info
					$guardaversion = "INSERT INTO min_version_uso(
						id,
						fecha_envio,
						id_eva,
						id_android,
						nombre_evaluador,
						eess_rut,
						modelo_android,
						version_android,
						version_app,
						tipo_ingreso,
						tipo_checklist
					) VALUES (
						NULL,
						NULL,
						'".$obj->timestamp."',
						'".$obj->android."',
						'".$obj->eva_evaluador."',
						'".$obj->eess_rut."',
						'".$obj->modelo_dispo."',
						'".$obj->version_android."',
						'".$obj->version_control."',
						'".$version_nueva."',
						'".$tipo_checklist."'
					);";
					//agregamos el tipo de checklist que se incluye para indicar si es eqiupos trabajadores o instalacion

				//  $otrosemails = Yii::app()->db->createCommand("SELECT GROUP_CONCAT(ema_email) FROM min_email WHERE eess_rut = '".$obj->eess_rut."'")->queryScalar();
				if($tipo_checklist=='trabajadores')
				{
					//realizar en caso de checklist trabaadores
					$sql = "INSERT INTO min_evaluacion(
						eva_id,
						eva_creado,
						eva_tipo,
						eess_rut,
						tra_rut,
						eva_apellidos,
						eva_nombres,
						eva_fecha_evaluacion,
						eva_fundo,
						eva_comuna,
						eva_jefe_faena,
						eva_faena,
						eva_supervisor,
						eva_apr,
						eva_geo_x,
						eva_geo_y,
						eva_campos_modificados,
						eva_linea,
						eva_vencimiento_corma,
						eva_licencia_conducir_clase,
						eva_licencia_conducir_vencimiento,
						eva_tipo_cosecha,
						eva_patente,
						eva_general_observacion,
						eva_general_foto,
						eva_general_fecha,
						eva_evaluador,
						eva_cargo
					) VALUES(
						".$obj->timestamp.",
						null,
						'".$obj->categoria."',
						'".$obj->eess_rut."',
						'".$obj->tra_rut."',
						'".$obj->eva_apellidos."',
						'".$obj->eva_nombres."',
						'".date("Y-m-d H:i:s",($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."',
						'".$obj->eva_fundo."',
						'".$obj->eva_comuna."',
						'".$obj->eva_jefe_faena."',
						'".$obj->eva_faena."',
						'".$obj->eva_supervisor."',
						'".$obj->eva_apr."',
						".$obj->lat.",
						".$obj->lon.",
						'".$tipo_checklist."',
						'".$obj->eva_linea."',
						'".$obj->eva_vencimiento_corma."',
						'".$obj->eva_licencia_conducir_clase."',
						'".$obj->eva_licencia_conducir_vencimiento."',
						'".$obj->eva_tipo_cosecha."',
						'".$obj->eva_patente."',
						'".$obj->eva_general_observacion."',
						'".$obj->eva_general_foto."',
						'".$obj->eva_general_fecha."',
						'".$obj->eva_evaluador."',
						'".$obj->eva_cargo."'
					);";
					$myfile = fopen("recep/sql".$_POST['android'].$_POST['timestamp'], "w") or die("Unable to open file!");
					fwrite($myfile, $sql);
					fclose($myfile);
					Yii::app()->db->createCommand($guardaversion)->execute();
					Yii::app()->db->createCommand($sql)->execute();
					$items = json_decode(utf8_encode(base64_decode($obj->items)));
					$respuestas = json_decode(base64_decode($obj->respuestas));
					$observaciones = json_decode(base64_decode($obj->observaciones));
					$fotos = json_decode(base64_decode($obj->fotos));
					$fechas = json_decode(base64_decode($obj->fechas));

					for($i=0;$i<count($items);$i++){
							$seguimiento = 1;
							if($respuestas[$i] == 'no') $seguimiento = 0;

							$sql = "INSERT INTO min_respuesta(
								res_enunciado,
								res_respuesta,
								res_critico,
								res_ponderacion,
								pre_id,
								car_id,
								tem_id,
								res_observacion,
								res_foto,
								eva_id,
								res_seguimiento,
								res_plazo
							) VALUES (
								'".$items[$i]->pre_enunciado."',
								'".$respuestas[$i]."',
								'".$items[$i]->critico."',
								'".$items[$i]->pre_ponderacion."',
								'".$items[$i]->pre_id."',
								'".$obj->categoria."',
								'".$items[$i]->tem_id."',
								'".urldecode($observaciones[$i])."',
								'".$fotos[$i]."',
								".$obj->timestamp.",
								'".$seguimiento."',
								'".$fechas[$i]."'
							);";
							Yii::app()->db->createCommand($sql)->execute();

					}
					// Actualizar porcentaje cache
					$limit1 = Yii::app()->params['riesgoalto'];
					$limit2 = Yii::app()->params['riesgomedio'];
					$id = $obj->timestamp;
					$sql = "SELECT eva_id FROM min_respuesta WHERE eva_id = ".$id." GROUP BY eva_id";
					$categorias = Yii::app()->db->createCommand($sql)->query()->readAll();
					$nota = 0;
					$todosna = 0;
					$si = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) as n FROM min_respuesta WHERE eva_id = ".$id."  AND res_respuesta = 'si' GROUP BY res_respuesta")->queryScalar();
					$no = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) as n FROM min_respuesta WHERE eva_id = ".$id."  AND res_respuesta = 'no' GROUP BY res_respuesta")->queryScalar();
					$na = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) as n FROM min_respuesta WHERE eva_id = ".$id."  AND res_respuesta = 'n/a' GROUP BY res_respuesta")->queryScalar();
					if($si == '') $si = 0;
					if($no == '') $no = 0;
					if($na == '') $na = 0;
					if(($si + $no) > 0) $r = 100*($si / ($si + $no)); else $r = 0;
						$nota=floor($r);
						$ref = floor($nota);
					// Actualizar porcentaje cache y correlativo evaluador
					$corr = Yii::app()->db->createCommand("SELECT MAX(eva_evaluador_correlativo)+1 FROM min_evaluacion WHERE eva_evaluador = '".$obj->eva_evaluador."'")->queryScalar();
					// Obtener cargo desde aplicación, si viene vacío, sacar de mantenedor de trabajadores.
					$evaCargo = $obj->eva_cargo;
					if($evaCargo=='') $evaCargo = Yii::app()->db->createCommand("SELECT car_descripcion FROM min_evaluacion as E JOIN min_trabajador as T ON(E.tra_rut = T.tra_rut) JOIN min_cargo as C ON(T.car_id = C.car_id) where eva_id = '".$obj->timestamp."'")->queryScalar();
					$sql="UPDATE min_evaluacion SET eva_cache_porcentaje = '".$ref."', eva_evaluador_correlativo = '".$corr."' WHERE eva_id = ".$obj->timestamp;
					Yii::app()->db->createCommand($sql)->execute();
					// Enviar correos con la evaluación
					$limit1 = Yii::app()->params['riesgoalto'];
					$limit2 = Yii::app()->params['riesgomedio'];
					//$nv = 'Bajo';
					//if($ref < Yii::app()->params['riesgomedio']) $nv = 'Medio';
					//if($ref < Yii::app()->params['riesgoalto']) $nv = 'Alto';
					if($ref>=0 && $ref<=$limit1) $nv = 'Alto';
					if($ref>$limit1 && $ref<=$limit2) $nv = 'Medio';
					if($ref>$limit2 && $ref<=100) $nv = 'Bajo';
					$ver= Yii::app()->db->createCommand("SELECT res_critico,res_respuesta FROM min_respuesta WHERE eva_id = ".$id)->query()->readAll();
					for($s=0;$s<count($ver);$s++)
					{
						if($ver[$s]['res_critico'] == 'si'&&$ver[$s]['res_respuesta']=='no'){
							$alerta1 = '¡Precaución!: Trabajador ha incumplido una pregunta considerada crítica.';//';
							$alerta2 = ' Se recomienda Identificar las desviaciones, implementar las medidas correctivas y un realizar un seguimiento sistemático.';//';
							$tienecriticosi='si';

						}else if($ver[$s]['res_critico'] == 'si'&&$ver[$s]['res_respuesta']=='si'){
							$alerta1 = '';
							$alerta2 = '';
							$tienecriticono = 'no';
						}else{
							$alerta1 = '';
							$alerta2 = '';
							$tienecritico = 'no';
							$tienecriticosi = 'no';
							$tienecriticono = 'no';
						}
					}
					//verificamos si cumple con el porcentaje minimo de respuesta
					$verpromedio = Yii::app()->db->createCommand("SELECT eva_cache_porcentaje From min_evaluacion WHERE eva_id=".$id)->query()->readAll();
					for($t=0;$t<count($verpromedio);$t++)
					{
						$notapromedio = number_format(floor($verpromedio[$t]['eva_cache_porcentaje']));
						if($notapromedio>85 and $tienecriticosi=='si'){
							$alerta1 = '¡Precaución!: Trabajador ha incumplido una pregunta considerada crítica.';//';
							$alerta2 = 'Se recomienda Identificar las desviaciones, implementar las medidas correctivas y un realizar un seguimiento sistemático.';//';

						}elseif($notapromedio<=85 or $tienecriticosi=='si'){
							$alerta1 = ' ¡ALERTA! Trabajador en Nivel de Riesgo ALTO';//';
							$alerta2 = ' Se recomienda detener los trabajos y reinstruir al trabajador.';//';
						}elseif($notapromedio>85 and $tienecriticono=='no'){
							$alerta1 = '';
							$alerta2 = '';
						}elseif($tienecriticosi=='si'){
							$alerta1 = ' ¡Precaución!: Trabajador ha incumplido una pregunta considerada crítica.';//';
							$alerta2 = ' Se recomienda Identificar las desviaciones, implementar las medidas correctivas y un realizar un seguimiento sistemático.';//';
						}elseif($tienecritico=='no'){
							$alerta1 = '';//';
							$alerta2 = '';//';
						}
					}
					$eess = Yii::app()->db->createCommand("SELECT eess_nombre_corto FROM min_eess WHERE eess_rut = '".$obj->eess_rut."'")->queryScalar();
					$eessR = Yii::app()->db->createCommand("SELECT eess_rut FROM min_eess WHERE eess_rut = '".$obj->eess_rut."'")->queryScalar();
					if ($eessR == '76885630') {
						$asunto = "".json_decode(json_encode($obj->categoria))."";
						// a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>
						$email = "
						<p>Se&ntilde;ores<br><br></p>
						<p>Informamos a usted que con fecha <b>".date("d-m-Y", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>, a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b> se llev&oacute; a cabo un Control Operacional a uno de sus trabajadores que se desempe&ntilde;a como ".$evaCargo.".</p>
						<p>El resultado de esta Evaluaci&oacute;n fue de un <b>".floor($nota)." %</b> de cumplimiento, lo que representa un Nivel de Riesgo <b>".$nv."</b></p>
						<p align='center' style='color:red;'><b>".$alerta1." </b></p><p align='center'><b>".$alerta2."</b></p>
						<p>El respectivo Informe se encuentra disponible en el archivo adjunto a este mensaje. <!--o ingresando a la <a href='http://innoapsion.cl/terreno/login-eess/'>siguiente plataforma</a>, teniendo como credenciales de acceso, tanto para el usuario y contrase&ntilde;a, el Rut de su Empresa, sin puntos, guion ni d&iacute;gito verificador (Rut: 1234567)--></p>
						<p>Atte.<br>SAFCO LTDA.<br></p>
						";
						$headers = "From: SAFCO LTDA <no-reply@safco.cl> \r\n";
					}else{
						$asunto = "Evaluación de Desempeño";
						// a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>
						if($nota < 100)
						{
							$email = "
							<p>Se&ntilde;ores<br><b>".json_decode(json_encode($eess))."</b><br></p>
							<p>Informamos a usted que con fecha <b>".date("d-m-Y", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>, a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b> se llev&oacute; a cabo una Evaluaci&oacute;n sedecc a uno de sus trabajadores que se desempe&ntilde;a como ".$evaCargo.".</p>
							<p>El resultado de esta Evaluaci&oacute;n fue de un <b>".floor($nota)." %</b> de cumplimiento, lo que representa un Nivel de Riesgo <b>".$nv."</b></p>
							<p align='center' style='color:red;'><b>".$alerta1." </b></p><p align='center'><b>".$alerta2."</b></p>
							<p>El respectivo Informe se encuentra disponible en el archivo adjunto a este mensaje. <!--o ingresando a la <a href='http://innoapsion.cl/terreno/login-eess/'>siguiente plataforma</a>, teniendo como credenciales de acceso, tanto para el usuario y contrase&ntilde;a, el Rut de su Empresa, sin puntos, guion ni d&iacute;gito verificador (Rut: 1234567)--></p>
							<p>Las consultas referidas a la Evaluaci&oacute;n favor dirigirlas al Ejecutor de la misma y que se encuentra identificado en el Informe.</b>
							<p>Atte.<br>Innoapsion.<br></p>
							";
						}
						//a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>
						else
						{
							$email = "
							<p>Se&ntilde;ores<br><b>".json_decode(json_encode($eess))."</b><br></p>
							<p>Informamos a usted que con fecha <b>".date("d-m-Y", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>, a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b> se llev&oacute; a cabo una Evaluaci&oacute;n sedecc a uno de sus trabajadores que se desempe&ntilde;a como ".$evaCargo.".</p>
							<p>El resultado de esta Evaluaci&oacute;n fue de un <b>".floor($nota)." %</b> de cumplimiento, lo que representa un Nivel de Riesgo <b>".$nv."</b></p>
							<p align='center' style='color:red;'><b>".$alerta1." </b></p><p align='center'><b>".$alerta2."</b></p>
							<p>El respectivo Informe se encuentra disponible en el archivo adjunto a este mensaje. <!--o ingresando a la <a href='http://innoapsion.cl/terreno/login-eess/'>siguiente plataforma</a>, teniendo como credenciales de acceso, tanto para el usuario y contrase&ntilde;a, el Rut de su Empresa, sin puntos, guion ni d&iacute;gito verificador (Rut: 1234567)--></p>
							<p>Las consultas referidas a la Evaluaci&oacute;n favor dirigirlas al Ejecutor de la misma y que se encuentra identificado en el Informe.</b>
							<p>Atte.<br>Innoapsion.<br></p>
							";
						}
						$headers = "From: sedecc <sedecc@innoapsion.cl> \r\n";
					}
					if ($eessR == '76885630') {
						$archivo = chunk_split(base64_encode(file_get_contents("http://innoapsion.cl/sedecc/index.php?r=evaluacion/pdfsafco&id=".$obj->timestamp)));
					}else{
						$archivo = chunk_split(base64_encode(file_get_contents("http://innoapsion.cl/sedecc/index.php?r=evaluacion/pdf&id=".$obj->timestamp)));
					}

					$direccionesem = Yii::app()->db->createCommand("SELECT GROUP_CONCAT(tra_email) FROM `min_trabajador` WHERE CONCAT(tra_nombres,' ',tra_apellidos) = '".$obj->eva_nombres." ".$obj->eva_apellidos."' OR
					CONCAT(tra_nombres,' ',tra_apellidos) = '".$obj->eva_supervisor."' OR
					CONCAT(tra_nombres,' ',tra_apellidos) = '".$obj->eva_jefe_faena."' OR
					CONCAT(tra_nombres,' ',tra_apellidos) = '".$obj->eva_apr."' OR
					tra_rut = '".$obj->eva_evaluador."'
					")->queryScalar();
					$direccioneses = Yii::app()->db->createCommand("SELECT eess_email FROM `min_eess` WHERE eess_rut = '".$obj->eess_rut."'")->queryScalar();

					$headers .= "Bcc: ronnymunoz22@gmail.com, eduardoacevedo@innoapsion.cl, sebastian.carcamo398@gmail.com " . "\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
						$headers .= "Content-Type: multipart/mixed; boundary=\"=A=G=R=O=\"\r\n\r\n";
						$email_message = "--=A=G=R=O=\r\n";
						$email_message .= "Content-type: text/html; charset=iso-8859-1\r\n";
						$email_message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
					$email_message .= $email . "\r\n\r\n";
						$email_message .= "--=A=G=R=O=\r\n";
						$email_message .= "Content-Type: application/octet-stream; name=\"EvaluacionesTrabajadores.pdf\"\r\n";
						$email_message .= "Content-Transfer-Encoding: base64\r\n";
						$email_message .= "Content-Disposition: attachment; filename=\"EvaluacionesTrabajadores.pdf\"\r\n\r\n";
					//comentado para probar
						$email_message .= $archivo . "\r\n\r\n";
						$email_message .= "--=A=G=R=O=\r\n";
						mail( $direccioneses, $asunto, $email_message, $headers); // Quitar email de prueba
					//$direccioneses,
					// Check de éxito
					echo json_encode("json_valido_sedecc");

					// LO QUE OCURRE DESDE ACÁ NO ES CRÍTICO, POR ESO SE HACE POST EL MENSAJE DE ÉXITO
					// Ingresar trabajador si no existe
					$c = Yii::app()->db->createCommand("SELECT COUNT(*) FROM min_trabajador
					WHERE eess_rut = '".$obj->eess_rut."' AND tra_rut = '".$obj->tra_rut."'")->queryScalar();
					if($c == 0){
						Yii::app()->db->createCommand("INSERT INTO min_trabajador(
							eess_rut,
							tra_rut,
							tra_nombres,
							tra_apellidos,
							tra_licencia_conducir
						)VALUES(
							'".$obj->eess_rut."',
							'".$obj->tra_rut."',
							'".$obj->eva_nombres."',
							'".$obj->eva_apellidos."',
							'".$obj->eva_licencia_conducir_clase."'
						)")->execute();
					}

					// Si existe actualizar corma y licencia de conducir
					if($obj->eva_vencimiento_corma != ''){
						Yii::app()->db->createCommand("UPDATE min_trabajador SET
							tra_vencimiento_corma = '".DateTime::createFromFormat('d-m-Y', $obj->eva_vencimiento_corma)->format('Y-m-d')."'
						WHERE tra_rut = '".$obj->tra_rut."'
						")->execute();
					}
					// Si existe licencia de conducir actualizar vencimiento
					if($obj->eva_licencia_conducir_vencimiento != ''){
						Yii::app()->db->createCommand("UPDATE min_trabajador SET
							tra_vencimiento_licencia_conducir = '".DateTime::createFromFormat('d-m-Y', $obj->eva_licencia_conducir_vencimiento)->format('Y-m-d')."'
						WHERE tra_rut = '".$obj->tra_rut."'
						")->execute();
					}
					// Ingresar faena si no existe
					$c = Yii::app()->db->createCommand("SELECT COUNT(*) FROM min_faena
					WHERE fae_nombre = '".$obj->eva_faena."' AND eess_rut = '".$obj->eess_rut."'")->queryScalar();
					if($c == 0){
						Yii::app()->db->createCommand("INSERT INTO min_faena(
							fae_nombre,
							eess_rut,
							tipo
						) VALUES(
							'".$obj->eva_faena."',
							'".$obj->eess_rut."',
							'".$obj->eva_tipo_cosecha."'
						)")->execute();
					}
						// Cada vez que llegue una evaluación, se actualizarán los caché por ítem pendientes.
					$rows = Yii::app()->db->createCommand("SELECT eva_id, eva_tipo, tra_rut, eva_nombres, eva_apellidos, eva_cache_porcentaje FROM min_evaluacion WHERE eva_item_nombre_0 is NULL")->queryAll();
					for($i=0;$i<count($rows);$i++){
					// Obtener categorías de cada evaluación
						$categorias = Yii::app()->db->createCommand("SELECT DISTINCT tem_id FROM min_respuesta WHERE car_id = '".$rows[$i]['eva_tipo']."' ORDER BY tem_id")->queryAll();
						for($j=0;$j<count($categorias);$j++){
						// Asignar nombre de ítem
							Yii::app()->db->createCommand("UPDATE min_evaluacion SET eva_item_nombre_".$j." = '".$categorias[$j]['tem_id']."' WHERE eva_id = '".$rows[$i]['eva_id']."'")->execute();
						// Asignar nota
							$si = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) FROM min_evaluacion e LEFT JOIN min_respuesta r ON (e.eva_id = r.eva_id) WHERE r.eva_id = '".$rows[$i]['eva_id']."' AND r.tem_id = '".$categorias[$j]['tem_id']."' AND res_respuesta = 'si'")->queryScalar();
							$no = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) FROM min_evaluacion e LEFT JOIN min_respuesta r ON (e.eva_id = r.eva_id) WHERE r.eva_id = '".$rows[$i]['eva_id']."' AND r.tem_id = '".$categorias[$j]['tem_id']."' AND res_respuesta = 'no'")->queryScalar();
							if($si+$no>0){
							Yii::app()->db->createCommand("UPDATE min_evaluacion SET eva_item_nota_".$j." = '".floor(100*($si/($si+$no)))."' WHERE eva_id = '".$rows[$i]['eva_id']."'")->execute();
							}
						}
					}
				}
				else if($tipo_checklist=='equipos')
				{
					//eq_maquina


					$rows_eq_maquina = Yii::app()->db->createCommand("SELECT eq_maquina FROM min_equipos WHERE eq_codigo = '".$obj->eq_codigo."'")->queryScalar();
					$rows_marca = Yii::app()->db->createCommand("SELECT eq_marca FROM min_equipos WHERE eq_codigo = '".$obj->eq_codigo."'")->queryScalar();
					$rows_modelo = Yii::app()->db->createCommand("SELECT eq_modelo FROM min_equipos WHERE eq_codigo = '".$obj->eq_codigo."'")->queryScalar();
					$rows_tipo = Yii::app()->db->createCommand("SELECT eq_tipo FROM min_equipos WHERE eq_codigo = '".$obj->eq_codigo."'")->queryScalar();
					$rows_ano = Yii::app()->db->createCommand("SELECT eq_ano FROM min_equipos WHERE eq_codigo = '".$obj->eq_codigo."'")->queryScalar();
					//$rows_busca_equipos = Yii::app()->db->createCommand("SELECT eq_maquina,eq_marca,eq_modelo,eq_tipo,eq_ano FROM min_equipos WHERE eq_codigo = '".$obj->eq_codigo."'")->queryScalar();

					//realizar en caso de checklist equipos
					$sql = "INSERT INTO min_evaluacion_equipos(
						eva_id,
						eva_creado,
						eva_tipo,
						eess_rut,
						eva_fecha_evaluacion,
						eva_jefe_faena,
						eq_codigo,
						eq_tipo,
						eq_maquina,
						eq_marca,
						eq_modelo,
						eva_operador,
						eva_horometro,
						eva_ot,
						eva_faena,
						eva_supervisor,
						eva_apr,
						eva_geo_x,
						eva_geo_y,
						eva_general_observacion,
						eva_general_foto,
						eva_general_fecha,
						eva_evaluador
						) VALUES(
						".$obj->timestamp.",
						null,
						'".$obj->categoria."',
						'".$obj->eess_rut."',
						'".date("Y-m-d H:i:s",($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."',
						'".$obj->eva_jefe_faena."',
						'".$obj->eq_codigo."',
						'".$rows_tipo."',
						'".$rows_eq_maquina."',
						'".$rows_marca."',
						'".$rows_modelo."',
						'".$obj->eva_operador."',
						'".$obj->eva_horometro."',
						'".$obj->eva_ot."',
						'".$obj->eva_faena."',
						'".$obj->eva_supervisor."',
						'".$obj->eva_apr."',
						".$obj->lat.",
						".$obj->lon.",
						'".$obj->eva_general_observacion."',
						'".$obj->eva_general_foto."',
						'".$obj->eva_general_fecha."',
						'".$obj->eva_evaluador."'
					);";
					$myfile = fopen("recep/sql".$_POST['android'].$_POST['timestamp'], "w") or die("Unable to open file!");
					fwrite($myfile, $sql);
					fclose($myfile);
					Yii::app()->db->createCommand($guardaversion)->execute();
					Yii::app()->db->createCommand($sql)->execute();
					$items = json_decode(utf8_encode(base64_decode($obj->items)));
					$respuestas = json_decode(base64_decode($obj->respuestas));
					$observaciones = json_decode(base64_decode($obj->observaciones));
					$fotos = json_decode(base64_decode($obj->fotos));
					$fechas = json_decode(base64_decode($obj->fechas));

					for($i=0;$i<count($items);$i++)
					{
							$seguimiento = 1;
							if($respuestas[$i] == 'no') $seguimiento = 0;
							$sql = "INSERT INTO min_respuesta_equipos(
								res_enunciado,
								res_respuesta,
								res_critico,
								res_ponderacion,
								pre_id,
								car_id,
								tem_id,
								res_observacion,
								res_foto,
								eva_id,
								res_seguimiento,
								res_plazo
								) VALUES (
								'".$items[$i]->pre_enunciado."',
								'".$respuestas[$i]."',
								'".$items[$i]->critico."',
								'".$items[$i]->pre_ponderacion."',
								'".$items[$i]->pre_id."',
								'".$obj->categoria."',
								'".$items[$i]->tem_id."',
								'".urldecode($observaciones[$i])."',
								'".$fotos[$i]."',
								".$obj->timestamp.",
								'".$seguimiento."',
								'".$fechas[$i]."'
							);";
							Yii::app()->db->createCommand($sql)->execute();
					}
					// Actualizar porcentaje cache
					$limit1 = Yii::app()->params['riesgoalto'];
					$limit2 = Yii::app()->params['riesgomedio'];
					$id = $obj->timestamp;
					$sql = "SELECT eva_id FROM min_respuesta_equipos WHERE eva_id = ".$id." GROUP BY eva_id";
					$categorias = Yii::app()->db->createCommand($sql)->query()->readAll();
					$nota = 0;
					$todosna = 0;
					$si = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) as n FROM min_respuesta_equipos WHERE eva_id = ".$id."  AND res_respuesta = 'si' GROUP BY res_respuesta")->queryScalar();
					$no = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) as n FROM min_respuesta_equipos WHERE eva_id = ".$id."  AND res_respuesta = 'no' GROUP BY res_respuesta")->queryScalar();
					$na = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) as n FROM min_respuesta_equipos WHERE eva_id = ".$id."  AND res_respuesta = 'n/a' GROUP BY res_respuesta")->queryScalar();
					if($si == '') $si = 0;
					if($no == '') $no = 0;
					if($na == '') $na = 0;
					if(($si + $no) > 0) $r = 100*($si / ($si + $no)); else $r = 0;
						$nota=floor($r);
						$ref = floor($nota);
					//filtramos para que en el caso que sea checklist de equipos pueda actualizar el cache de min_evaluaciones_equipos
					// Actualizar porcentaje cache y correlativo evaluador
					//$corr = Yii::app()->db->createCommand("SELECT MAX(eva_evaluador_correlativo)+1 FROM min_evaluacion_equipos WHERE eva_evaluador = '".$obj->eva_evaluador."'")->queryScalar();
					//$sql="UPDATE min_evaluacion_equipos SET eva_cache_porcentaje = '".$ref."', eva_evaluador_correlativo = '".$corr."' WHERE eva_id = ".$obj->timestamp;
					// Actualizar porcentaje cache y correlativo evaluador
					$corr = Yii::app()->db->createCommand("SELECT MAX(eva_evaluador_correlativo)+1 FROM min_evaluacion_equipos WHERE eva_evaluador = '".$obj->eva_evaluador."'")->queryScalar();
					// Obtener cargo desde aplicación, si viene vacío, sacar de mantenedor de trabajadores.
					$evaCargo = $obj->eva_cargo;
					if($evaCargo=='') $evaCargo = '';//Yii::app()->db->createCommand("SELECT car_descripcion FROM min_evaluacion_equipos as E JOIN min_trabajador as T ON(E.tra_rut = T.tra_rut) JOIN min_cargo as C ON(T.car_id = C.car_id) where eva_id = '".$obj->timestamp."'")->queryScalar();
					$sql="UPDATE min_evaluacion_equipos SET eva_cache_porcentaje = '".$ref."', eva_evaluador_correlativo = '".$corr."' WHERE eva_id = ".$obj->timestamp;
					Yii::app()->db->createCommand($sql)->execute();
					// Enviar correos con la evaluación
					 $limit1 = Yii::app()->params['riesgoalto'];
					$limit2 = Yii::app()->params['riesgomedio'];
					//$nv = 'Bajo';
					//if($ref < Yii::app()->params['riesgomedio']) $nv = 'Medio';
					//if($ref < Yii::app()->params['riesgoalto']) $nv = 'Alto';
					if($ref>=0 && $ref<=$limit1) $nv = 'Alto';
					if($ref>$limit1 && $ref<=$limit2) $nv = 'Medio';
					if($ref>$limit2 && $ref<=100) $nv = 'Bajo';

					$ver= Yii::app()->db->createCommand("SELECT res_critico,res_respuesta FROM min_respuesta_equipos WHERE eva_id = ".$id)->query()->readAll();
					for($s=0;$s<count($ver);$s++)
					{
						if($ver[$s]['res_critico'] == 'si'&&$ver[$s]['res_respuesta']=='no'){
							$alerta1 = '¡Precaución!: Equipo ha incumplido una pregunta considerada crítica.';//';
							$alerta2 = ' Se recomienda Identificar las desviaciones, implementar las medidas correctivas y un realizar un seguimiento sistemático.';//';
							$tienecriticosi='si';

						}else if($ver[$s]['res_critico'] == 'si'&&$ver[$s]['res_respuesta']=='si'){
							$alerta1 = '';
							$alerta2 = '';
							$tienecriticono = 'no';
						}else{
							$alerta1 = '';
							$alerta2 = '';
							$tienecritico = 'no';
							$tienecriticosi = 'no';
							$tienecriticono = 'no';
						}
					}
					//verificamos si cumple con el porcentaje minimo de respuesta
					$verpromedio = Yii::app()->db->createCommand("SELECT eva_cache_porcentaje From min_evaluacion_equipos WHERE eva_id=".$id)->query()->readAll();
					for($t=0;$t<count($verpromedio);$t++)
					{
						$notapromedio = number_format(floor($verpromedio[$t]['eva_cache_porcentaje']));
						if($notapromedio>85 and $tienecriticosi=='si'){
							$alerta1 = '¡Precaución!: Trabajador ha incumplido una pregunta considerada crítica.';//';
							$alerta2 = 'Se recomienda Identificar las desviaciones, implementar las medidas correctivas y un realizar un seguimiento sistemático.';//';

						}elseif($notapromedio<=85 or $tienecriticosi=='si'){
							$alerta1 = ' ¡ALERTA! Trabajador en Nivel de Riesgo ALTO';//';
							$alerta2 = ' Se recomienda detener los trabajos y reparar EQUIPO.';//';
						}elseif($notapromedio>85 and $tienecriticono=='no'){
							$alerta1 = '';
							$alerta2 = '';
						}elseif($tienecriticosi=='si'){
							$alerta1 = ' ¡Precaución!: Trabajador ha incumplido una pregunta considerada crítica.';//';
							$alerta2 = ' Se recomienda Identificar las desviaciones, implementar las medidas correctivas y un realizar un seguimiento sistemático.';//';
						}elseif($tienecritico=='no'){
							$alerta1 = '';//';
							$alerta2 = '';//';
						}
					}
					//cambios4
					// Enviar email a medio mundo

					$eess = Yii::app()->db->createCommand("SELECT eess_nombre_corto FROM min_eess WHERE eess_rut = '".$obj->eess_rut."'")->queryScalar();
					$eessR = Yii::app()->db->createCommand("SELECT eess_rut FROM min_eess WHERE eess_rut = '".$obj->eess_rut."'")->queryScalar();
					if ($eessR == '76885630') {
						//en el caso de necesitar uno nuevo u otro tipo de correo
						$asunto = "".json_decode(json_encode($obj->categoria))."";
						// a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>
						$email = "
						<p>Se&ntilde;ores<br><b>".json_decode(json_encode($eess))."</b><br></p>
						<p>Informamos a usted que con fecha <b>".date("d-m-Y", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>, a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b> se llev&oacute; a cabo un Monitoreo al Equipo/Máquina ".$obj->eq_maquina." que tiene como Código de Equipo ".$obj->eq_codigo.".</p>
						<p>El resultado de esta Evaluaci&oacute;n fue de un <b>".floor($nota)." %</b> de cumplimiento, lo que representa un Nivel de Riesgo <b>".$nv."</b></p>
						<p align='center' style='color:red;'><b>".$alerta1." </b></p><p align='center'><b>".$alerta2."</b></p>
						<p>El respectivo Informe se encuentra disponible en el archivo adjunto a este mensaje. <!--o ingresando a la <a href='http://innoapsion.cl/terreno/login-eess/'>siguiente plataforma</a>, teniendo como credenciales de acceso, tanto para el usuario y contrase&ntilde;a, el Rut de su Empresa, sin puntos, guion ni d&iacute;gito verificador (Rut: 1234567)--></p>
						<p>Las consultas referidas a la Evaluaci&oacute;n favor dirigirlas al Ejecutor de la misma y que se encuentra identificado en el Informe.</b>
						<p>Atte.<br>Innoapsion.<br></p>
						";
						$headers = "From: SAFCO LTDA <no-reply@safco.cl> \r\n";
					}else{
					$asunto = "Monitoreo Máquina ".$obj->eq_maquina."(".$obj->eq_codigo.")";
					// a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>
					if($nota < 100) $email = "
					<p>Se&ntilde;ores<br><b>".json_decode(json_encode($eess))."</b><br></p>
					<p>Informamos a usted que con fecha <b>".date("d-m-Y", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>, a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b> se llev&oacute; a cabo un Monitoreo al Equipo/Máquina ".$obj->eq_maquina." que tiene como Código de Equipo ".$obj->eq_codigo.".</p>
					<p>El resultado de esta Evaluaci&oacute;n fue de un <b>".floor($nota)." %</b> de cumplimiento, lo que representa un Nivel de Riesgo <b>".$nv."</b></p>
					<p align='center' style='color:red;'><b>".$alerta1." </b></p><p align='center'><b>".$alerta2."</b></p>
					<p>El respectivo Informe se encuentra disponible en el archivo adjunto a este mensaje. <!--o ingresando a la <a href='http://innoapsion.cl/terreno/login-eess/'>siguiente plataforma</a>, teniendo como credenciales de acceso, tanto para el usuario y contrase&ntilde;a, el Rut de su Empresa, sin puntos, guion ni d&iacute;gito verificador (Rut: 1234567)--></p>
					<p>Las consultas referidas a la Evaluaci&oacute;n favor dirigirlas al Ejecutor de la misma y que se encuentra identificado en el Informe.</b>
					<p>Atte.<br>Innoapsion.<br></p>
					";

					//a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>
					else $email = "
					<p>Se&ntilde;ores<br><b>".json_decode(json_encode($eess))."</b><br></p>
					<p>Informamos a usted que con fecha <b>".date("d-m-Y", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>, a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b> se llev&oacute; a cabo un Monitoreo al Equipo/Máquina ".$obj->eq_maquina." que tiene como Código de Equipo ".$obj->eq_codigo.".</p>
					<p>El resultado de esta Evaluaci&oacute;n fue de un <b>".floor($nota)." %</b> de cumplimiento, lo que representa un Nivel de Riesgo <b>".$nv."</b></p>
					<p align='center' style='color:red;'><b>".$alerta1." </b></p><p align='center'><b>".$alerta2."</b></p>
					<p>El respectivo Informe se encuentra disponible en el archivo adjunto a este mensaje. <!--o ingresando a la <a href='http://innoapsion.cl/terreno/login-eess/'>siguiente plataforma</a>, teniendo como credenciales de acceso, tanto para el usuario y contrase&ntilde;a, el Rut de su Empresa, sin puntos, guion ni d&iacute;gito verificador (Rut: 1234567)--></p>
					<p>Las consultas referidas a la Evaluaci&oacute;n favor dirigirlas al Ejecutor de la misma y que se encuentra identificado en el Informe.</b>
					<p>Atte.<br>Innoapsion.<br></p>
					";
					$headers = "From: sedecc <sedecc@innoapsion.cl> \r\n";
					}

					if ($eessR == '76885630') {
						$archivo = chunk_split(base64_encode(file_get_contents("http://innoapsion.cl/sedecc/index.php?r=evaEquipos/pdfsafco&id=".$obj->timestamp)));
					}else{
						$archivo = chunk_split(base64_encode(file_get_contents("http://innoapsion.cl/sedecc/index.php?r=evaEquipos/pdf&id=".$obj->timestamp)));
					}

					//  $otrosemails = Yii::app()->db->createCommand("SELECT GROUP_CONCAT(ema_email) FROM min_email WHERE eess_rut = '".$obj->eess_rut."'")->queryScalar();

					/*$direccionesem = Yii::app()->db->createCommand("
					SELECT GROUP_CONCAT(tra_email) FROM `min_trabajador` WHERE
					CONCAT(tra_nombres,' ',tra_apellidos) = '".$obj->eva_nombres." ".$obj->eva_apellidos."' OR
					CONCAT(tra_nombres,' ',tra_apellidos) = '".$obj->eva_supervisor."' OR
					CONCAT(tra_nombres,' ',tra_apellidos) = '".$obj->eva_jefe_faena."' OR
					CONCAT(tra_nombres,' ',tra_apellidos) = '".$obj->eva_apr."' OR
					tra_rut = '".$obj->eva_evaluador."'
					")->queryScalar();
					*/

					$direccioneses = Yii::app()->db->createCommand("SELECT eess_email FROM `min_eess` WHERE eess_rut = '".$obj->eess_rut."'")->queryScalar();

					$headers .= "Bcc: ronnymunoz22@gmail.com, eduardoacevedo@innoapsion.cl, sebastian.carcamo398@gmail.com " . "\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
						$headers .= "Content-Type: multipart/mixed; boundary=\"=A=G=R=O=\"\r\n\r\n";
						$email_message = "--=A=G=R=O=\r\n";
						$email_message .= "Content-type: text/html; charset=iso-8859-1\r\n";
						$email_message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
					$email_message .= $email . "\r\n\r\n";
						$email_message .= "--=A=G=R=O=\r\n";
						$email_message .= "Content-Type: application/octet-stream; name=\"EvaluacionesEquipos.pdf\"\r\n";
						$email_message .= "Content-Transfer-Encoding: base64\r\n";
						$email_message .= "Content-Disposition: attachment; filename=\"EvaluacionesEquipos.pdf\"\r\n\r\n";
					//comentado para probar
						$email_message .= $archivo . "\r\n\r\n";
						$email_message .= "--=A=G=R=O=\r\n";
						mail( $direccioneses, $asunto, $email_message, $headers); // Quitar email de prueba
					//$direccioneses,
					// Check de éxito
					echo json_encode("json_valido_sedecc");
					// LO QUE OCURRE DESDE ACÁ NO ES CRÍTICO, POR ESO SE HACE POST EL MENSAJE DE ÉXITO
					// Ingresar faena si no existe
					$c = Yii::app()->db->createCommand("SELECT COUNT(*) FROM min_faena
					WHERE fae_nombre = '".$obj->eva_faena."' AND eess_rut = '".$obj->eess_rut."'")->queryScalar();
					if($c == 0){
						Yii::app()->db->createCommand("INSERT INTO min_faena(
							fae_nombre,
							eess_rut,
							tipo
						) VALUES(
							'".$obj->eva_faena."',
							'".$obj->eess_rut."',
							'".$obj->eva_tipo_cosecha."'
						)")->execute();
					}
						// Cada vez que llegue una evaluación, se actualizarán los caché por ítem pendientes.
					$rows = Yii::app()->db->createCommand("SELECT eva_id, eva_tipo, eva_cache_porcentaje FROM min_evaluacion_equipos WHERE eva_item_nombre_0 is NULL")->queryAll();
					for($i=0;$i<count($rows);$i++){
					// Obtener categorías de cada evaluación
						$categorias = Yii::app()->db->createCommand("SELECT DISTINCT tem_id FROM min_respuesta_equipos WHERE car_id = '".$rows[$i]['eva_tipo']."' ORDER BY tem_id")->queryAll();
						for($j=0;$j<count($categorias);$j++){
						// Asignar nombre de ítem
							Yii::app()->db->createCommand("UPDATE min_evaluacion_equipos SET eva_item_nombre_".$j." = '".$categorias[$j]['tem_id']."' WHERE eva_id = '".$rows[$i]['eva_id']."'")->execute();
						// Asignar nota
							$si = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) FROM min_evaluacion_equipos e LEFT JOIN min_respuesta r ON (e.eva_id = r.eva_id) WHERE r.eva_id = '".$rows[$i]['eva_id']."' AND r.tem_id = '".$categorias[$j]['tem_id']."' AND res_respuesta = 'si'")->queryScalar();
							$no = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) FROM min_evaluacion_equipos e LEFT JOIN min_respuesta r ON (e.eva_id = r.eva_id) WHERE r.eva_id = '".$rows[$i]['eva_id']."' AND r.tem_id = '".$categorias[$j]['tem_id']."' AND res_respuesta = 'no'")->queryScalar();
							if($si+$no>0){
							Yii::app()->db->createCommand("UPDATE min_evaluacion_equipos SET eva_item_nota_".$j." = '".floor(100*($si/($si+$no)))."' WHERE eva_id = '".$rows[$i]['eva_id']."'")->execute();
							}
						}
					}

				}
				else if($tipo_checklist=='instalaciones')
				{
				//realizar en caso de checklist instalaciones
					$sql = "INSERT INTO min_evaluacion_instalaciones(
						eva_id,
						eva_creado,
						eva_tipo,
						eess_rut,
						eva_cod_faena,
						eva_gerente_general,
						eva_num_trabajadores,
						eva_gerente_operacion,
						eva_administrador,
						eva_num_personas,
						eva_fecha_evaluacion,
						eva_fundo,
						eva_jefe_faena,
						eva_faena,
						eva_supervisor,
						eva_apr,
						eva_geo_x,
						eva_geo_y,
						eva_general_observacion,
						eva_general_foto,
						eva_general_fecha,
						eva_evaluador
					) VALUES(
						".$obj->timestamp.",
						null,
						'".$obj->categoria."',
						'".$obj->eess_rut."',
						'".$obj->eva_cod_faena."',
						'".$obj->eva_gerente_general."',
						'".$obj->eva_num_trabajadores."',
						'".$obj->eva_gerente_operacion."',
						'".$obj->eva_administrador."',
						'".$obj->eva_num_personas."',
						'".date("Y-m-d H:i:s",($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."',
						'".$obj->eva_fundo."',
						'".$obj->eva_jefe_faena."',
						'".$obj->eva_faena."',
						'".$obj->eva_supervisor."',
						'".$obj->eva_apr."',
						".$obj->lat.",
						".$obj->lon.",
						'".$obj->eva_general_observacion."',
						'".$obj->eva_general_foto."',
						'".$obj->eva_general_fecha."',
						'".$obj->eva_evaluador."'
					);";
					$myfile = fopen("recep/sql".$_POST['android'].$_POST['timestamp'], "w") or die("Unable to open file!");
					fwrite($myfile, $sql);
					fclose($myfile);
					Yii::app()->db->createCommand($guardaversion)->execute();
					Yii::app()->db->createCommand($sql)->execute();
					$items = json_decode(utf8_encode(base64_decode($obj->items)));
					$respuestas = json_decode(base64_decode($obj->respuestas));
					$observaciones = json_decode(base64_decode($obj->observaciones));
					$fotos = json_decode(base64_decode($obj->fotos));
					$fechas = json_decode(base64_decode($obj->fechas));

					for($i=0;$i<count($items);$i++)
					{
							$seguimiento = 1;
							if($respuestas[$i] == 'no') $seguimiento = 0;

							$sql = "INSERT INTO min_respuesta_instalaciones(
								res_enunciado,
								res_respuesta,
								res_critico,
								res_ponderacion,
								pre_id,
								car_id,
								tem_id,
								res_observacion,
								res_foto,
								eva_id,
								res_seguimiento,
								res_plazo
							) VALUES (
								'".$items[$i]->pre_enunciado."',
								'".$respuestas[$i]."',
								'".$items[$i]->critico."',
								'".$items[$i]->pre_ponderacion."',
								'".$items[$i]->pre_id."',
								'".$obj->categoria."',
								'".$items[$i]->tem_id."',
								'".urldecode($observaciones[$i])."',
								'".$fotos[$i]."',
								".$obj->timestamp.",
								'".$seguimiento."',
								'".$fechas[$i]."'
							);";
							Yii::app()->db->createCommand($sql)->execute();
						}

					// Actualizar porcentaje cache
					$limit1 = Yii::app()->params['riesgoalto'];
					$limit2 = Yii::app()->params['riesgomedio'];
					$id = $obj->timestamp;
					$sql = "SELECT eva_id FROM min_respuesta_instalaciones WHERE eva_id = ".$id." GROUP BY eva_id";
					$categorias = Yii::app()->db->createCommand($sql)->query()->readAll();
					$nota = 0;
					$todosna = 0;
					$si = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) as n FROM min_respuesta_instalaciones WHERE eva_id = ".$id."  AND res_respuesta = 'si' GROUP BY res_respuesta")->queryScalar();
					$no = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) as n FROM min_respuesta_instalaciones WHERE eva_id = ".$id."  AND res_respuesta = 'no' GROUP BY res_respuesta")->queryScalar();
					$na = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) as n FROM min_respuesta_instalaciones WHERE eva_id = ".$id."  AND res_respuesta = 'n/a' GROUP BY res_respuesta")->queryScalar();
					if($si == '') $si = 0;
					if($no == '') $no = 0;
					if($na == '') $na = 0;
					if(($si + $no) > 0) $r = 100*($si / ($si + $no)); else $r = 0;
						$nota=floor($r);
						$ref = floor($nota);
					//filtramos para que en el caso que sea checklist de equipos pueda actualizar el cache de min_evaluaciones_equipos
					// Actualizar porcentaje cache y correlativo evaluador
					$corr = Yii::app()->db->createCommand("SELECT MAX(eva_evaluador_correlativo)+1 FROM min_evaluacion_instalaciones WHERE eva_evaluador = '".$obj->eva_evaluador."'")->queryScalar();
					$evaCargo = $obj->eva_cargo;
					if($evaCargo=='') $evaCargo = '';
					$sql="UPDATE min_evaluacion_instalaciones SET eva_cache_porcentaje = '".$ref."', eva_evaluador_correlativo = '".$corr."' WHERE eva_id = ".$obj->timestamp;
					Yii::app()->db->createCommand($sql)->execute();
				// A
					// Enviar correos con la evaluación
					$limit1 = Yii::app()->params['riesgoalto'];
					$limit2 = Yii::app()->params['riesgomedio'];
					//$nv = 'Bajo';
					//if($ref < Yii::app()->params['riesgomedio']) $nv = 'Medio';
					//if($ref < Yii::app()->params['riesgoalto']) $nv = 'Alto';
					if($ref>=0 && $ref<=$limit1) $nv = 'Alto';
					if($ref>$limit1 && $ref<=$limit2) $nv = 'Medio';
					if($ref>$limit2 && $ref<=100) $nv = 'Bajo';
					$ver= Yii::app()->db->createCommand("SELECT res_critico,res_respuesta FROM min_respuesta_instalaciones WHERE eva_id = ".$id)->query()->readAll();
					for($s=0;$s<count($ver);$s++)
					{
						if($ver[$s]['res_critico'] == 'si'&&$ver[$s]['res_respuesta']=='no'){
							$alerta1 = '¡Precaución!: Faena ha incumplido una pregunta considerada crítica.';//';
							$alerta2 = ' Se recomienda Identificar las desviaciones, implementar las medidas correctivas y un realizar un seguimiento sistemático.';//';
							$tienecriticosi='si';

						}else if($ver[$s]['res_critico'] == 'si'&&$ver[$s]['res_respuesta']=='si'){
							$alerta1 = '';
							$alerta2 = '';
							$tienecriticono = 'no';
						}else{
							$alerta1 = '';
							$alerta2 = '';
							$tienecritico = 'no';
							$tienecriticosi = 'no';
							$tienecriticono = 'no';
						}
					}
					//verificamos si cumple con el porcentaje minimo de respuesta
					$verpromedio = Yii::app()->db->createCommand("SELECT eva_cache_porcentaje From min_evaluacion_instalaciones WHERE eva_id=".$id)->query()->readAll();
					for($t=0;$t<count($verpromedio);$t++)
					{
						$notapromedio = number_format(floor($verpromedio[$t]['eva_cache_porcentaje']));
						if($notapromedio>85 and $tienecriticosi=='si'){
							$alerta1 = '¡Precaución!: Faena ha incumplido una pregunta considerada crítica.';//';
							$alerta2 = 'Se recomienda Identificar las desviaciones, implementar las medidas correctivas y un realizar un seguimiento sistemático.';//';

						}elseif($notapromedio<=85 or $tienecriticosi=='si'){
							$alerta1 = ' ¡ALERTA! Faena en Nivel de Riesgo ALTO';//';
							$alerta2 = ' Se recomienda detener los trabajos y reorganizar instalaciones.';//';
						}elseif($notapromedio>85 and $tienecriticono=='no'){
							$alerta1 = '';
							$alerta2 = '';
						}elseif($tienecriticosi=='si'){
							$alerta1 = ' ¡Precaución!: Faena ha incumplido una pregunta considerada crítica.';//';
							$alerta2 = ' Se recomienda Identificar las desviaciones, implementar las medidas correctivas y un realizar un seguimiento sistemático.';//';
						}elseif($tienecritico=='no'){
							$alerta1 = '';//';
							$alerta2 = '';//';
						}
					}
					$evaCargo = $obj->eva_cod_faena;
					$eess = Yii::app()->db->createCommand("SELECT eess_nombre_corto FROM min_eess WHERE eess_rut = '".$obj->eess_rut."'")->queryScalar();
					$eessR = Yii::app()->db->createCommand("SELECT eess_rut FROM min_eess WHERE eess_rut = '".$obj->eess_rut."'")->queryScalar();
					if ($eessR == '76885630')
					{
						$asunto = "".json_decode(json_encode($obj->categoria))."";
						// a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>
						$email = "
						<p>Se&ntilde;ores<br><b>".json_decode(json_encode($eess))."</b><br></p>
						<p>Informamos a usted que con fecha <b>".date("d-m-Y", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>, a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b> se llev&oacute; a cabo un Monitoreo y control de faena a su Instalación : ".$obj->eva_cod_faena.".</p>
						<p>El resultado de esta Evaluaci&oacute;n fue de un <b>".floor($nota)." %</b> de cumplimiento, lo que representa un Nivel de Riesgo <b>".$nv."</b></p>
						<p align='center' style='color:red;'><b>".$alerta1." </b></p><p align='center'><b>".$alerta2."</b></p>
						<p>El respectivo Informe se encuentra disponible en el archivo adjunto a este mensaje. <!--o ingresando a la <a href='http://innoapsion.cl/terreno/login-eess/'>siguiente plataforma</a>, teniendo como credenciales de acceso, tanto para el usuario y contrase&ntilde;a, el Rut de su Empresa, sin puntos, guion ni d&iacute;gito verificador (Rut: 1234567)--></p>
						<p>Las consultas referidas a la Evaluaci&oacute;n favor dirigirlas al Ejecutor de la misma y que se encuentra identificado en el Informe.</b>
						<p>Atte.<br>Sedecc.<br></p>
						";
						$headers = "From: SAFCO LTDA <no-reply@safco.cl> \r\n";
					}
					else
					{
						$asunto = "Evaluación de Desempeño";
						// a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>
						if($nota < 100)
						{
							$email = "
							<p>Se&ntilde;ores<br><b>".json_decode(json_encode($eess))."</b><br></p>
							<p>Informamos a usted que con fecha <b>".date("d-m-Y", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>, a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b> se llev&oacute; a cabo un Monitoreo y control de faena a su Instalación : ".$obj->eva_cod_faena.".</p>
							<p>El resultado de esta Evaluaci&oacute;n fue de un <b>".floor($nota)." %</b> de cumplimiento, lo que representa un Nivel de Riesgo <b>".$nv."</b></p>
							<p align='center' style='color:red;'><b>".$alerta1." </b></p><p align='center'><b>".$alerta2."</b></p>
							<p>El respectivo Informe se encuentra disponible en el archivo adjunto a este mensaje. <!--o ingresando a la <a href='http://innoapsion.cl/terreno/login-eess/'>siguiente plataforma</a>, teniendo como credenciales de acceso, tanto para el usuario y contrase&ntilde;a, el Rut de su Empresa, sin puntos, guion ni d&iacute;gito verificador (Rut: 1234567)--></p>
							<p>Las consultas referidas a la Evaluaci&oacute;n favor dirigirlas al Ejecutor de la misma y que se encuentra identificado en el Informe.</b>
							<p>Atte.<br>Sedecc.<br></p>
							";
							//a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>
						}
						else
						{
							$email = "
							<p>Se&ntilde;ores<br><b>".json_decode(json_encode($eess))."</b><br></p>
							<p>Informamos a usted que con fecha <b>".date("d-m-Y", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b>, a las <b>".date("H:i", ($obj->timestamp/1000)+(3600*Yii::app()->params['gmt']))."</b> se llev&oacute; a cabo un Monitoreo y control de faena a su Instalación : ".$obj->eva_cod_faena.".</p>
							<p>El resultado de esta Evaluaci&oacute;n fue de un <b>".floor($nota)." %</b> de cumplimiento, lo que representa un Nivel de Riesgo <b>".$nv."</b></p>
							<p align='center' style='color:red;'><b>".$alerta1." </b></p><p align='center'><b>".$alerta2."</b></p>
							<p>El respectivo Informe se encuentra disponible en el archivo adjunto a este mensaje. <!--o ingresando a la <a href='http://innoapsion.cl/terreno/login-eess/'>siguiente plataforma</a>, teniendo como credenciales de acceso, tanto para el usuario y contrase&ntilde;a, el Rut de su Empresa, sin puntos, guion ni d&iacute;gito verificador (Rut: 1234567)--></p>
							<p>Las consultas referidas a la Evaluaci&oacute;n favor dirigirlas al Ejecutor de la misma y que se encuentra identificado en el Informe.</b>
							<p>Atte.<br>Sedecc.<br></p>
							";
						}
					$headers = "From: sedecc <sedecc@sedecc.cl> \r\n";
					}

					$archivo = chunk_split(base64_encode(file_get_contents("http://innoapsion.cl/sedecc/index.php?r=evalInstalaciones/pdf&id=".$obj->timestamp)));


					$direccionesem = '';


					$direccioneses = Yii::app()->db->createCommand("SELECT eess_email FROM `min_eess` WHERE eess_rut = '".$obj->eess_rut."'")->queryScalar();

					$headers .= "Bcc: ronnymunoz22@gmail.com, eduardoacevedo@innoapsion.cl, sebastian.carcamo398@gmail.com " . "\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
						$headers .= "Content-Type: multipart/mixed; boundary=\"=A=G=R=O=\"\r\n\r\n";
						$email_message = "--=A=G=R=O=\r\n";
						$email_message .= "Content-type: text/html; charset=iso-8859-1\r\n";
						$email_message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
					$email_message .= $email . "\r\n\r\n";
						$email_message .= "--=A=G=R=O=\r\n";
						$email_message .= "Content-Type: application/octet-stream; name=\"EvaluacionesInstalaciones.pdf\"\r\n";
						$email_message .= "Content-Transfer-Encoding: base64\r\n";
						$email_message .= "Content-Disposition: attachment; filename=\"EvaluacionesInstalaciones.pdf\"\r\n\r\n";
					//comentado para probar
						$email_message .= $archivo . "\r\n\r\n";
						$email_message .= "--=A=G=R=O=\r\n";
						mail( $direccioneses, $asunto, $email_message, $headers); // Quitar email de prueba
					//$direccioneses,
					// Check de éxito
					echo json_encode("json_valido_sedecc");

					// LO QUE OCURRE DESDE ACÁ NO ES CRÍTICO, POR ESO SE HACE POST EL MENSAJE DE ÉXITO
					// Cada vez que llegue una evaluación, se actualizarán los caché por ítem pendientes.
					$rows = Yii::app()->db->createCommand("SELECT eva_id, eva_tipo, eva_cache_porcentaje FROM min_evaluacion_instalaciones WHERE eva_item_nombre_0 is NULL")->queryAll();
					for($i=0;$i<count($rows);$i++)
					{
					// Obtener categorías de cada evaluación
						$categorias = Yii::app()->db->createCommand("SELECT DISTINCT tem_id FROM min_respuesta WHERE car_id = '".$rows[$i]['eva_tipo']."' ORDER BY tem_id")->queryAll();
						for($j=0;$j<count($categorias);$j++)
						{
						// Asignar nombre de ítem
							Yii::app()->db->createCommand("UPDATE min_evaluacion SET eva_item_nombre_".$j." = '".$categorias[$j]['tem_id']."' WHERE eva_id = '".$rows[$i]['eva_id']."'")->execute();
						// Asignar nota
							$si = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) FROM min_evaluacion e LEFT JOIN min_respuesta r ON (e.eva_id = r.eva_id) WHERE r.eva_id = '".$rows[$i]['eva_id']."' AND r.tem_id = '".$categorias[$j]['tem_id']."' AND res_respuesta = 'si'")->queryScalar();
							$no = Yii::app()->db->createCommand("SELECT SUM(res_ponderacion) FROM min_evaluacion e LEFT JOIN min_respuesta r ON (e.eva_id = r.eva_id) WHERE r.eva_id = '".$rows[$i]['eva_id']."' AND r.tem_id = '".$categorias[$j]['tem_id']."' AND res_respuesta = 'no'")->queryScalar();
							if($si+$no>0)
							{
								Yii::app()->db->createCommand("UPDATE min_evaluacion SET eva_item_nota_".$j." = '".floor(100*($si/($si+$no)))."' WHERE eva_id = '".$rows[$i]['eva_id']."'")->execute();
							}
						}
					}
				}
			}
				else{
					echo json_encode("ERROR");
				}
			}
		}//cierre de else
	}


	public function actionRecepbitacora(){
		if(!isset($_POST['bitacora'])) $_POST['bitacora'] = 'nada';
		$myfile = fopen("recepbitacora/".$_POST['android'].$_POST['timestamp'], "w") or die("Unable to open file!");
		fwrite($myfile, $_POST['bitacora']);
		fclose($myfile);

		$obj = json_decode(utf8_encode($_POST['bitacora']));

		$fechas = explode(".",$obj->fechas);
		$acuerdos = explode("...sep...",$obj->acuerdos);

		$ts=intval($_POST['timestamp']/1000)+(3600*Yii::app()->params['gmt']);

		$sql = "
			INSERT INTO min_reunion(
				reu_id,
				reu_tiempo,
				reu_android,
				reu_tipo,
				eess_rut,
				eess_razon_social,
				eess_representante,
				reu_dirige,
				eess_apr,
				reu_lugar,
				reu_descripcion,
				reu_foto,
				reu_geo_x,
				reu_geo_y,
				reu_evaluador,
				reu_area,
				reu_jefe_area,
				reu_gerente_general,
				reu_gerente_operaciones,
				reu_participantes
			)VALUES(
				'".$_POST['timestamp']."',
				'".date("Y-m-d H:i:s", $ts)."',
				'".$_POST['android']."',
				'".$obj->bitacora_tipo."',
				'".$obj->bitacora_eess_rut."',
				'".$obj->bitacora_eess_nombre."',
				'".$obj->bitacora_eess_representante."',
				'".$obj->bitacora_dirigida."',
				'".$obj->bitacora_apr."',
				'".$obj->bitacora_ubicacion."',
				'".$obj->bitacora_descripcion."',
				'data:image/jpeg;base64,".$obj->general_foto."',
				'".$obj->geo_x."',
				'".$obj->geo_y."',
				'".$obj->evaluador."',
				'".$obj->bitacora_area."',
				'".$obj->bitacora_jefe_area."',
				'".$obj->bitacora_gerente_general."',
				'".$obj->bitacora_gerente_operaciones."',
				'".$obj->bitacora_participantes."'
			);
		";
		Yii::app()->db->createCommand($sql)->execute();

		for($i=0;$i<count($acuerdos);$i++){
			if($acuerdos[$i] != ''){
				$sql = "
					INSERT INTO min_reunion_acuerdo(
						acu_descripcion,
						acu_plazo,
						acu_foto,
						acu_seguimiento,
						reu_id
					)
					VALUES(
						'".$acuerdos[$i]."',
						'".$fechas[$i]."',
						'',
						'0',
						'".$_POST['timestamp']."'
					);
				";
				Yii::app()->db->createCommand($sql)->execute();
			}
		}


		// Actualizar correlativo
		$correlativo = Yii::app()->db->createCommand("SELECT MAX(reu_correlativo)+1 FROM min_reunion")->queryScalar();
		Yii::app()->db->createCommand("UPDATE min_reunion SET reu_correlativo = '".$correlativo."' WHERE reu_id = '".$_POST['timestamp']."';")->execute();

		/*
		// Enviar email a medio mundo
		$email = "
		<p>Se&ntilde;ores<br><b>".json_decode(json_encode($obj->tripode_eess_nombre))."</b><br></p>
		<p>Adjuntamos Minuta de <b>".$obj->tripode_tipo."</b> realizada el <b>".date("d-m-Y", $ts)."</b> en las instalaciones de <b>".$obj->tripode_ubicacion."</b>.
		<p>En cuanto a los acuerdos tomados, agradeceremos tomar las acciones necesarias para dar cumplimiento a los mismos en sus respectivos plazos.</p>
 		<p>La respectiva Minuta se encuentra disponible en el archivo adjunto a este mensaje o ingresando a la <a href='http://riesgoempresa.cl/terreno/login-eess/'>siguiente plataforma</a>, donde podrá dar respuesta y seguimiento al estado de cada uno de los Acuerdos.</p>
 		<p>Recordamos que las credenciales de acceso, tanto para el usuario y contraseña, son el Rut de su Empresa, sin puntos, guion ni dígito verificador (Rut: 1234567).</b>
		<p>Las consultas referidas a la Minuta favor dirigirlas al Ejecutor de la misma y que se encuentra identificado en el Informe.</b>
		<p>Atte.<br>Subgerencia Seguridad y Salud Ocupacional<br>Forestal Mininco S.A.<br></p>
		";
		$email_eess = Yii::app()->dbaxel->createCommand("SELECT concat(IFNULL(correo,''),', ', IFNULL(correo_apr,'')) FROM et_eess WHERE rut_eess = '".$obj->tripode_eess_rut."'")->queryScalar();
		// quitar eamil d eprueba
		//$email_eess = "";

		$email_subgerente = Yii::app()->dbaxel->createCommand("SELECT email_subgerente FROM et_area WHERE nombre_area = '".$obj->tripode_area."'")->queryScalar();
		$email_ingeniero_especialidad = Yii::app()->dbaxel->createCommand("SELECT email FROM et_ingeniero_especialidad WHERE nombre LIKE '%".trim(strtoupper($obj->tripode_ingeniero_especialidad))."%'")->queryScalar();
		$email_jefe_area = Yii::app()->dbaxel->createCommand("SELECT email FROM et_jefe_area WHERE nombre = '".trim(strtoupper($obj->tripode_jefe_area))."'")->queryScalar();

		// Excepción maderas
		if($obj->tripode_area == 'Gerencia Compra de Maderas'){
			$email_ingenieros = Yii::app()->dbaxel->createCommand("SELECT group_concat(correo separator ',') as correo FROM et_evaluador WHERE copiar_correo = 1")->queryScalar();
		}
		else{
			$email_ingenieros = Yii::app()->dbaxel->createCommand("SELECT group_concat(correo separator ',') as correo FROM et_evaluador WHERE copiar_correo = 1 AND area <> '8'")->queryScalar();
		}

		$archivo = chunk_split(base64_encode(file_get_contents("http://riesgoempresa.cl/terreno/Informes/InformeT.php?id=".$_POST['timestamp'])));
		$headers = "From: Minuta ".$obj->tripode_tipo." SSO en Terreno <informereunion@riesgoempresa.cl> \r\n";


		if(trim($obj->tripode_eess_nombre) == 'PRUEBA' || trim($obj->tripode_eess_nombre) == 'INNOAPSION') $headers .= "Cc: prueba@prueba.cl \r\n"; //
		else $headers .= "Cc: ".$email_subgerente.", ".$email_ingenieros.", ".$email_jefe_area.", ".$email_ingeniero_especialidad.", fpacheco@forestal.cmpc.cl, ogonzalez@forestal.cmpc.cl \r\n"; //


		$headers .= "Bcc: sebastian.carcamo398@gmail.com, eduardoacevedo@innoapsion.cl " . "\r\n"; //
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: multipart/mixed; boundary=\"=A=G=R=O=\"\r\n\r\n";
		$email_message = "--=A=G=R=O=\r\n";
		$email_message .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$email_message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
		$email_message .= $email . "\r\n\r\n";
		$email_message .= "--=A=G=R=O=\r\n";
		$email_message .= "Content-Type: application/octet-stream; name=\"Evaluaciones.pdf\"\r\n";
		$email_message .= "Content-Transfer-Encoding: base64\r\n";
		$email_message .= "Content-Disposition: attachment; filename=\"Evaluaciones.pdf\"\r\n\r\n";
		$email_message .= $archivo . "\r\n\r\n";
		$email_message .= "--=A=G=R=O=\r\n";

		$tipo_tr = substr($obj->tripode_tipo, 0, 1);

		if ($tipo_tr == 'R') {
			$asunto_tr = "Informe de Reunión SSO";
		}else if($tipo_tr == 'T'){
			$asunto_tr = "Informe Trípode SSO";
		}

		mail(utf8_decode($email_eess), utf8_decode($asunto_tr), utf8_decode($email_message), utf8_decode($headers)); // Quitar email de prueba
		*/

		echo json_encode("json_valido_sedecc");
	}

	public function actionError(){
		if(!isset($_POST['android'])) $_POST['android'] = 'nada';
		if(!isset($_POST['timestamp'])) $_POST['timestamp'] = 'nada';
		if(!isset($_POST['error'])) $_POST['error'] = 'nada';

		$myfile = fopen("errorlog/".$_POST['android'].$_POST['timestamp'], "w") or die("Unable to open file!");
		fwrite($myfile, $_POST['error']);
		fclose($myfile);
	}
}
