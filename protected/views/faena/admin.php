<?php 

if(!isset(Yii::app()->user->id)){
  header('Location: index.php?r=site/login');
}
?>
<?php
/* @var $this FaenaController */
/* @var $model Faena */

$this->breadcrumbs=array(
	'Faenas'=>array('index'),
	'Administrar',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#faena-grid').yiiGridView('update', {
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
</span> 
<h1>Faenas</h1>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'faena-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'fae_nombre',
		//'fae_activo',
		array(
			'name'=>'fae_activo',
			'type'=>'raw',
			'value'=>function($data){
				if($data->fae_activo == 1) return 'Activo'; else return 'Inactivo';
			}
		),
		'tipo',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
