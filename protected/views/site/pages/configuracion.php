<?php

if(!isset(Yii::app()->user->id)){
  header('Location: index.php?r=site/login');
}
?>
<?php
/*if(isset($_POST['eesscheck'])){
	//print_r($_POST['eesscheck']);
	Yii::app()->db->createCommand("DELETE FROM min_check_activo")->execute();
	for($i=0;$i<count($_POST['eesscheck']);$i++){
		$tmp = json_decode($_POST['eesscheck'][$i]);
		Yii::app()->db->createCommand("REPLACE INTO min_check_activo VALUES(NULL,'".$tmp[0]."','".$tmp[1]."')")->execute();
	}
	echo '<div class="alert alert-success">Se almacenó la configuración con éxito.</div>';
}
*/
?>

<h2 class="page-header">Configuración</h2>

<div id="exTab1">
	<ul class="nav nav-pills">
		<li class="active"><a href="#1a" data-toggle="tab">Checklist activos EESS</a></li>
		<!--li><a href="#2a" data-toggle="tab">Using nav-pills</a></li>
		<li><a href="#3a" data-toggle="tab">Applying clearfix</a></li>
  		<li><a href="#4a" data-toggle="tab">Background color</a></li-->
	</ul>
	<div class="tab-content clearfix">
		<div class="tab-pane active" id="1a">
          <!--?php
          $eess = Yii::app()->db->createCommand("SELECT * FROM min_eess ORDER BY eess_nombre_corto")->queryAll();
		  $chec = Yii::app()->db->createCommand("SELECT distinct car_id FROM min_pregunta")->queryAll();
		  echo '<form method="post"><table class="table table-striped table-bordered" style="font-size:8pt; background-color: white;">';
		  echo '<thead><th>EESS</th>';
		  for($i=0;$i<count($chec);$i++){
		  	echo '<th>'.substr($chec[$i]['car_id'],0,20).'</th>';
		  }
		  echo '</thead>';
		  for($i=0;$i<count($eess);$i++){
		  	echo '<tr><td>'.$eess[$i]['eess_nombre_corto'].'</td>';
			for($j=0;$j<count($chec);$j++){
				$c = Yii::app()->db->createCommand("SELECT COUNT(*) FROM min_check_activo WHERE act_eess = '".$eess[$i]['eess_rut']."' AND act_car = '".$chec[$j]['car_id']."'")->queryScalar();
				if($c == 0) $activo = ''; else $activo = 'checked';
				echo '<td><input '.$activo.' name="eesscheck[]" value=\''.(json_encode(array($eess[$i]['eess_rut'],$chec[$j]['car_id']))).'\' type="checkbox" style="display:block; width:100%;"></td>';
			}
			echo '</tr>';
		  }
		  echo '<tr><td colspan="80" style="padding:0px; margin:0px;"><input type="submit" value="Guardar" class="btn btn-primary btn-block"></td></tr>';
		  echo '</table></form>';
          ?-->


          <?php
          $eess = Yii::app()->db->createCommand("SELECT * FROM min_eess ORDER BY eess_nombre_corto")->queryAll();
		  $chec = Yii::app()->db->createCommand("SELECT distinct car_id, eess_rut FROM min_pregunta")->queryAll();
		  echo '<form method="post"><table class="table table-striped table-bordered" style="font-size:8pt; background-color: white;">';
		  for($i=0;$i<count($eess);$i++){
		  echo '<a class="btn btn-block" data-toggle="collapse" data-target="#demo'.$i.'" style="margin-top:5px; color:#ffffff;">'.$eess[$i]['eess_nombre_corto'].'</a>
			<div id="demo'.$i.'" class="collapse" style="background:#ffffff; padding:10px;">';
			for($j=0;$j<count($chec);$j++){
				$c = Yii::app()->db->createCommand("SELECT COUNT(*) FROM min_check_activo WHERE act_eess = '".$eess[$i]['eess_rut']."' AND act_car = '".$chec[$j]['car_id']."'")->queryScalar();
				if($c == 0) $activo = ''; else $activo = 'checked';
				$nombreeess = '';
				if($chec[$j]['car_id'] != '' && $chec[$j]['car_id'] != '0'){
					$nombreeess = Yii::app()->db->createCommand("SELECT eess_nombre_corto FROM min_eess WHERE eess_rut = '".$chec[$j]['eess_rut']."'")->queryScalar();
					$nombreeess = '<span class="label label-info">'.$nombreeess.'</span>';
				}
				echo '<label><input '.$activo.' name="eesscheck[]" value=\''.(json_encode(array($eess[$i]['eess_rut'],$chec[$j]['car_id']))).'\' type="checkbox"> '.$nombreeess.' <small>'.$chec[$j]['car_id'].'</small></label><br>';
			}
		  echo '</div>
		  ';
		  }
		  //echo '<input type="submit" value="Guardar" class="btn btn-primary btn-block">';
		  echo '</form>';
          ?>
		</div>
	</div>
</div>
