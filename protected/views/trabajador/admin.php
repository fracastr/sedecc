<?php 

if(!isset(Yii::app()->user->id)){
  header('Location: index.php?r=site/login');
}
?>
<?php
/* @var $this TrabajadorController */
/* @var $model Trabajador */

$this->breadcrumbs=array(
	'Trabajadors'=>array('index'),
	'Administrar',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#trabajador-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

$modificar_visible = 'false';
$eliminar_visible = 'false';

if(Yii::app()->controller->usertype() == 1){
	$modificar_visible = 'true';
	$eliminar_visible = 'true';
}
if(Yii::app()->controller->usertype() == 2){
	$modificar_visible = 'true';
	$eliminar_visible = 'true';
}
if(Yii::app()->controller->usertype() == 3){
	$modificar_visible = 'true';
}

?>


<span style='float:right;'>
<?php echo CHtml::link('<img src="img/agregar.png" width="40px;">',array('create'),array('title'=>'Nuevo')); ?>
<!--?php echo CHtml::link('<i class="i i-list2"></i>',array('index'),array('class'=>'btn btn-rounded btn-sm btn-icon btn-default')); ?-->
<?php echo CHtml::link('<img src="img/busqueda.png" width="40px;">','#',array('title'=>'Buscar','class'=>'search-button')); ?>

<?php echo CHtml::link('<img src="img/descarga.png" width="40px;">',array('excel'),array('title'=>'Exportar XLS')); ?>

</span> 
<h1>Trabajadores</h1>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'trabajador-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'tra_id',
		//'tra_creado',
		/*array(
			'name'=>'eess_rut',
			'type'=>'raw',
			'value'=>function($data){
				return Yii::app()->db->createCommand("SELECT eess_nombre_corto FROM min_eess WHERE eess_rut = '".$data->eess_rut."'")->queryScalar();
			},
		),*/
		 array(
    	'name'=>'tra_rut',
    	'htmlOptions'=>array('width'=>'60px'),
  		),
		array(
    	'name'=>'tra_dv',
    	'htmlOptions'=>array('width'=>'30px'),
  		),
		
		'tra_nombres',
		'tra_apellidos',
		'tra_fecha_nacimiento',
		array(
			'name'=>'tra_vencimiento_corma',
			'type'=>'raw',
			'value'=>function($data){
				if($data->tra_vencimiento_corma < date("Y-m-d")) return '<span style="color:red;">'.$data->tra_vencimiento_corma.'</span>'; // color rojo
				else return '<span style="color:green;">'.$data->tra_vencimiento_corma.'</span>'; // Sin color
				//'value'=>'date_format(date_create($data->start_date), "d-m-Y ")',
			},
		),
		array(
			'name'=>'tra_vencimiento_examen',
			'type'=>'raw',
			'value'=>function($data){
				if($data->tra_vencimiento_examen < date("Y-m-d")) return '<span style="color:red;">'.$data->tra_vencimiento_examen.'</span>'; // color rojo
				else return '<span style="color:green;">'.$data->tra_vencimiento_examen.'</span>'; // Sin color
			},
		),

		'tra_licencia_conducir',
		
		//'car_id',
		array(
			'name'=>'tra_vencimiento_licencia_conducir',
			'type'=>'raw',
			'value'=>function($data){
				if($data->tra_vencimiento_licencia_conducir < date("Y-m-d")) return '<span style="color:red;">'.$data->tra_vencimiento_licencia_conducir.'</span>'; // color rojo
				else return '<span style="color:green;">'.$data->tra_vencimiento_licencia_conducir.'</span>'; // Sin color
			},
		),


		
		array(
			'name'=>'car_id',
			'type'=>'raw',
			'value'=>function($data){
				return Yii::app()->db->createCommand("SELECT car_descripcion FROM min_cargo WHERE car_id = '".$data->car_id."'")->queryScalar();
			},
		),
		array(
			'class'=>'CButtonColumn',
			'buttons'=>array(
				'view' => array(
    				'options'=>array('title'=>'Ver'),
				),
				'update' => array(
					'visible'=>$modificar_visible,
    				'options'=>array('title'=>'Modificar'),
				),
				'trash' => array(
					'visible'=>$eliminar_visible,
				),
			),
		),
	),
)); ?>
