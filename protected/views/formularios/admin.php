<?php
/* @var $this FormulariosController */
/* @var $model Formularios */

$this->breadcrumbs=array(
	'Formularioses'=>array('index'),
	'Administrar',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#formularios-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>


<span style='float:right;'>
<?php echo CHtml::link('<i class="i i-plus2"></i>',array('create'),array('class'=>'btn btn-rounded btn-sm btn-icon btn-default')); ?>
<?php echo CHtml::link('<i class="i i-list2"></i>',array('index'),array('class'=>'btn btn-rounded btn-sm btn-icon btn-default')); ?>
<?php echo CHtml::link('<i class="i i-search"></i>','#',array('class'=>'search-button btn btn-rounded btn-sm btn-icon btn-default')); ?>
</span> 
<h1>Administrar Formularioses</h1>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'formularios-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'correlativo_chk_eess',
		'checklist',
		'tipo_checklist',
		'eess_rut',
		'n_campos',
		'campo',
		/*
		'nombre_campos',
		'campos_values',
		'campos_requeridos',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
