<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDuojqC59sKz9CrNOHGyyAXCzxwl_f1NVA" type="text/javascript"></script>
<?php 

if(!isset(Yii::app()->user->id)){
  header('Location: index.php?r=site/login');
}

$where = "";	
$where2 = "";
$tipo = "evaluador";

// Lógica para tipos de usuario eess
if(Yii::app()->controller->usertype() == 1){
	$where.=" AND eess_rut = '".Yii::app()->user->id."' ";
}
			
// Lógica para tipos de usuario evaluador
if(Yii::app()->controller->usertype() == 3){
	$eess = Yii::app()->db->createCommand("SELECT eess_rut FROM min_trabajador WHERE tra_rut = '".Yii::app()->user->id."'")->queryScalar();
	$where.=" AND eess_rut = '".$eess."' ";
}

$evaluador = Yii::app()->db->createCommand("SELECT DISTINCT eva_evaluador FROM min_evaluacion WHERE 1 ".$where." AND TRIM(eva_evaluador) != '' AND TRIM(eva_evaluador) IS NOT NULL")->query()->readAll();
for($i=0;$i<count($evaluador);$i++){
	$evaluador[$i]['nombre_evaluador'] = Yii::app()->db->createCommand("SELECT CONCAT(tra_nombres,' ',tra_apellidos) as nombre_evaluador FROM min_trabajador WHERE tra_rut = '".$evaluador[$i]['eva_evaluador']."'")->queryScalar();
}
$ano = Yii::app()->db->createCommand("SELECT DISTINCT YEAR(eva_fecha_evaluacion) as ano FROM min_evaluacion WHERE 1 ".$where." ORDER BY YEAR(eva_fecha_evaluacion)")->query()->readAll();
$mes = Yii::app()->db->createCommand("SELECT DISTINCT MONTH(eva_fecha_evaluacion) as mes FROM min_evaluacion WHERE 1 ".$where." ORDER BY MONTH(eva_fecha_evaluacion)")->query()->readAll();

$o_evaluador='';
for($i=0;$i<count($evaluador);$i++){
	$selected = '';
	if(isset($_POST['evaluador'])) if($_POST['evaluador'] == $evaluador[$i]['eva_evaluador']) $selected = 'selected';
	$o_evaluador.= '<option '.$selected.' value="'.$evaluador[$i]['eva_evaluador'].'">'.$evaluador[$i]['nombre_evaluador'].'</option>'; 
}

$o_ano='';
for($i=0;$i<count($ano);$i++){
	$selected = '';
	if(isset($_POST['ano'])) if($_POST['ano'] == $ano[$i]['ano']) $selected = 'selected';
	$o_ano.= '<option '.$selected.' value="'.$ano[$i]['ano'].'">'.$ano[$i]['ano'].'</option>'; 
}
$o_mes='';
for($i=0;$i<count($mes);$i++){
	$selected = '';
	if(isset($_POST['mes'])) if($_POST['mes'] == $mes[$i]['mes']) $selected = 'selected';
	$o_mes.= '<option '.$selected.' value="'.$mes[$i]['mes'].'">'.$mes[$i]['mes'].'</option>'; 
}
?>



    <style>
      #map {
        height: 100%;
        margin-left:-20px;
        margin-right:-20px;
      }
    </style>
    
    <div id="map"></div>
    <script>

      function initMap() {
        var myLatLng = {lat: -37.600, lng: -73.044};

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 8,
          center: myLatLng
        });

			// ACÁ SE REPITEN LOS MARCADORES
			<?php
			$where_mapa = '';
			
			if(isset($_POST['evaluador'])) if($_POST['evaluador'] != '') $where_mapa.= " AND E.eva_evaluador = '".$_POST['evaluador']."'";
			if(isset($_POST['ano'])) if($_POST['ano'] != '') $where_mapa.= " AND YEAR(eva_fecha_evaluacion) = '".$_POST['ano']."'";
			if(isset($_POST['mes'])) if($_POST['mes'] != '') $where_mapa.= " AND MONTH(eva_fecha_evaluacion) = '".$_POST['mes']."'";
			
			// Lógica para tipos de usuario eess
			if(Yii::app()->controller->usertype() == 1){
				$where2.=" AND E.eess_rut = '".Yii::app()->user->id."' ";
			}
			
			// Lógica para tipos de usuario evaluador
			if(Yii::app()->controller->usertype() == 3){
				$eess = Yii::app()->db->createCommand("SELECT eess_rut FROM min_trabajador WHERE tra_rut = '".Yii::app()->user->id."'")->queryScalar();
				$where2.=" AND E.eess_rut = '".$eess."' ";
			}
			
			$sql = "
			SELECT E.eva_geo_x, E.eva_geo_y, E.eva_id, E.eva_nombres, E.eva_apellidos, E.eva_fecha_evaluacion, E.eess_rut, L.tra_rut, L.tra_color
			FROM min_evaluacion as E
			left join min_trabajador as L on(E.eva_evaluador = L.tra_rut)
			WHERE 1 ".$where_mapa." ".$where2."
			AND E.eva_geo_x != ''
			AND E.eva_geo_y != ''
			";
			$evaluaciones = Yii::app()->db->createCommand($sql)->query()->readAll();
			
			?>
			<?php
			for($i=0;$i<count($evaluaciones);$i++){
			?>
			
			var marker<?php echo $i;?> = new google.maps.Marker({
              position: new google.maps.LatLng(<?php echo $evaluaciones[$i]['eva_geo_x'];?>, <?php echo $evaluaciones[$i]['eva_geo_y'];?>),
              map: map,
              icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|<?php echo $evaluaciones[$i]['tra_color'];?>',
              draggable: false
            });
            var info<?php echo $i;?> = new google.maps.InfoWindow({content: '<div class="map-popup"><h4>Evaluación <?php echo $evaluaciones[$i]['eva_id'];?></h4><p><?php echo trim($evaluaciones[$i]['eva_nombres'].' '.$evaluaciones[$i]['eva_apellidos']);?></p><p><a href="index.php?r=evaluacion/view&id=<?php echo $evaluaciones[$i]['eva_id'];?>" target="_blank">Ir a la evaluación</a></p></div>',
            });
            google.maps.event.addListener(marker<?php echo $i;?>, 'mouseover', function() {
              info<?php echo $i;?>.open(map, marker<?php echo $i;?>);
              setTimeout(function(){ info<?php echo $i;?>.close(); }, 3000);
            });
			
			<?php
			}
			?>
            // FIN DE MARCADORES
      }
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuojqC59sKz9CrNOHGyyAXCzxwl_f1NVA&callback=initMap"></script>
    


<?php
echo '<form class="row" method="post" style="position:absolute; z-index:1000; top:9px; left:130px;">';
if ($tipo == "evaluador"){
echo'
		<select name="evaluador" onchange="this.parentNode.submit();" class="form-control input-sm" style="display:inline;width:120px;">
			<option value="">[Evaluador]</option>
			'.$o_evaluador.'
		</select>	
	';
}

echo'
		<select name="ano" onchange="this.parentNode.submit();" class="form-control input-sm" style="display:inline;width:70px;">
			<option value="">[Año]</option>
			'.$o_ano.'
		</select>	
	
		<select name="mes" onchange="this.parentNode.submit();" class="form-control input-sm" style="display:inline;width:70px;">
			<option value="">[Mes]</option>
			'.$o_mes.'
		</select>	
	
</form>
';
?>