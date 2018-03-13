<?php
/* @var $this AccidenteController */
/* @var $model Accidente */

$this->breadcrumbs=array(
	'Accidentes'=>array('index'),
	$model->id_accidente,
);
?>


<span style='float:right;'>
<?php echo CHtml::link('<i class="i i-list"></i>',array('index'),array('class'=>'btn btn-rounded btn-sm btn-icon btn-default')); ?>
<?php echo CHtml::link('<i class="i i-plus2"></i>',array('create'),array('class'=>'btn btn-rounded btn-sm btn-icon btn-default')); ?>
<?php echo CHtml::link('<i class="i i-pencil2"></i>',array('update','id'=>$model->id_accidente),array('class'=>'btn btn-rounded btn-sm btn-icon btn-default')); ?>
<?php echo CHtml::link('<i class="i i-cross2"></i>',array('trash','id'=>$model->id_accidente),array('class'=>'btn btn-rounded btn-sm btn-icon btn-default','onclick'=>'return confirm(\'¿Realmente desea eliminar?\');'));?>
<?php echo CHtml::link('<i class="i i-list2"></i>',array('admin'),array('class'=>'btn btn-rounded btn-sm btn-icon btn-default')); ?>
</span> 
<h1>Detalle Accidente #<?php echo $model->id_accidente; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_accidente',
		'rut_eess',
		'rut_trabajador',
		'tra_cargo',
		'tra_depto',
		'acc_tipo_accidnte',
		'fecha_accidente',
		'fecha_alta',
		'Descripcion',
	),
)); ?>
