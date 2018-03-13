<?php 

if(!isset(Yii::app()->user->id)){
  header('Location: index.php?r=site/login');
}
?>
<style type="text/css">
 li: a: span{
      color:red;
    }
    .parcial{
    	width:50px;
    	text-align:right;
    	opacity:0.8;
    }
</style>
<?php
$where_evaluacion = "";
$where_evaluacion_2 = "";

// Cuando el tipo de usuario es empresa
if(Yii::app()->controller->usertype() == 1){
	$_POST['filtro_eess'] = Yii::app()->user->id;
	if($_POST['filtro_eess'] != "") $where_evaluacion.= " AND e.eess_rut = '".$_POST['filtro_eess']."' ";
}
// Cuando el tipo de usuario es evaluador
if(Yii::app()->controller->usertype() == 3){
	$eess = Yii::app()->db->createCommand("SELECT eess_rut FROM min_trabajador WHERE tra_rut = '".Yii::app()->user->id."'")->queryScalar();
	$_POST['filtro_eess'] = $eess;
	if($_POST['filtro_eess'] != "") $where_evaluacion.= " AND e.eess_rut = '".$_POST['filtro_eess']."' ";
}

if(isset($_POST['filtro_tipo'])){
	$where_evaluacion.= " ";
	//if($_POST['filtro_area'] != "") $where_evaluacion.= " AND UPPER(e.car_servicio) = UPPER('".$_POST['filtro_area']."') ";
	if($_POST['filtro_eess'] != "") $where_evaluacion.= " AND e.eess_rut = '".$_POST['filtro_eess']."' ";
	if($_POST['filtro_tipo'] != "") $where_evaluacion.= " AND e.eva_tipo = '".$_POST['filtro_tipo']."' ";
	if($_POST['filtro_trabajador'] != "") $where_evaluacion.= " AND (CONCAT(e.eva_nombres,e.eva_apellidos,e.tra_rut) LIKE '%".$_POST['filtro_trabajador']."%') ";
	if($_POST['filtro_trabajador_nombre'] != "") $where_evaluacion.= " AND (CONCAT(e.eva_nombres,e.eva_apellidos,e.tra_rut) LIKE '%".$_POST['filtro_trabajador_nombre']."%') ";
	//if($_POST['filtro_fundo'] != "") $where_evaluacion.= " AND e.eva_fundo = '".$_POST['filtro_fundo']."' ";
	//if($_POST['filtro_faena'] != "") $where_evaluacion.= " AND e.eva_faena = '".$_POST['filtro_faena']."' ";
	//if($_POST['filtro_desde'] != "") $where_evaluacion.= " AND e.eva_fecha_evaluacion > '".$_POST['filtro_desde']." 00:00:00'";
	//if($_POST['filtro_hasta'] != "") $where_evaluacion.= " AND e.eva_fecha_evaluacion < '".$_POST['filtro_desde']." 23:59:59'";
}
else{
	$where_evaluacion.= " ";
}
?>
<script type="text/javascript" src="js/amcharts/amcharts.js"></script>
  <script type="text/javascript" src="js/amcharts/dataloader.js"></script>
  <script type="text/javascript" src="js/amcharts/serial.js"></script>
  <script type="text/javascript" src="js/amcharts/gauge.js"></script>
  <script type="text/javascript" src="js/amcharts/pie.js"></script>
<!-- Chart code -->

<link href="https://www.amcharts.com/lib/3/plugins/export/export.css" media="all" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<script type="text/javascript" src="https://www.amcharts.com/lib/3/themes/dark.js"></script>
<script type="text/javascript" src="https://www.amcharts.com/lib/3/plugins/export/export.js"></script>


<h2 class="page-header">Reporte de Información</h2>
<h3>Ranking por Trabajador</h3>
<!--FILTROS-->
<form method="post">
<div class="row">
	
	<div class="col-sm-2" <?php if(Yii::app()->controller->usertype() == 1 || Yii::app()->controller->usertype() == 3) echo 'style="display:none;"';?>>
		<small>Empresa</small>
		<select name="filtro_eess" class="form-control input-sm" <?php if(Yii::app()->controller->usertype() == 1 || Yii::app()->controller->usertype() == 3) echo 'disabled';?> onchange="this.form.submit();">
			<?php
			if(Yii::app()->controller->usertype() == 1){
				$rows = Yii::app()->db->createCommand("SELECT * FROM min_eess WHERE eess_rut = '".Yii::app()->user->id."'")->query()->readAll();
			}
			else if(Yii::app()->controller->usertype() == 3){
				$eess = Yii::app()->db->createCommand("SELECT eess_rut FROM min_trabajador WHERE tra_rut = '".Yii::app()->user->id."'")->queryScalar();
				$rows = Yii::app()->db->createCommand("SELECT * FROM min_eess WHERE eess_rut = '".$eess."'")->query()->readAll();
			}
			else{
				$rows = Yii::app()->db->createCommand("SELECT * FROM min_eess")->query()->readAll();
				echo '<option value="">[Todas las empresas]</option>';
			}
			for($i=0;$i<count($rows);$i++){
				$selected = "";
				if(isset($_POST['filtro_eess'])) 
					if($_POST['filtro_eess'] == $rows[$i]['eess_rut']) 
						$selected = "selected";
				echo '<option '.$selected.' value="'.$rows[$i]['eess_rut'].'">'.$rows[$i]['eess_nombre_corto'].'</option>';
			}
			?>
		</select>
	</div>
	<div class="col-sm-2">
		<small>Actividad</small>
		<select name="filtro_tipo" class="form-control input-sm" onchange="this.form.submit();">
			<?php
			if(Yii::app()->controller->usertype() == 1){
				$rows = Yii::app()->db->createCommand("SELECT distinct eva_tipo FROM min_evaluacion e WHERE eess_rut = '".Yii::app()->user->id."' ")->query()->readAll();
			}
			else if(Yii::app()->controller->usertype() == 3){
				$eess = Yii::app()->db->createCommand("SELECT eess_rut FROM min_trabajador WHERE tra_rut = '".Yii::app()->user->id."'")->queryScalar();
				$rows = Yii::app()->db->createCommand("SELECT distinct eva_tipo FROM min_evaluacion e WHERE eess_rut = '".$eess."' ")->query()->readAll();
			}
			else{
				$rows = Yii::app()->db->createCommand("SELECT distinct eva_tipo FROM min_evaluacion e WHERE 1 ")->query()->readAll();
			}
			echo '<option value="">[Seleccione cargo]</option>';
			for($i=0;$i<count($rows);$i++){
				$selected = "";
				if(isset($_POST['filtro_tipo'])) 
					if($_POST['filtro_tipo'] == $rows[$i]['eva_tipo'])
					 $selected = "selected";

				echo '<option '.$selected.' value="'.$rows[$i]['eva_tipo'].'">'.$rows[$i]['eva_tipo'].'</option>';
				
			}
			$value = '';
			?>
		</select>
	</div>
	
	<div class="col-sm-2">
		<small>Rut Trabajador</small>
		<input name="filtro_trabajador" type="text" class="form-control input-sm" placeholder="Buscar por rut" value="<?php if(isset($_POST['filtro_trabajador'])) echo $_POST['filtro_trabajador'];?>">
	</div>
	<div class="col-sm-2">
		<small>Nombre Trabajador</small>
		<input name="filtro_trabajador_nombre" type="text" class="form-control input-sm" placeholder="Buscar por nombre" value="<?php if(isset($_POST['filtro_trabajador_nombre'])) echo $_POST['filtro_trabajador_nombre'];?>">
	</div>
	<div class="col-sm-2" id="contenedor_submit" style="display:none;">
		<input type="submit" class="btn btn-primary btn-block btn-sm" style="margin-top:20px;" value="Generar reporte">
	</div>
	
	<span style='float:right;'>

	<?php
	if(isset($_POST['filtro_tipo'])){
		$value2 = $_POST['filtro_tipo'];
		if(isset($_POST['filtro_eess']))
		{
			$value1 = $_POST['filtro_eess'];
		}else{}
		
	}else{
		$value1 = '';
	$value2 = '';
	}


	
	

	 echo CHtml::link('<img src="img/descarga.png" width="40px;">',array('Reporte2','param1'=>$selected,
                                         'param2'=>$value1,
                                         'param3'=>$value2,
                                         'param4'=>''),array('title'=>'Exportar XLS'));
                                         ?>
     <!--<a id="exportar_xls" href="exportarReporte2.php" alt="Exportar a Excel" class="btn btn-sm btn-success" onclick="updateurl();"><i class="i i-file-excel"></i></a>
-->


	</span> 

</div>

</form>
<div class="col-sm-2" id="btn_reporte" style="float:right;">
		<a href="index.php?r=site/page&view=reporte1">
		   <button>Ver Ranking</button>
		</a>
</div>
<script>
function updateurl(){
a = document.getElementByTagName('filtro_tipo').value;
e = document.getElementByTagName('filtro_eess').value;
document.getElementById('exportar_xls').href = 'exportarReporte2.php?a='+a+'&e='+e;
}
</script>
<style>
	.btn-success{
		background:#119A0E;
	}
</style>

<?php if(!isset($_POST['filtro_tipo'])) return; ?>


<!-- <h4>Evaluaciones con Riesgo Alto</h4> sin titulo-->
<?php
$rows = Yii::app()->db->createCommand("SELECT * FROM min_evaluacion e WHERE 1 ".$where_evaluacion."")->queryAll();
if(count($rows)==0){
	echo '<div class="alert alert-warning">Sin resultados</div>';
	return;
}

// Parche categorías
if($_POST['filtro_tipo'] == 'Motosierristas Cosecha y Raleo'){
	$rows[0]['eva_item_nombre_0'] = 'TOCONES';

	$rows[0]['eva_item_nombre_2'] = 'PLANIFICACIÓN';
	$rows[0]['eva_item_nombre_3'] = 'TRANSVERSAL';
	//$rows[0]['eva_fecha_evaluacion'] = 'FECHA EVALUACION';
}

$hdrs = "";
for($j=0;$j<20;$j++){
	if(isset($rows[0]['eva_item_nombre_'.$j])) if($rows[0]['eva_item_nombre_'.$j] != '') $hdrs.= '<th>'.$rows[0]['eva_item_nombre_'.$j].'</th>';
}

echo '<table class="table" style="background:#ffffff; font-size:8pt;">
<thead>
	<th>RUT Trabajador</th>
	<th>Nombre completo</th>
	<th>Fecha Evaluacion</th>
	'.$hdrs.'
	<th>Porcentaje de Cumplimiento</th>
</thead>
';
// Actualizar porcentaje cache
				$limit1 = Yii::app()->params['riesgoalto'];
				$limit2 = Yii::app()->params['riesgomedio'];

				$MalNotaBaja = Yii::app()->params['MalNotaBaja'];
          		$RalLimBajo = Yii::app()->params['RalLimBajo'];

          		$MalNotaMedia = Yii::app()->params['MalNotaMedia'];
          		$RalLimMedio = Yii::app()->params['RalLimMedio'];

          		$MalNotaAlta = Yii::app()->params['MalNotaAlta'];
				$RalLimAlto = Yii::app()->params['RalLimAlto'];
for($i=0;$i<count($rows);$i++){
	$hdrs = "";
	for($j=0;$j<20;$j++){
		if(isset($rows[$i]['eva_item_nombre_'.$j])) if($rows[$i]['eva_item_nombre_'.$j] != ''){
			if($rows[$i]['eva_item_nota_'.$j]!=''){

				//$style = 'success';
				//if($rows[$i]['eva_item_nota_'.$j]<Yii::app()->params['riesgomedio'])$style = 'warning';
				//if($rows[$i]['eva_item_nota_'.$j]<Yii::app()->params['riesgoalto'])$style = 'danger';
				if($rows[$i]['eva_item_nota_'.$j]>=0 &&  $rows[$i]['eva_item_nota_'.$j]<=$limit1) $style = 'danger';
				if($rows[$i]['eva_item_nota_'.$j]>$limit1 &&  $rows[$i]['eva_item_nota_'.$j]<=$limit2) $style = 'warning';
				if($rows[$i]['eva_item_nota_'.$j]>$limit2 &&  $rows[$i]['eva_item_nota_'.$j]<=100) $style = 'success';
		
				$hdrs.= '<td><div class="parcial btn btn-'.$style.' btn-xs">'.$rows[$i]['eva_item_nota_'.$j].' %</div></td>';
			}
			else{
				$hdrs.= '<td></td>';
			}
		}
	}
	
	//$style = 'success';
	//if($rows[$i]['eva_cache_porcentaje']<Yii::app()->params['riesgomedio'])$style = 'warning';
	//if($rows[$i]['eva_cache_porcentaje']<Yii::app()->params['riesgoalto'])$style = 'danger';
	$cache_porcentaje=floor($rows[$i]['eva_cache_porcentaje']);
	if($cache_porcentaje>=0 &&  $cache_porcentaje<=$limit1) $style = 'danger';
				if($cache_porcentaje>$limit1 &&  $cache_porcentaje<=$limit2) $style = 'warning';
				if($cache_porcentaje>$limit2 &&  $cache_porcentaje<=100) $style = 'success';

	echo '
	<tr>
		<td><a href="index.php?r=evaluacion/view&id='.$rows[$i]['eva_id'].'" target="_blank">'.$rows[$i]['tra_rut'].'</a></td>
		<td><a href="index.php?r=evaluacion/view&id='.$rows[$i]['eva_id'].'" target="_blank">'.$rows[$i]['eva_nombres'].' '.$rows[$i]['eva_apellidos'].'</a></td>
		<td><a href="index.php?r=evaluacion/view&id='.$rows[$i]['eva_id'].'" target="_blank">'.$rows[$i]['eva_fecha_evaluacion'].'</a></td>
		'.$hdrs.'
		<td><div class="btn btn-'.$style.' btn-block btn-xs">'.floor($rows[$i]['eva_cache_porcentaje']).' %</div></td>
	</tr>
	';
}
echo '</table>';  
?>







