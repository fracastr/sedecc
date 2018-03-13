<?php 

if(!isset(Yii::app()->user->id)){
  header('Location: index.php?r=site/login');
}
?>
<?php
if(Yii::app()->controller->usertype() == 1) return; 

/* @var $this EessController */
/* @var $model Eess */

$this->breadcrumbs=array(
	'Eesses'=>array('index'),
	'Administrar',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#eess-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>


<span style='float:right;'>
<?php echo CHtml::link('<img src="img/agregar.png" width="40px;">',array('create'),array('title'=>'Nuevo')); ?>
<!--?php echo CHtml::link('<i class="i i-list2"></i>',array('index'),array('class'=>'btn btn-rounded btn-sm btn-icon btn-default')); ?-->
<?php echo CHtml::link('<img src="img/busqueda.png" width="40px;">','#',array('title'=>'Buscar','class'=>'search-button')); ?>

<?php echo CHtml::link('<img src="img/descarga.png" width="40px;">',array('excel'),array('title'=>'Exportar XLS')); ?>
</span> 
<h1>Empresas de servicio</h1>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'eess-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'eess_rut',
		'eess_creado',
		'eess_nombre_corto',
		'eess_razon_social',
		'eess_ciudad',
		'eess_telefono',
		/*
		'eess_email',
		'eess_representante',
		'eess_representante_telefono',
		'eess_representante_email',
		'eess_clave',
		'eess_logo',
		'eess_estado',
		*/
		array(
			'class'=>'CButtonColumn',
			'buttons'=>array(
				'view' => array(
    				'options'=>array('title'=>'Ver'),
				),
				'update' => array(
    				'options'=>array('title'=>'Modificar'),
				),
				'trash' => array(
    				'options'=>array('title'=>'Eliminar'),
				),
			),
		),
	),
)); ?>
